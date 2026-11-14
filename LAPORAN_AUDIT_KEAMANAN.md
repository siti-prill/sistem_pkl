# Laporan Audit Keamanan Aplikasi PKL
## SMKN 2 Padang Panjang

| Item | Keterangan |
|---|---|
| Target aplikasi | `https://pkl.smkn2-padangpanjang.sch.id` |
| Source code yang diaudit | Repository lokal `sistem_pkl` (Laravel 13.37, PHP 8.3+) |
| Tanggal audit | 6 November 2026 |
| Metode | Review kode (white-box), pengujian lokal, pemeriksaan pasif konfigurasi produksi |
| Penguji | _[isi nama penguji]_ |
| Status dokumen | Draft temuan untuk diperbaiki sekolah |

---

## 1. Dasar dan batasan pengujian

Audit dilakukan atas penugasan sekolah untuk menguji keamanan aplikasi PKL. Pengujian dibatasi pada hal yang tidak merusak layanan dan data:

- **Dilakukan:** review source code, pengujian di salinan lokal, pemeriksaan header/TLS/port secara pasif, serta uji rate limit login terbatas.
- **Tidak dilakukan:** brute force kredensial, eksploitasi aktif, pengambilan data produksi, dan pengujian yang berpotensi mengganggu layanan.
- **Port database** hanya diidentifikasi sampai tahap handshake. Tidak ada percobaan login ke database produksi.
- Pengujian lokal memakai database terpisah dan **transaction rollback**, sehingga tidak ada data yang berubah permanen.

> Catatan: beberapa kontrol keamanan di source code sudah diperbaiki. Perlu dipastikan versi yang berjalan di produksi benar-benar sama dengan source yang diaudit.

---

## 2. Ringkasan eksekutif

Ditemukan **1 temuan kritis, 1 tinggi, 4 sedang, 6 rendah, dan 3 informasional**.

Prioritas utama:

1. **Tutup port database MariaDB (3306) dari internet.** Ini risiko terbesar karena database dapat dijangkau langsung dari luar tanpa melalui aplikasi.
2. **Hilangkan password default `password` pada fitur import.** Akun hasil import dapat diambil alih dengan menebak NIS/email.
3. **Perbaiki stored XSS di dashboard admin dan formula injection pada export Excel.** Keduanya dapat berujung pada pengambilalihan sesi admin atau eksekusi konten berbahaya saat file dibuka.

Kontrol yang sudah baik: login memakai parameter binding (tidak rentan SQL injection klasik), rate limit login aktif, middleware role berjalan, pengecekan kepemilikan data (IDOR) sudah ada di modul jurnal/nilai/laporan, `role` tidak mass-assignable, dan validasi upload gambar sudah memadai.

---

## 3. Matriks temuan

| ID | Temuan | Severity | Area | Status |
|---|---|---|---|---|
| K-01 | Port MariaDB 3306 terbuka ke internet | **Kritis** | Infrastruktur | Perlu tindakan segera |
| T-01 | Password default `password` pada import massal | **Tinggi** | Autentikasi / import | Belum diperbaiki |
| S-01 | Stored XSS di dashboard admin | **Sedang** | Web / XSS | Belum diperbaiki |
| S-02 | Excel formula injection pada export pengajuan | **Sedang** | Export / integritas file | Belum diperbaiki |
| S-03 | Registrasi publik terbuka, auto-login, tanpa verifikasi email | **Sedang** | Manajemen akun | Belum diperbaiki |
| S-04 | Kebijakan password lemah (minimum 8 karakter) | **Sedang** | Autentikasi | Belum diperbaiki |
| R-01 | CSP terlalu minimal (tidak membatasi script) | **Rendah** | Hardening | Belum diperbaiki |
| R-02 | `ForceHttps` mempercayai header `X-Forwarded-Proto` | **Rendah** | Konfigurasi | Belum diperbaiki |
| R-03 | Link sosial `target="_blank"` tanpa `rel` dan tanpa validasi URL | **Rendah** | Web / hardening | Belum diperbaiki |
| R-04 | Perbedaan waktu respons login NIS (timing enumeration) | **Rendah** | Autentikasi | Belum diperbaiki |
| R-05 | Route `home` tidak terdefinisi pada alur tanpa data siswa | **Rendah** | Stabilitas | Bug fungsional |
| R-06 | Port 21, 2083, 2087 terbuka ke internet | **Rendah** | Infrastruktur | Perlu verifikasi |
| I-01 | Kemungkinan selisih versi produksi vs source | **Info** | Deployment | Perlu verifikasi |
| I-02 | Kredensial produksi sempat dibagikan di chat | **Info** | Operasional | Rotasi disarankan |
| I-03 | Parsing file import dari pihak ketiga | **Info** | Dependency | Pantau pembaruan |

