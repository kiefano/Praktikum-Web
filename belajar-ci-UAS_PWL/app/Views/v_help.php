<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="section">
    <div class="card p-4">
        <h5 class="card-title">Need Help?</h5>
        <p class="text-muted">Jika ada masalah, pastikan server berjalan dan database sudah siap.</p>
        <ul>
            <li>Pastikan XAMPP MySQL aktif.</li>
            <li>Pastikan Anda login dengan akun admin1234 / 1234567.</li>
            <li>Jika halaman masih error, muat ulang halaman setelah server reload.</li>
        </ul>
    </div>
</section>

<?= $this->endSection() ?>
