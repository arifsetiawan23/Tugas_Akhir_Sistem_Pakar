<?php
require 'mysql.php';
require 'global.php';
require 'templates/header.php';
require 'templates/navbar.php';

$sql = "SELECT id, kode, nama_gejala FROM gejala";
$result = mysqli_query($conn, $sql);

$gejala = mysqli_fetch_all($result, MYSQLI_ASSOC);
// if (mysqli_num_rows($result) > 0) {
// // output data of each row
// while ($row = mysqli_fetch_assoc($result)) {
//     echo "id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
// }
// }

if (isset($_GET['diagnosa_ulang'])) {
    unset($_SESSION['keputusan']);
}

function check($keputusan, $gejalas, $nama, $umur, $jenis_kelamin)
{
    foreach ($keputusan as $k) {
        $jikaGejalas = explode('AND', $k['jika']);
        if (count($jikaGejalas) == count($gejalas) && count(array_diff($jikaGejalas, $gejalas)) == 0) {
            $_SESSION['keputusan'] = [
                'nama' => $nama,
                'umur' => $umur,
                'jenis_kelamin' => $jenis_kelamin,
                'data' => $k,
                'created_at' => time()
            ];
            return;
        }
    }
    unset($_SESSION['keputusan']);
    setFlash('error_diagnosa', 'danger', 'Diagnosa tidak ditemukan!');
}

if (isset($_POST['cek'])) {
    $gejalas = $_POST['gejala'] ?? null;
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    if (!$gejalas) {
        setFlash('error_diagnosa', 'danger', 'Pilih setidaknya salah satu gejala yang anda rasakan!');
    } else {

        $sql = "SELECT id, kode, jika, kode_diagnosa FROM keputusan";
        $result = mysqli_query($conn, $sql);

         $keputusan = mysqli_fetch_all($result, MYSQLI_ASSOC);
         
       

        check($keputusan, $gejalas, $nama, $umur, $jenis_kelamin);
    }
}

?>

<main id="main">

    <section id="cek-kondisi-anda" class="about section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Cek Kondisi Anda</h2>
                <h3>Masukan Gejala-gejala yang Anda Rasakan</span></h3>
                <p class="fw-normal">Isi dan pilih data anda dan gejala-gejala yang anda rasakan untuk <span>mendiagnosa Kesehatan Mental anda.</p>
            </div>
        </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">

                    <div class="card text-bg-light">
                        <div class="card-body p-5">
                            <form action="#cek-kondisi-anda" method="post">
                                <h3 class="mb-3">Form Cek Kondisi Tubuh Anda</h3>
                                <?php if ($error = getFlash('error_diagnosa')) : ?>
                                    <div class="alert alert-<?= $error['type']; ?> " role="alert">
                                        <div><?= $error['message']; ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($keputusan = $_SESSION['keputusan'] ?? null) : ?>
                                    <div class="alert alert-info" role="alert">
                                        <table class="table table-info table-sm">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold">Nama</td>
                                                    <td><?= $keputusan['nama']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Umur</td>
                                                    <td><?= $keputusan['umur']; ?> Tahun</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Jenis Kelamin</td>
                                                    <td><?= $keputusan['jenis_kelamin'] == 'L' ? "Laki-Laki" : "Perempuan"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Di Cek Pada</td>
                                                    <td><?= date('d F Y H:i', $keputusan['created_at']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-center" colspan="2">
                                                        Hasil Diagnosa ( Kesehatan Mental )
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <?php
                                                        $sql = "SELECT * FROM kasus_penyakit WHERE kode='" . $keputusan['data']['kode_diagnosa'] . "' LIMIT 1";
                                                        $result = mysqli_query($conn, $sql);

                                                        $penyakit = mysqli_fetch_all($result, MYSQLI_ASSOC);

                                                        echo '<span class="fw-bold">' . $penyakit[0]['kode'] . '</span> - ' . $penyakit[0]['diagnosa'];
                                                        
                                                        $kode=$penyakit[0]['kode'];
                                                        
                                                        
                                                        
                                                        mysqli_query($conn, "INSERT INTO riwayat_konsultasi  VALUES (null, NOW(), '$keputusan[nama]','$keputusan[umur]','$keputusan[jenis_kelamin]','$kode')");
                                            
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-center" colspan="2">
                                                        Deskripsi Penyakit
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td colspan="2"><?=$penyakit[0]['deskripsi']?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td class="fw-bold text-center" colspan="2">
                                                        Solusi Penyakit
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td colspan="2"><?=$penyakit[0]['solusi']?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <a href="<?= url(); ?>/cekkondisi.php?diagnosa_ulang=1" class="btn btn-outline-primary py-3 d-block w-100 fw-bold mt-4">Diagnosa Ulang</a>
                                    </div>
                                <?php else : ?>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama anda</label>
                                        <input type="text" class="form-control" id="name" name="nama" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="umur" class="form-label">Umur</label>
                                        <input type="number" min="1" class="form-control" id="umur" name="umur" required />
                                    </div>

                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <div class="d-flex align-items-center justify-content-start">
                                            <div class="form-check me-4">
                                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_1" checked value="L" required>
                                                <label class="form-check-label" for="jenis_kelamin_1">
                                                    Laki-Laki
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_2" value="P" required>
                                                <label class="form-check-label" for="jenis_kelamin_2">
                                                    Perempuan
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="gejala" class="form-label">Gejala</label>

                                        <?php foreach ($gejala as $g) : ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="<?= $g['kode']; ?>" id="flexCheckDefault<?= $g['id'] ?>" name="gejala[]">
                                                <label class="form-check-label" for="flexCheckDefault<?= $g['id'] ?>">
                                                    <?= $g['nama_gejala']; ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <hr class="d-block mt-4">
                                    <button type="submit" name="cek" class="btn btn-primary py-3 d-block w-100 fw-bold mt-4">Cek Sekarang</button>
                                <?php endif; ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section><!-- End About Section -->
</main>

<?php
require 'mysql-footer.php';
require 'templates/footer.php';
?>