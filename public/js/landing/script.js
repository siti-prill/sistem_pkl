// ============ DETAIL FITUR (MODAL) ============
const features = {
    siswa: {
        icon: 'fa-user-graduate',
        color: '#4f46e5',
        bg: 'rgba(79,70,229,0.1)',
        title: 'Manajemen Siswa',
        desc: 'Kelola seluruh data siswa yang melaksanakan PKL secara terpusat dalam satu dashboard admin.',
        points: [
            'Pendataan lengkap data siswa (NIS, nama, jurusan, kontak, dan alamat)',
            'Penempatan PKL ke industri mitra sesuai kompetensi keahlian',
            'Pemantauan jurnal harian siswa selama periode PKL berlangsung',
            'Admin mengelola seluruh data tanpa kendala dari satu tempat'
        ]
    },
    industri: {
        icon: 'fa-building',
        color: '#198754',
        bg: 'rgba(25,135,84,0.1)',
        title: 'Mitra Industri',
        desc: 'Kelola daftar perusahaan mitra PKL beserta kuota dan contact person secara terintegrasi.',
        points: [
            'Data lengkap perusahaan mitra PKL beserta alamat',
            'Informasi kuota penerimaan siswa per perusahaan',
            'Contact person perusahaan untuk memudahkan koordinasi',
            'Pencatatan terintegrasi antara siswa, jurusan, dan industri'
        ]
    },
    monitoring: {
        icon: 'fa-chart-line',
        color: '#ffc107',
        bg: 'rgba(255,193,7,0.12)',
        title: 'Monitoring & Laporan',
        desc: 'Pantau aktivitas siswa selama PKL dan cetak laporan jurnal serta nilai dalam format PDF.',
        points: [
            'Guru memantau jurnal harian siswa secara real-time',
            'Pemberian nilai PKL melalui modul penilaian yang mudah',
            'Laporan jurnal dan nilai siap dicetak dalam format PDF',
            'Rekap perkembangan siswa selama periode PKL berjalan'
        ]
    },
    bimbingan: {
        icon: 'fa-comments',
        color: '#0dcaf0',
        bg: 'rgba(13,202,240,0.12)',
        title: 'Bimbingan Online',
        desc: 'Guru dapat memberikan komentar dan umpan balik langsung pada setiap jurnal harian siswa.',
        points: [
            'Komentar langsung pada setiap jurnal harian siswa',
            'Umpan balik membantu siswa memperbaiki catatan kegiatan PKL',
            'Komunikasi dua arah antara guru dan siswa dalam satu platform',
            'Kemajuan bimbingan terdokumentasi secara otomatis'
        ]
    },
    export: {
        icon: 'fa-file-pdf',
        color: '#dc3545',
        bg: 'rgba(220,53,69,0.1)',
        title: 'Export PDF',
        desc: 'Cetak laporan jurnal dan nilai PKL dalam format PDF yang rapi dan siap cetak.',
        points: [
            'Cetak laporan jurnal harian siswa dalam format PDF',
            'Cetak laporan nilai PKL yang siap diserahkan',
            'Format rapi dan siap cetak untuk keperluan administrasi',
            'Dapat diunduh kapan saja melalui menu laporan'
        ]
    },
    pengajuan: {
        icon: 'fa-paper-plane',
        color: '#0d6efd',
        bg: 'rgba(13,110,253,0.1)',
        title: 'Pengajuan Tempat PKL',
        desc: 'Siswa dapat mengajukan pilihan tempat PKL yang akan diverifikasi dan disetujui oleh admin.',
        points: [
            'Login khusus dengan NIS untuk masuk ke halaman pengajuan',
            'Siswa memilih tempat PKL dari daftar industri mitra',
            'Admin memverifikasi dan menyetujui pengajuan siswa',
            'Status pengajuan dapat dipantau langsung oleh siswa'
        ]
    },
    aman: {
        icon: 'fa-shield-alt',
        color: '#7c3aed',
        bg: 'rgba(124,58,237,0.1)',
        title: 'Aman & Terpercaya',
        desc: 'Sistem dibangun dengan autentikasi user dan role management yang aman untuk semua pengguna.',
        points: [
            'Autentikasi login yang aman untuk setiap pengguna',
            'Role management: Admin, Guru, Siswa, dan Industri',
            'Hak akses sesuai dengan peran masing-masing pengguna',
            'Data tersimpan dengan aman dan terlindungi'
        ]
    },
    penilaian_industri: {
        icon: 'fa-clipboard-check',
        color: '#0d9488',
        bg: 'rgba(13,148,136,0.1)',
        title: 'Penilaian Industri',
        desc: 'Industri dapat langsung menilai siswa PKL sesuai template penilaian dari sekolah.',
        points: [
            'Industri login dengan email dan password khusus',
            'Input nilai siswa sesuai aspek penilaian dari template sekolah',
            'Guru pembimbing dapat melihat hasil penilaian industri',
            'Nilai kesimpulan akhir guru untuk raport (tidak terlihat siswa)',
            'Cetak raport PDF gabungan nilai industri dan guru'
        ]
    }
};

