<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $diagnosa = $_POST['diagnosa'];
    $deskripsi = $_POST['deskripsi'];
    $solusi = $_POST['solusi'];

    if (empty($kode) || empty($diagnosa)) {
        setFlash('error_tambah', 'danger', 'Kode & nama penyakit wajib di isi.');
    } else if (strlen($kode) > 3) {
        setFlash('error_tambah', 'danger', 'Panjang kode max. 3.');
    } else {
        $sql = "SELECT * FROM kasus_penyakit WHERE kode='$kode'";
        $result = mysqli_query($conn, $sql);
        $penyakit = mysqli_fetch_row($result);
        if ($penyakit) {
            mysqli_query($conn, "UPDATE `kasus_penyakit` SET kode='$kode', diagnosa='$diagnosa', deskripsi='$deskripsi', solusi='$solusi' WHERE kode='$kode'");
            setFlash('alert', 'success', 'Data penyakit berhasil diedit');
        } else {
            mysqli_query($conn, "INSERT INTO `kasus_penyakit` VALUES (null, '$kode', '$diagnosa','$deskripsi','$solusi')");
            setFlash('alert', 'success', 'Data penyakit berhasil disimpan');

            $_POST = []; // reset input
        }
    }
}


if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM kasus_penyakit WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data penyakit berhasil dihapus');
}


$sql = "SELECT * FROM kasus_penyakit";
$result = mysqli_query($conn, $sql);
$penyakits = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Daftar Data Penyakit</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode</th>
                                    <th scope="col">Nama Penyakit</th>
                                    <th scope="col">Deskripsi Penyakit</th>
                                    <th scope="col">Solusi Penyakit</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($penyakits as $penyakit) : ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td>
                                            <span class="fw-bold"><?= $penyakit['kode']; ?></span>
                                        </td>
                                        <td><?= $penyakit['diagnosa']; ?></td>
                                        <td><?= $penyakit['deskripsi']; ?></td>
                                        <td><?= $penyakit['solusi']; ?></td>
                                        <td>
                                            <a href="#" onclick="document.getElementById('kode').value = '<?= $penyakit['kode']; ?>'; document.getElementById('diagnosa').value = '<?= $penyakit['diagnosa']; ?>'; document.getElementById('deskripsi').value = '<?= $penyakit['deskripsi']; ?>'; document.getElementById('solusi').value = '<?= $penyakit['solusi']; ?>';" class="badge text-bg-primary">edit</a>
                                            <form action="" method="post" class="d-inline-block">
                                                <input type="hidden" name="id" value="<?= $penyakit['id']; ?>">
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
                            <input type="text" class="form-control" id="diagnosa" name="diagnosa" placeholder="-" value="<?= $_POST['diagnosa'] ?? ''; ?>">
                            <label for="diagnosa">Nama Penyakit</label>
                        </div>
                        <small class="text-muted mb-3 d-block mt-2">Masukan ID yang sudah ada untuk mengedit.</small>
                        <div class="form-floating mb-3">
                            <textarea name="deskripsi" class="form-control" id="deskripsi" placeholder="-"><?= $_POST['deskripsi'] ?? ''; ?></textarea>

                            <label for="diagnosa">Deskripsi Penyakit</label>
                        </div>
                        <small class="text-muted mb-3 d-block mt-2">Masukan ID yang sudah ada untuk mengedit.</small>
                        <div class="form-floating mb-3">
                            <textarea name="solusi" class="form-control" id="solusi" placeholder="-"><?= $_POST['solusi'] ?? ''; ?></textarea>
                            <label for="diagnosa">Solusi Penyakit</label>
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