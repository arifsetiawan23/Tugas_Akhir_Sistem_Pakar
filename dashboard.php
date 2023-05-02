<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

?>

<div class="container py-5">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="display-6">Selemat Datang, <div class="fw-bold d-inline-block"><?= auth()['user']['nama']; ?></div>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>