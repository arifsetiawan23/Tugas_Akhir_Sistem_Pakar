<nav class="navbar navbar-expand-md bg-body-tertiary py-4 shadow-sm border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/dashboard.php'; ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/artikel.php'; ?>">Data Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/penyakit.php'; ?>">Data Penyakit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/gejala.php'; ?>">Data Gejala</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/keputusan.php'; ?>">Data Keputusan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/riwayat.php'; ?>">Riwayat Konsultasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white py-2 px-3 ms-0 ms-md-3 btn-sm" aria-current="page" href="<?= url() . '/logout.php'; ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>