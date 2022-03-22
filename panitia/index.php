<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
define("RESMI", "OK");

if (!isset($_SESSION['idADM'])) {
    header('Location: ../index.php');
}

//konfigurasi
require('../config/database.php');
require('../config/fungsi.php');
require('../vendor/autoload.php');
require('../config/csrf-token.php');

//token
$csrf = new csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);

$sql = $db->prepare("SELECT * FROM dm_user WHERE user_id = :idna");
$sql->execute(array(':idna' => $_SESSION['idADM']));
$adm = $sql->fetch(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d6efd">
    <title>DMI Award</title>
    <link rel="shortcut icon" type="image/jpg" href="../assets/img/dmi-logo.png" />
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-avatar@latest/dist/avatar.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.0.18/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.0.18/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="../assets/style/dashboard.css" rel="stylesheet">
    <link href="../assets/style/style.css" rel="stylesheet">
    <link href="../assets/style/font-awesome.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <header class="section-header py-3 head-banner">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 style="padding-top: 2%;">Dewan Mesjid Indonesia</h4>
                </div>
                <div>
                    <div class="btn-group">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../assets/img/avatar2.png" class="avatar avatar-24 rounded-circle" alt="User Image">
                            <span class="hidden-xs"><?= $adm['user_name'] ?></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li class="text-center">
                                <img src="../assets/img/avatar2.png" class="avatar avatar-64" alt="User Image">
                                <p>
                                    <?= $adm['user_nama'] ?>
                                </p>
                            </li>
                            <li class="text-center">
                                <a class="btn btn-xs btn-success p-2" href="logout.php"><i class="bi-box-arrow-right"></i> Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="../assets/img/dmi-logo.png" width="30" alt="Dewan Mesjid Indonesia" class="d-inline-block align-text-middle"> DMI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main_nav">
                <?php include 'component/navbar.php'; ?>
            </div> <!-- navbar-collapse.// -->
        </div> <!-- container-fluid.// -->
    </nav>
    <!-- Begin page content -->
    <main class="container-xl py-4">
        <div class="row">
            <?php require 'component/page.php'; ?>
        </div>
    </main>
    <footer class="footer mt-auto py-3 bg-success bg-gradient">
        <div class="container-xl">
            <span class="text-white"><?= date('Y') ?> Dewan Mesjid Indonesia</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>