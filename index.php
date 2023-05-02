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

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container" data-aos="zoom-out" data-aos-delay="100">
        <div class="row">
            <div class="col-md-7">
                <h1>Welcome to <span>Sistem Pakar Kesehatan Mental Remaja</span></h1>
                <h2>Kami akan membantu memeriksa kesehatan mental anda.</h2>
                <div class="d-flex">
                    <a href="cekkondisi.php" class="btn-get-started scrollto">Cek Kondisi Anda Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</section><!-- End Hero -->

<section id="Metode" class="about section-bg">
    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2>Metode</h2>
        </div>
        <div class="items text-center">
            <div class="row">
                <div class="col-md-4">
                    <div class="desc">
                        <h5 class="text-center text-primary fw-bold">Pengetahuan</h5>
                        <p class="text-justify">
                            Langkah paling awal untuk membuat sistem pakar adalah dengan
                            menggali informasi tentang suatu masalah yang akan dipecahkan
                            dengan bantuan seorang pakar maupun sumber pengetahuan lainnya
                            seperti buku maupun jurnal.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="desc">
                        <h5 class="text-center text-primary fw-bold">Forward Chaining</h5>
                        <p class=" text-alignment justify">
                            Metode forward chaining adalah merupakan metode yang digunakan
                            dengan mencari beberapa fakta-fakta dengan mencari pedoman yang
                            sesuai dengan dugaan/hipotesis yang muncul menuju suatu
                            hasil / kesimpulan.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="desc">
                        <h5 class="text-center text-primary fw-bold">Keakuratan</h5>
                        <p>
                            Pada sistem pakar kesehatan mental remaja ini, tingkat keakuratan
                            masih belum maksimal karenana data yang diperoleh masih sedikit
                            sehingga masih belum dapat menggantikan keakuratan pakar yang
                            sesungguhnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require 'mysql-footer.php';
require 'templates/footer.php';
?>