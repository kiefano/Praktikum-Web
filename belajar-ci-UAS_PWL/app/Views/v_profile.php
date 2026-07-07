<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="section profile">
    <div class="card p-4">
        <h5 class="card-title">Profile Information</h5>
        <div class="row mb-1">
            <div class="col-lg-3 col-md-4 label text-primary ">Username</div>
            <div class="col-lg-9 col-md-8"><?= session()->get('username') ?></div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-3 col-md-4 label">Role</div>
            <div class="col-lg-9 col-md-8">
                <span class="badge bg-danger rounded text-white"><?= session()->get('role') ?></span>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-3 col-md-4 label">Email</div>
            <div class="col-lg-9 col-md-8 text-primary"><?= session()->get('email') ?></div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-3 col-md-4 label">Login Time</div>
            <div class="col-lg-9 col-md-8"><?= session()->get('loginTime')?></div>
        </div>
        <div class="row mb-1">
            <div class="col-lg-3 col-md-4 label">Status</div>
            <div class="col-lg-9 col-md-8">
                <span class="badge bg-success"> Login </span>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>