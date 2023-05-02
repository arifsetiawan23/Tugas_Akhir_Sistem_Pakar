<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $slug = str_replace(' ', '-', $judul);
    $id = $_POST['id'];

    if (empty($judul) || empty($deskripsi)) {
        setFlash('error_tambah', 'danger', 'Judul & deskripsi wajib di isi.');
    } else {

        if (!empty($id)) {

            if (!empty($_FILES['file']['name'])) {

                $ekstensi_diperbolehkan    = array('png', 'jpg', 'jpeg');
                $nama = $_FILES['file']['name'];
                $x = explode('.', $nama);
                $ekstensi = strtolower(end($x));
                $ukuran    = $_FILES['file']['size'];
                $file_tmp = $_FILES['file']['tmp_name'];

                $newfilename = $slug . '.' . $ekstensi;

                if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                    if ($ukuran < 1044070) {

                        move_uploaded_file($file_tmp, 'file/' . $newfilename);
                        $query =   mysqli_query($conn, "UPDATE artikel SET judul='$judul',  deskripsi='$deskripsi', slug='$slug', gambar='$newfilename' WHERE id_artikel='$id'");
                        if ($query) {
                            setFlash('alert', 'success', 'Data berhasil disimpan');
                        } else {
                            setFlash('alert', 'danger', 'GAGAL MENGUPLOAD GAMBAR');
                        }
                    } else {
                        setFlash('alert', 'danger', 'UKURAN FILE TERLALU BESAR');
                    }
                } else {
                    setFlash('alert', 'danger', 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN');
                }





                $_POST = []; // reset input

            } else {

                mysqli_query($conn, "UPDATE artikel SET judul='$judul',  deskripsi='$deskripsi', slug='$slug' WHERE id_artikel='$id'");
                setFlash('alert', 'success', 'Data Artikel berhasil diedit');
                $_POST = [];
            }
        } else {

            $ekstensi_diperbolehkan    = array('png', 'jpg', 'jpeg');
            $nama = $_FILES['file']['name'];
            $x = explode('.', $nama);
            $ekstensi = strtolower(end($x));
            $ukuran    = $_FILES['file']['size'];
            $file_tmp = $_FILES['file']['tmp_name'];

            $newfilename = $slug . '.' . $ekstensi;

            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                if ($ukuran < 1044070) {

                    move_uploaded_file($file_tmp, 'file/' . $newfilename);
                    $query = mysqli_query($conn, "INSERT INTO artikel VALUES (null, '$judul', '$slug','$deskripsi','$newfilename')");
                    if ($query) {
                        setFlash('alert', 'success', 'Data berhasil disimpan');
                    } else {
                        setFlash('alert', 'danger', 'GAGAL MENGUPLOAD GAMBAR');
                    }
                } else {
                    setFlash('alert', 'danger', 'UKURAN FILE TERLALU BESAR');
                }
            } else {
                setFlash('alert', 'danger', 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN');
            }





            $_POST = []; // reset input
        }
    }
}


if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM artikel WHERE id_artikel='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data Artikel berhasil dihapus');
}


$sql = "SELECT * FROM artikel order by id_artikel desc";
$result = mysqli_query($conn, $sql);
$penyakits = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Daftar Data Artikel</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Gambar</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($penyakits as $penyakit) : ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td>
                                            <span class="fw-bold"><?= $penyakit['judul']; ?></span>
                                        </td>
                                        <td><?= $penyakit['deskripsi']; ?></td>
                                        <td><img class="logo" src="file/<?= $penyakit['gambar']; ?>" style="width:100px; height:80px"></td>
                                        <td>
                                            <a href="#" onclick="document.getElementById('idartikel').value = '<?= $penyakit['id_artikel']; ?>'; document.getElementById('judul').value = '<?= $penyakit['judul']; ?>'; document.getElementById('deskripsi').value = '<?= $penyakit['deskripsi']; ?>';" class="badge text-bg-primary">edit</a>
                                            <form action="" method="post" class="d-inline-block">
                                                <input type="hidden" name="id" value="<?= $penyakit['id_artikel']; ?>">
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
                    <form action="" method="POST" enctype="multipart/form-data">

                        <input type="hidden" class="form-control" id="idartikel" name="id" value="<?= $_POST['id_artikel'] ?? ''; ?>">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="-" value="<?= $_POST['judul'] ?? ''; ?>">
                            <label for="kode">Judul</label>
                        </div>
                        <small class="text-muted mb-3 d-block mt-2">Masukan Judul yang sudah ada untuk mengedit.</small>


                        <div class="form-floating mb-3">
                            <textarea name="deskripsi" class="form-control" id="deskripsi" placeholder="-"><?= $_POST['deskripsi'] ?? ''; ?></textarea>

                            <label for="diagnosa">Deskripsi Artikel</label>
                        </div>
                        <small class="text-muted mb-3 d-block mt-2">Masukan Gambar Artikel (*png, *jpg, *jpeg)</small>
                        <div class="form-floating mb-3">
                            <input type="file" name="file" class="form-control" id="file">

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