<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="section">
    <div class="card p-4">
        <h5 class="card-title">Account Settings</h5>
        <p class="text-muted">Halaman pengaturan akun sederhana untuk menampilkan status login Anda.</p>
        <ul class="list-group">
            <li class="list-group-item"><strong>Username:</strong> <?= esc(session()->get('username')) ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?= esc(session()->get('email')) ?></li>
            <li class="list-group-item"><strong>Role:</strong> <?= esc(session()->get('role')) ?></li>
        </ul>
    </div>
</section>

<?= $this->endSection() ?>
