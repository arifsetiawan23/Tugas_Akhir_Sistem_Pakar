<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama_gejala = $_POST['nama_gejala'];

    if (empty($kode) || empty($nama_gejala)) {
        setFlash('error_tambah', 'danger', 'Kode & nama gejala wajib di isi.');
    } else if (strlen($kode) > 3) {
        setFlash('error_tambah', 'danger', 'Panjang kode max. 3.');
    } else {
        $sql = "SELECT * FROM gejala WHERE kode='$kode'";
        $result = mysqli_query($conn, $sql);
        $gejala = mysqli_fetch_row($result);
        if ($gejala) {
            mysqli_query($conn, "UPDATE `gejala` SET kode='$kode', nama_gejala='$nama_gejala' WHERE kode='$kode'");
            setFlash('alert', 'success', 'Data gejala berhasil diedit');
        } else {
            mysqli_query($conn, "INSERT INTO `gejala` VALUES (null, '$kode', '$nama_gejala')");
            setFlash('alert', 'success', 'Data gejala berhasil disimpan');

            $_POST = []; // reset input
        }
    }
}


if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM gejala WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data gejala berhasil dihapus');
}


$sql = "SELECT * FROM gejala";
$result = mysqli_query($conn, $sql);
$gejala = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Daftar Data Gejala</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode</th>
                                <th scope="col">Nama Gejala</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($gejala as $gejala) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td>
                                        <span class="fw-bold"><?= $gejala['kode']; ?></span>
                                    </td>
                                    <td><?= $gejala['nama_gejala']; ?></td>
                                    <td>
                                        <a href="#" onclick="document.getElementById('kode').value = '<?= $gejala['kode']; ?>'; document.getElementById('nama_gejala').value = '<?= $gejala['nama_gejala']; ?>';" class="badge text-bg-primary">edit</a>
                                        <form action="" method="post" class="d-inline-block">
                                            <input type="hidden" name="id" value="<?= $gejala['id']; ?>">
                                            <button name="hapus" class="badge text-bg-danger border-0">hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Form Tambah & Edit Data
                </div>
                <div class="card-body">
                    <?php if ($error = getFlash('error_tambah')) : ?>
                        <div class="alert alert-<?= $error['type']; ?> " role="alert">
                            <div><?= $error['message']; ?></div>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="kode" name="kode" placeholder="-" value="<?= $_POST['kode'] ?? ''; ?>" maxlength="3">
                            <label for="kode">Kode</label>
                        </div>
                        <small class="text-muted mb-3 d-block mt-2">Masukan ID yang sudah ada untuk mengedit.</small>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nama_gejala" name="nama_gejala" placeholder="-" value="<?= $_POST['nama_gejala'] ?? ''; ?>">
                            <label for="nama_gejala">nama Gejala</label>
                        </div>
                        <button name="tambah" class="btn btn-primary d-block mt-3 w-100 py-3 d-block fw-bold">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>