<?php
require 'mysql.php';
require 'global.php';
require 'templates/header.php';
require 'templates/navbar.php';

$sql = "SELECT * FROM artikel order by id_artikel desc";
$result = mysqli_query($conn, $sql);

$artikel = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql2 = "SELECT * FROM artikel order by id_artikel desc limit 3";
$result = mysqli_query($conn, $sql2);

$artikels = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<main id="main">

    <section id="cek-kondisi-anda" class="about section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Artikel</h2>
                 
            </div>
        </div>
            <div class="row justify-content-center">
                <div class="col-lg-10 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">
                   <?php if(isset($_GET['slug'])){  
                        $sqla = "SELECT * FROM artikel WHERE slug='$_GET[slug]'";
                        $result = mysqli_query($conn, $sqla);
                        $detail = mysqli_fetch_row($result);
                        
                      
                   ?>
                       <div class="row">
                        
                        <div class="item" style="margin-bottom:2em">
                            <div class="thumbnail card">
                                <div class="img-event"><br/>
                                    <center><img class="group list-group-image img-fluid" src="file/<?=$detail[4]?>" alt="<?=$detail[1]?>" style="width:100%; height:250px"/></center>
                                </div>
                                <div class="caption card-body">
                                    <a href="?slug=<?=$detail[2]?>"><h4 class="group card-title inner list-group-item-heading">
                                        <?=ucwords($detail[1])?></h4></a>
                                    <p class="group inner list-group-item-text"><?=$detail[3]?></p>
                                     
                                </div>
                            </div>
                        </div>
                         
              
                       </div>
                       <div class="row">
                         <?php foreach ($artikels as $g) : ?>
                        <div class="item col-xs-4 col-lg-4" style="margin-bottom:2em">
                            <div class="thumbnail card">
                                <div class="img-event">
                                    <img class="group list-group-image img-fluid" src="file/<?=$g['gambar']?>" alt="<?=$g['judul']?>" />
                                </div>
                                <div class="caption card-body">
                                    <a href="?slug=<?=$g['slug']?>"><h4 class="group card-title inner list-group-item-heading">
                                        <?=ucwords($g['judul'])?></h4></a>
                                    <p class="group inner list-group-item-text"><?=substr($g['deskripsi'],0,100)?></p>
                                     
                                </div>
                            </div>
                        </div>
                         <?php endforeach; ?>
              
                   </div>
                    
                   <?php }else{ ?>
                   <div class="row">
                         <?php foreach ($artikel as $g) : ?>
                        <div class="item col-xs-4 col-lg-4" style="margin-bottom:2em">
                            <div class="thumbnail card">
                                <div class="img-event">
                                    <img class="group list-group-image img-fluid" src="file/<?=$g['gambar']?>" alt="<?=$g['judul']?>" style="width:100%; height:250px"/>
                                </div>
                                <div class="caption card-body">
                                    <a href="?slug=<?=$g['slug']?>"><h4 class="group card-title inner list-group-item-heading">
                                        <?=ucwords($g['judul'])?></h4></a>
                                    <p class="group inner list-group-item-text"><?=substr($g['deskripsi'],0,100)?></p>
                                     
                                </div>
                            </div>
                        </div>
                         <?php endforeach; ?>
              
                   </div>
                   
                   <?php } ?>
                </div>
            </div>
    </section><!-- End About Section -->
</main>

<?php
require 'mysql-footer.php';
require 'templates/footer.php';
?>