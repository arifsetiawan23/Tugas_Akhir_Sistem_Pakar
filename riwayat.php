<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();



if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM riwayat_konsultasi WHERE id_riwayat='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data Riwayat Konsultasi berhasil dihapus');
}


$sql = "SELECT * FROM riwayat_konsultasi";
$result = mysqli_query($conn, $sql);
$riwayat = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Daftar Riwayat Konsultasi</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Umur</th>
                                    <th scope="col">Jenis Kelamin</th>
                                    <th scope="col">Hasil Diagnosa</th>
                                    <th scope="col">Solusi</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($riwayat as $item) : ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td>
                                            <span class="fw-bold"><?= date('d F Y H:i', strtotime($item['tgl_konsultasi'])) ?></span>
                                        </td>
                                        <td><?= $item['nama']; ?></td>
                                        <td><?= $item['umur']; ?></td>
                                        <td><?= $item['jns_kelamin'] == 'L' ? "Laki-Laki" : "Perempuan"; ?></td>
                                        <td><?php $sql = "SELECT * FROM kasus_penyakit WHERE kode='" . $item['kode'] . "' LIMIT 1";
                                            $result = mysqli_query($conn, $sql);

                                            $penyakit = mysqli_fetch_all($result, MYSQLI_ASSOC);

                                            echo '<span class="fw-bold">' . $penyakit[0]['kode'] . '</span> - ' . $penyakit[0]['diagnosa']; ?>
                                            <hr /> <?= $penyakit[0]['deskripsi'] ?>
                                        </td>

                                        <td><?= $penyakit[0]['solusi'] ?></td>
                                        <td>
                                            <form action="" method="post" class="d-inline-block">
                                                <input type="hidden" name="id" value="<?= $item['id_riwayat']; ?>">
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

    </div>
</div>

<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>