---

## 4. Detail temuan

### K-01 — Port MariaDB 3306 terbuka ke internet

**Severity: Kritis**  
**Komponen:** Infrastruktur hosting, database produksi

**Bukti:**

- Port `3306` pada server produksi merespons koneksi dari internet.
- Banner yang terbaca: `5.5.5-10.11.19-MariaDB-cll-lve`.
- Server mengembalikan `ERROR 1045 (28000): Access denied`, yang berarti koneksi TCP berhasil mencapai database dan server meminta kredensial.

**Dampak:**

- Database dapat diserang langsung tanpa melewati aplikasi, rate limit, atau WAF.
- Memungkinkan credential brute force, eksploitasi kerentanan MariaDB, dan kebocoran data siswa/guru/industri jika kredensial lemah.
- Banner versi mempermudah penyerang menyesuaikan serangan.

**Rekomendasi:**

1. Di hPanel Hostinger, buka **Databases → Remote MySQL** dan hapus akses `%` (semua IP). Tambahkan hanya IP sekolah/pengelola jika memang diperlukan.
2. Minta Hostinger menutup inbound port `3306` dari internet melalui firewall, atau membatasi `bind-address` ke `127.0.0.1`.
3. Ganti password user database dan periksa kembali daftar user/grant.
4. Jika akses jarak jauh diperlukan, gunakan SSH tunnel, bukan koneksi langsung.
5. Periksa log koneksi database untuk aktivitas mencurigakan.
6. Verifikasi ulang dari luar setelah perbaikan: port 3306 harus `timeout`/`refused`.

> Port lain yang juga teridentifikasi terbuka: `21` (FTP), `2083`, dan `2087` (cPanel). Jika tidak dipakai, batasi ke IP pengelola.

---

### T-01 — Password default `password` pada import massal

**Severity: Tinggi**  
**Komponen:** `app/Imports/SiswaImport.php:35`, `app/Imports/GuruImport.php:35`, `app/Imports/IndustriImport.php:35`

**Bukti:**

```php
$password = trim((string) ($row['password'] ?? 'password')) ?: 'password';
```

Jika kolom `password` di file Excel kosong, akun dibuat dengan password `password`. Tidak ada validasi kekuatan password dan tidak ada paksaan ganti password saat login pertama.

**Dampak:**

- Semua akun hasil import yang tidak mengisi kolom password memakai kredensial yang sama.
- NIS siswa relatif mudah ditebak (contoh format `NIS001`), sehingga akun dapat diambil alih.
- Akun guru/industri hasil import juga berisiko, termasuk akses ke data penempatan dan penilaian.
- Password `password` juga lolos aturan minimum 8 karakter yang dipakai saat ini.

**Rekomendasi:**

1. Jangan pernah memakai password default. Generate password acak per akun:

```php
use Illuminate\Support\Str;

$plain = trim((string) ($row['password'] ?? ''));

if ($plain === '') {
    $plain = Str::password(16); // acak, kuat
}

// Validasi kekuatan jika password diisi manual dari file
Validator::make(
    ['password' => $plain],
    ['password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()]]
)->validate();
```

