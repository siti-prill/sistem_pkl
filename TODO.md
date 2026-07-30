# ✅ Hapus Semua yang Berhubungan dengan `kelas` - SELESAI

## Files Edited

- [x]   1. Migration: hapus baris `$table->string('kelas', 20)`
- [x]   2. Model: hapus `'kelas'` dari `$fillable`
- [x]   3. Request: hapus rule & message validasi `kelas`
- [x]   4. Controller: hapus search/filter/compact/array `kelas`
- [x]   5. Factory: hapus `'kelas'` dari definition
- [x]   6. Seeder: hapus `'kelas' => 'XII'`
- [x]   7. View `create.blade.php`: hapus field select kelas
- [x]   8. View `edit.blade.php`: hapus field select kelas
- [x]   9. View `show.blade.php`: hapus tampilan kelas
- [x]   10. View `dashboard.blade.php`: hapus kolom kelas dari tabel
- [x]   11. View `guru/nilai/show.blade.php`: hapus baris kelas

## Post-Edit Steps

- [x]   12. Clear view cache
- [x]   13. Migrate fresh + seed berhasil
- [x]   14. Kolom `kelas` sudah tidak ada di tabel `siswas`
