<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $gejala = $_POST['gejala'] ?? [];
    $kodeDiagnosa = $_POST['kode_diagnosa'];
    if (empty($kode) || count($gejala) <= 0 || empty($kodeDiagnosa)) {
        setFlash('error_tambah', 'danger', 'Kode wajib di isi, dan gejala setidaknya dipilih salah satu.');
    } else if (strlen($kode) > 3) {
        setFlash('error_tambah', 'danger', 'Panjang kode max. 3.');
    } else {
        $sql = "SELECT * FROM keputusan WHERE kode='$kode'";
        $result = mysqli_query($conn, $sql);
        $keputusan = mysqli_fetch_row($result);

        $gejala = join('AND', $gejala);

        if ($keputusan) {
            mysqli_query($conn, "UPDATE `keputusan` SET kode='$kode', jika='$gejala', kode_diagnosa='$kodeDiagnosa' WHERE kode='$kode'");
            setFlash('alert', 'success', 'Data keputusan berhasil diedit');
        } else {
            mysqli_query($conn, "INSERT INTO `keputusan` VALUES (null, '$kode', '$gejala', '$kodeDiagnosa')");
            setFlash('alert', 'success', 'Data keputusan berhasil disimpan');
        }

        $_POST = []; // reset input
    }
}


if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM keputusan WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data keputusan berhasil dihapus');
}


$sql = "SELECT * FROM keputusan";
$result = mysqli_query($conn, $sql);
$keputusan = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = "SELECT * FROM gejala";
$result = mysqli_query($conn, $sql);
$gejala = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = "SELECT * FROM kasus_penyakit";
$result = mysqli_query($conn, $sql);
$penyakit = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Daftar Data Keputusan / Aturan</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode</th>
                                    <th scope="col">Maka</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($keputusan as $k) : ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td>
                                            <span class="fw-bold"><?= $k['kode']; ?></span>
                                        </td>
                                        <td>
                                            <?= $k['kode_diagnosa']; ?>
                                        </td>
                                        <td>
                                            <a href="#" onclick="handleEditFillForm('<?= $k['kode']; ?>', '<?= $k['jika']; ?>', '<?= $k['kode_diagnosa'] ?>')" class="badge text-bg-primary">edit</a>
                                            <form action="" method="post" class="d-inline-block">
                                                <input type="hidden" name="id" value="<?= $k['id']; ?>">
                                                <button name="hapus" class="badge text-bg-danger border-0">hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">
                                            Jika:
                                        </td>
                                        <td colspan="3">
                                            <?= join('AND', array_map(
                                                function ($data) {
                                                    return "<span class=\"fw-bold\">$data</span>";
                                                },
                                                explode('AND', $k['jika'])
                                            )); ?>
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
                        <hr>
                        <div class="mb-3" style="font-size: 14px;">
                            <label for="" class="form-label">Gejala</label>
                            <?php foreach ($gejala as $g) : ?>
                                <div class="form-check">
                                    <input class="form-check-input jika-gejala-checkbox" type="checkbox" value="<?= $g['kode']; ?>" id="flexCheckDefault<?= $g['id'] ?>" data-kode="<?= $g['kode']; ?>" name="gejala[]">
                                    <label class="form-check-label" for="flexCheckDefault<?= $g['id'] ?>">
                                        <?= $g['nama_gejala']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="kode_diagnosa">
                                <?php foreach ($penyakit as $p) : ?>
                                    <option value="<?= $p['kode']; ?>" class="kode-diagnosa-options"><?= $p['diagnosa']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="floatingSelect">Pilih Penyakit</label>
                        </div>
                        <button name="tambah" class="btn btn-primary d-block mt-3 w-100 py-3 d-block fw-bold">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function handleEditFillForm(kode, jika, kodeDiagnosa) {
        document.getElementById('kode').value = kode;
        const gejalaKodes = jika.split('AND');
        const jikaGejalaCheckboxs = document.querySelectorAll('.jika-gejala-checkbox');
        const kodeDiagnosaOptions = document.querySelectorAll('.kode-diagnosa-options');
        jikaGejalaCheckboxs.forEach(el => {
            el.checked = false;
            if (gejalaKodes.find(g => g == el.dataset.kode)) {
                el.checked = true;
            }
        });
        kodeDiagnosaOptions.forEach(el => {
            el.selected = false;
            if (kodeDiagnosa == el.value) {
                el.selected = true;
            }
        });
    }
</script>
<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>