2. Simpan hash saja, dan tampilkan password awal **sekali** kepada admin (unduh daftar kredensial) atau kirim link reset ke email user.
3. Tambahkan kolom `must_change_password` dan paksa ganti password pada login pertama.
4. Untuk akun yang sudah terlanjur dibuat, lakukan reset password massal.

---

### S-01 — Stored XSS di dashboard admin

**Severity: Sedang**  
**Komponen:** `resources/views/admin/dashboard.blade.php:184` dan `:425`

**Bukti:**

```blade
<button onclick="showWarning('{{ $item->siswa->nama_siswa ?? '' }}')" ...>
```

```js
function showWarning(nama) {
    Swal.fire({
        title: 'Ingatkan Siswa',
        html: `Anda akan mengingatkan <strong>${nama}</strong> untuk mengisi jurnal hari ini.`,
        ...
    });
}
```

Nama siswa dimasukkan ke dalam string JavaScript di atribut `onclick`, lalu dimasukkan lagi ke opsi `html:` SweetAlert. Blade meng-escape HTML, tetapi entitas seperti `&#039;` tetap di-decode oleh browser sebelum JavaScript dijalankan, sehingga penyerang dapat keluar dari string. Opsi `html:` juga membuat tag HTML di nama siswa dieksekusi.

Contoh payload yang dapat tersimpan di nama siswa:

```text
');alert(document.domain);//
<img src=x onerror=alert(document.domain)>
```

**Dampak:**

- JavaScript berjalan di browser admin.
- Penyerang dapat membaca isi halaman, mengambil token CSRF, dan melakukan aksi atas nama admin (ubah data, hapus data, membuat akun).
- Jika admin membuka dashboard, serangan berjalan otomatis (stored XSS).

**Catatan reachability:** saat ini nama siswa diisi melalui menu admin/import. Dampaknya menjadi lebih tinggi jika ada peran lain atau proses import dari pihak ketiga yang dapat mengisi nama siswa.

**Rekomendasi:**

1. Jangan menaruh data ke dalam atribut event. Gunakan `data-*` dan event listener:

```blade
<button type="button"
        class="btn btn-warning btn-sm btn-reminder"
        data-nama="{{ $item->siswa->nama_siswa ?? '' }}">
```

```js
document.querySelectorAll('.btn-reminder').forEach((btn) => {
    btn.addEventListener('click', () => showWarning(btn.dataset.nama));
});
```

2. Gunakan `text:` alih-alih `html:` pada SweetAlert:

```js
Swal.fire({
    title: 'Ingatkan Siswa',
    text: `Anda akan mengingatkan ${nama} untuk mengisi jurnal hari ini.`,
    icon: 'warning',
    // ...
});
```

3. Jika tetap butuh HTML, escape nama dengan fungsi khusus HTML sebelum dimasukkan.

---

### S-02 — Excel formula injection pada export pengajuan

**Severity: Sedang**  
**Komponen:** `app/Exports/PengajuanExport.php` (field `pilihan_1`, `pilihan_2`, `alamat`, `pekerjaan_orang_tua`, `catatan_admin`, dll.)

**Bukti:**

Data pengajuan ditulis apa adanya ke cell Excel. Pengujian lokal menghasilkan file dengan formula di dalam `xl/worksheets/sheet1.xml`:

```xml
<c r="F2"><f>1+1</f></c>
<c r="L2"><f>HYPERLINK(&quot;http://evil.example&quot;,&quot;klik&quot;)</f></c>
```

Payload yang diuji:

```text
=1+1
=HYPERLINK("http://evil.example","klik")
@SUM(1,1)
+cmd|' /C calc'!A0
```

**Dampak:**