const featureModal = document.getElementById('featureModal');
featureModal.addEventListener('show.bs.modal', function(event) {
    const key = event.relatedTarget.getAttribute('data-feature');
    const data = features[key];

    if (!data) return;

    const icon = document.getElementById('featureModalIcon');
    icon.innerHTML = '<i class="fas ' + data.icon + '"></i>';
    icon.style.color = data.color;
    icon.style.background = data.bg;

    document.getElementById('featureModalTitle').textContent = data.title;
    document.getElementById('featureModalDesc').textContent = data.desc;

    const pointsList = document.getElementById('featureModalPoints');
    pointsList.innerHTML = '';
    data.points.forEach(function(point) {
        const li = document.createElement('li');
        li.className = 'mb-2';
        li.innerHTML = '<i class="fas fa-check-circle me-2" style="color:' + data.color +
            ';"></i>' + point;
        pointsList.appendChild(li);
    });
});

// Scroll Reveal Animation
(function() {
    const elements = document.querySelectorAll(
        '.feature-card, .features-section h2, .features-section .subtitle, .footer');
    if (!elements.length) return;

    if (!('IntersectionObserver' in window)) {
        elements.forEach(function(el) {
            el.classList.add('revealed');
        });
        return;
    }

    elements.forEach(function(el) {
        el.classList.add('reveal');
    });

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px'
    });

    elements.forEach(function(el) {
        observer.observe(el);
    });
})();

// ============ POPUP FULL LAYAR ============
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('popupIklan');
    const closeBtn = document.getElementById('popupClose');
    const countdownEl = document.getElementById('countdownNumber');

    // Kalo elemen ga ada, stop
    if (!popup || !closeBtn || !countdownEl) {
        console.error('Elemen popup ga lengkap!');
        return;
    }

    if (sessionStorage.getItem('popupIklanShown') === 'true') { return; }

    let countdown = 5;
    let timer = null;

    // Tampilkan popup FULL LAYAR setelah 1 detik
    setTimeout(function() {
        popup.classList.add('show');

        sessionStorage.setItem('popupIklanShown', 'true');

        startCountdown();
    }, 1000);

    function startCountdown() {
        timer = setInterval(function() {
            countdown--;
            countdownEl.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(timer);
                timer = null;
                countdownEl.textContent = '0';

                // AKTIFIN TOMBOL CLOSE
                closeBtn.classList.add('enabled');
                console.log('✅ Tombol close aktif!');
            }
        }, 1000);
    }

    // EVENT CLOSE (kalo udah enabled)
    closeBtn.addEventListener('click', function() {
        if (this.classList.contains('enabled')) {
            popup.classList.remove('show');
            console.log('❌ Popup ditutup');
        } else {
            console.log('⏳ Tunggu 5 detik dulu!');
        }
    });

    // Klik di luar gambar (background) - TAPI karena full layar, ga bisa
    // Tapi kalo mau bisa tambahin:
    popup.addEventListener('click', function(e) {
        // Kalo yang diklik adalah overlay-nya (bukan gambar)
        if (e.target === this && closeBtn.classList.contains('enabled')) {
            popup.classList.remove('show');
            console.log('❌ Popup ditutup (klik background)');
        }
    });
});