- Saat admin membuka file export di Excel/LibreOffice, formula dijalankan.
- Dapat digunakan untuk menampilkan link palsu, memancing admin mengklik tautan berbahaya, atau pada konfigurasi tertentu memicu eksekusi perintah (DDE).
- Data yang diekspor berasal dari input siswa, sehingga siswa dapat menyisipkan payload ke kolom pengajuan.

**Rekomendasi:**

1. Sanitasi nilai sebelum ditulis ke Excel. Awali dengan apostrof jika diawali karakter berbahaya:

```php
private function safeExcel(?string $value): string
{
    $value = (string) ($value ?? '-');

    // Cegah formula injection
    if (preg_match('/^[=+\-@\t\r]/', $value)) {
        return "'" . $value;
    }

    return $value;
}
```

2. Terapkan pada semua field teks bebas di `collection()`.
3. Alternatif yang lebih ketat: gunakan custom value binder yang menulis semua nilai sebagai string eksplisit (`DataType::TYPE_STRING`), bukan formula.
4. Pastikan `SimpleXlsx` tetap dipakai untuk export lain karena sudah menulis `inlineStr` (aman dari formula injection).

---

### S-03 — Registrasi publik terbuka, auto-login, tanpa verifikasi email

**Severity: Sedang**  
**Komponen:** `routes/auth.php:15-18`, `app/Http/Controllers/Auth/RegisteredUserController.php:29-46`, migrasi `users` (default role `siswa`)

**Bukti:**

```php
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);
```

```php
$user = User::create([...]);
event(new Registered($user));
Auth::login($user);
return redirect(route('dashboard', absolute: false));
```

Tidak ada verifikasi email, approval admin, atau pembatasan domain email sekolah. Role default user adalah `siswa`.

**Dampak:**

- Siapa pun dapat membuat akun tanpa verifikasi.
- Akun langsung login dan dapat mengakses area siswa (meskipun belum punya data siswa).
- Dapat dipakai untuk spam akun, mengacaukan data, atau mencoba fitur yang tersedia untuk role siswa.
- Tidak ada bukti kepemilikan email, sehingga sulit diaudit.

**Rekomendasi:**

1. Jika aplikasi hanya untuk siswa/guru/industri yang didata sekolah, **nonaktifkan registrasi publik** (hapus/comment route atau batasi dengan middleware admin).
2. Jika registrasi tetap diperlukan:
   - aktifkan `MustVerifyEmail` dan middleware `verified`;
   - wajibkan approval admin sebelum akun aktif;
   - batasi domain email (mis. hanya email sekolah) atau gunakan undangan;
   - tambahkan rate limit pada endpoint registrasi.
3. Pastikan akun tanpa data `siswa` tidak bisa mengakses fitur apa pun selain halaman profil.

---

### S-04 — Kebijakan password lemah

**Severity: Sedang**  
**Komponen:** `app/Http/Requests/GuruRequest.php`, `SiswaRequest.php`, `IndustriRequest.php`, `RegisteredUserController.php`, `PasswordController.php`, `NewPasswordController.php`

**Bukti:**

- Form admin memakai `min:8` tanpa syarat kompleksitas.
- `Password::defaults()` pada Laravel 13 tanpa konfigurasi khusus hanya berarti `min(8)`.
- Tidak ada pengecekan password bocor (`uncompromised()`), tidak ada 2FA untuk admin.
- Password `password` (8 karakter) lolos validasi.

**Dampak:**

- Password mudah ditebak/di-stuffing.
- Risiko lebih tinggi jika digabung dengan password default import (T-01).
- Akun admin tidak memiliki lapisan kedua jika password bocor.

**Rekomendasi:**

1. Terapkan kebijakan password yang lebih kuat secara global:

```php
// app/Providers/AppServiceProvider.php — boot()
Password::defaults(function () {
    return Password::min(12)
        ->letters()
        ->mixedCase()
        ->numbers()
        ->symbols()
        ->uncompromised();
});
```

2. Gunakan aturan yang sama di form admin, registrasi, reset password, dan import.
3. Aktifkan 2FA (TOTP) untuk akun admin, atau minimal verifikasi ulang password untuk aksi sensitif.
4. Paksa ganti password untuk akun yang masih memakai password default.

---

### R-01 — CSP terlalu minimal

**Severity: Rendah**  
**Komponen:** `app/Http/Middleware/SecurityHeaders.php:35`

**Bukti:**

```php
'Content-Security-Policy' => "frame-ancestors 'self'; object-src 'none'; base-uri 'self'"
```

Tidak ada `script-src`, sehingga CSP tidak mengurangi dampak XSS.

**Rekomendasi:**

1. Mulai dengan `Content-Security-Policy-Report-Only` untuk memetakan script yang dipakai.
2. Setelah bersih, terapkan kebijakan seperti:

```text
default-src 'self';
script-src 'self' 'nonce-...' https://cdn.jsdelivr.net;
style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;
img-src 'self' data:;
font-src 'self' https://cdnjs.cloudflare.com;
frame-ancestors 'self';
object-src 'none';
base-uri 'self';
```

3. Pindahkan inline script/`onclick` ke file JS agar bisa menghapus `'unsafe-inline'`.

---

### R-02 — `ForceHttps` mempercayai header `X-Forwarded-Proto`

**Severity: Rendah**  
**Komponen:** `app/Http/Middleware/ForceHttps.php:54-60`

**Bukti:**

```php
$proto = $request->header('X-Forwarded-Proto');
return is_string($proto) && str_contains(strtolower($proto), 'https');
```

Siapa pun yang bisa mengirim request langsung ke aplikasi dapat memalsukan header ini.

**Dampak:**

- Aplikasi mengira koneksi sudah HTTPS sehingga tidak melakukan redirect.
- Flag cookie `secure` dapat diatur berdasarkan asumsi yang salah.
- Risiko lebih rendah jika aplikasi hanya dapat diakses melalui reverse proxy Hostinger.

**Rekomendasi:**

1. Jangan mempercayai header dari klien. Gunakan konfigurasi trusted proxies Laravel:

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: [
        'IP_PROXY_HOSTINGER_1',
        'IP_PROXY_HOSTINGER_2',
    ]);
})
```

2. Setelah itu gunakan `$request->secure()` tanpa membaca header mentah.
3. Pastikan origin aplikasi tidak bisa diakses langsung selain lewat proxy.

---

### R-03 — Link sosial `target="_blank"` tanpa `rel` dan tanpa validasi URL

**Severity: Rendah**  
**Komponen:** `resources/views/landing.blade.php:274-289`, `app/Http/Controllers/Admin/SettingController.php:17-19`

**Bukti:**

```blade
<a href="{{ setting('footer_instagram') }}" target="_blank" class="social-link">
```

Validasi hanya `nullable|string|max:255`, bukan URL, dan tidak ada `rel="noopener noreferrer"`.

**Dampak:**

- Reverse tabnabbing: halaman tujuan dapat mengubah `window.opener`.
- Nilai seperti `javascript:` dapat tersimpan jika admin salah memasukkan URL.
- Dampak terbatas karena hanya admin yang dapat mengubah setting.

**Rekomendasi:**

1. Tambahkan `rel="noopener noreferrer"` pada semua link `target="_blank"`.
2. Validasi URL dan skema:

```php
'footer_instagram' => ['nullable', 'url:http,https', 'max:255'],
```

3. Tolak skema selain `http`/`https`.

---

### R-04 — Perbedaan waktu respons login NIS

**Severity: Rendah**  
**Komponen:** `app/Http/Controllers/LoginSiswaController.php:37-46`

**Bukti:**

```php
$siswa = \App\Models\Siswa::where('nis', $request->nis)->first();
$user = $siswa?->user;

if (!$user || !Hash::check($request->password, $user->password)) {
```

Jika NIS tidak ditemukan, pengecekan bcrypt dilewati sehingga respons lebih cepat. Pesan error memang sudah diseragamkan, tetapi perbedaan waktu masih dapat dipakai untuk menebak NIS yang valid.

**Dampak:**

- Enumerasi NIS siswa.
- Rate limit 5 percobaan/60 detik memperlambat, tetapi tidak menghilangkan sinyal timing.

**Rekomendasi:**

1. Selalu jalankan `Hash::check` dengan dummy hash saat user tidak ditemukan:

```php
$dummyHash = '$2y$12$........................................................';
$hash = $user?->password ?? $dummyHash;
$valid = Hash::check($request->password, $hash);

if (!$user || !$valid) {
    // ...
}
```

2. Pertahankan pesan error yang seragam dan rate limit yang sudah ada.

---

### R-05 — Route `home` tidak terdefinisi

**Severity: Rendah (bug fungsional)**  
**Komponen:** `app/Http/Controllers/Siswa/JurnalController.php:19` dan `:56`

**Bukti:**

```php
return redirect()->route('home')->with('error', 'Data siswa tidak ditemukan.');
```

Tidak ada route bernama `home` di `routes/web.php`. Jika siswa login tetapi data `siswa` tidak ditemukan, aplikasi akan melempar `RouteNotFoundException` (HTTP 500).

**Rekomendasi:**

- Ganti dengan route yang ada, misalnya `route('dashboard')`, atau definisikan route `home` jika memang dibutuhkan.
- Tambahkan test untuk skenario akun siswa tanpa relasi `siswa`.

---

### R-06 — Port 21, 2083, dan 2087 terbuka ke internet

**Severity: Rendah**  
**Komponen:** Infrastruktur hosting

**Bukti:** Port FTP (21) dan cPanel (2083/2087) merespons dari internet.

**Dampak:**

- Memperluas attack surface (brute force FTP/cPanel).
- Risiko meningkat jika password lemah atau 2FA tidak aktif.

**Rekomendasi:**

1. Jika tidak dipakai, tutup port tersebut atau batasi ke IP pengelola.
2. Aktifkan 2FA pada akun hPanel/cPanel.
3. Gunakan SFTP, bukan FTP biasa.
4. Pantau log login yang gagal.

---

### I-01 — Kemungkinan selisih versi produksi vs source

**Severity: Info**  
Beberapa kontrol di source (pengecekan kepemilikan, `role` tidak fillable, perbaikan `JurnalRequest`) tampak sudah diperbarui. Perlu dipastikan commit yang di-deploy sama dengan yang diaudit.

**Rekomendasi:** catat commit/hash deploy, bandingkan dengan repository, dan deploy ulang setelah perbaikan.

---

### I-02 — Kredensial produksi sempat dibagikan di chat

**Severity: Info / operasional**  
Password produksi dan potongan hash sempat dibagikan. Anggap kredensial tersebut sudah bocor.

**Rekomendasi:**

1. Segera ganti password akun yang bersangkutan.
2. Jangan pernah membagikan password/hash di chat, tiket, atau dokumen.
3. Gunakan password manager dan aktifkan 2FA untuk hPanel serta akun admin aplikasi.
4. Audit log login setelah rotasi.

---

### I-03 — Parsing file import dari pihak ketiga

**Severity: Info**  
Import menerima `xlsx`, `xls`, dan `csv` hingga 10 MB. File dari pihak luar berpotensi berisi konten berbahaya atau memicu bug parser.

**Rekomendasi:**

- Batasi format yang diterima (utamakan `xlsx`/`csv`).
- Selalu perbarui `maatwebsite/excel` dan `phpoffice/phpspreadsheet`.
- Simpan file import di luar web root dan hapus setelah diproses.
- Batasi ukuran dan jumlah baris.

---

## 5. Kontrol yang sudah baik

Hal-hal berikut sudah benar dan perlu dipertahankan:

- **SQL injection login:** `Auth::attempt()` memakai parameter binding, input divalidasi sebagai email, dan password di-hash bcrypt. Payload klasik `' OR '1'='1` tidak berhasil.
- **Rate limit login:** 5 percobaan per IP+identitas, dengan pesan tunggu yang jelas.
- **Otorisasi role:** middleware `role` memisahkan admin/guru/siswa/industri dan mengembalikan 403 untuk akses lintas role.
- **IDOR:** modul jurnal, nilai, kesimpulan, monitoring, penilaian industri, dan laporan sudah mengecek kepemilikan data (`siswa_id`, `guru_id`, `industri_id`).
- **Mass assignment:** `role` sengaja tidak masuk `$fillable`; role di-set eksplisit lewat `forceFill()`.
- **Upload gambar jurnal:** validasi `image|mimes:jpeg,png,jpg,gif|max:2048`.
- **Security header:** HSTS, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`, dan `Cross-Origin-Opener-Policy` sudah dikirim.
- **Produksi:** HTTPS dipaksa, sertifikat valid, `.env`/`.git`/log tidak dapat diakses, debug tidak bocor, TRACE mati.
- **Export `SimpleXlsx`:** menulis string sebagai `inlineStr`, sehingga aman dari formula injection (berbeda dengan `PengajuanExport`).
- **Pesan login:** pesan gagal diseragamkan untuk mengurangi enumerasi.

---

## 6. Rencana perbaikan prioritas

| Prioritas | Tenggat | Tindakan |
|---|---|---|
| P0 | 0–24 jam | Tutup port 3306 dari internet, rotasi password database, verifikasi dari luar |
| P0 | 0–24 jam | Rotasi password akun produksi yang sempat dibagikan |
| P1 | 1–7 hari | Hilangkan password default import; reset akun hasil import |
| P1 | 1–7 hari | Perbaiki stored XSS dashboard admin |
| P1 | 1–7 hari | Sanitasi export Excel (`PengajuanExport`) |
| P2 | 1–4 minggu | Nonaktifkan/amankan registrasi publik, tambahkan verifikasi email |
| P2 | 1–4 minggu | Terapkan kebijakan password kuat + 2FA admin |
| P2 | 1–4 minggu | Perketat CSP, trusted proxies, validasi URL, perbaiki route `home` |
| P3 | Berkelanjutan | Batasi port FTP/cPanel, perbarui dependency, audit log berkala |

---

## 7. Lampiran — reproduksi lokal

Pengujian dilakukan di mesin lokal, bukan produksi.

**Uji formula injection:**

```bash
php /tmp/opencode/test_formula.php
unzip -p /tmp/opencode/pengajuan_test.xlsx xl/worksheets/sheet1.xml | grep -oE '<f>[^<]*</f>'
```

Hasil yang diharapkan (sebelum diperbaiki):

```text
<f>1+1</f>
<f>HYPERLINK(&quot;http://evil.example&quot;,&quot;klik&quot;)</f>
```

**Uji login lokal (rate limit dan SQLi):**

- 5 percobaan gagal berturut-turut → pesan tunggu aktif.
- Payload `admin@gmail.com' OR '1'='1` dan password `' OR '1'='1` → gagal.
- Password benar → redirect ke `/dashboard`.

---

## 8. Penutup

Temuan paling mendesak adalah **port database 3306 yang terbuka ke internet**. Perbaikan ini berada di level hosting dan harus dikoordinasikan dengan Hostinger. Temuan aplikasi lainnya dapat diperbaiki di source code dan di-deploy setelah melalui pengujian lokal/staging.

Dokumen ini disusun untuk kepentingan perbaikan oleh sekolah. Pengujian aktif terhadap produksi tidak dilakukan; verifikasi akhir sebaiknya dilakukan di staging atau dengan persetujuan tertulis dan koordinasi dengan penyedia hosting.

---

**Disusun oleh:** _[nama penguji]_  
**Tanggal:** 6 November 2026
