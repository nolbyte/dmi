<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
define("RESMI", "OK");

//konfigurasi
require('config/database.php');
require('config/csrf-token.php');
require('config/fungsi.php');
//require('config/gump.class.php');
require('vendor/autoload.php');
//token
$csrf = new csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DMI Award</title>
    <link rel="shortcut icon" type="image/jpg" href="assets/img/dmi-logo.png" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.0.18/sweetalert2.all.min.js" integrity="sha512-kW/Di7T8diljfKY9/VU2ybQZSQrbClTiUuk13fK/TIvlEB1XqEdhlUp9D+BHGYuEoS9ZQTd3D8fr9iE74LvCkA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.0.18/sweetalert2.min.css" integrity="sha512-riZwnB8ebhwOVAUlYoILfran/fH0deyunXyJZ+yJGDyU0Y8gsDGtPHn1eh276aNADKgFERecHecJgkzcE9J3Lg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css">
    <link href="assets/style/login.css" rel="stylesheet">
</head>

<body>
    <?php
    if ($csrf->check_valid('post')) {
        $gump     = new GUMP();
        $username = $_POST['username'];
        $password = $_POST['password'];

        $_POST = array(
            'username' => $username,
            'password' => $password
        );

        $_POST = $gump->sanitize($_POST);
        $gump->validation_rules(array(
            'username' => 'required',
            'password' => 'required'
        ));

        $gump->filter_rules(array(
            'username' => 'trim|sanitize_email|sanitize_string'
        ));
        $ok = $gump->run($_POST);
        if ($ok === false) {
            $error[] = $gump->get_readable_errors(true);
        } else {
            $sql = $db->prepare("SELECT * FROM dm_user WHERE user_name = :user");
            $sql->execute(array(':user' => $username));
            $log = $sql->fetch(PDO::FETCH_ASSOC);
            if ($log) {
                if (password_verify($password, $log['user_pass'])) {
                    $_SESSION['idADM'] = $log['user_id'];
                    $_SESSION['nama']   = $log['user_nama'];
                    header('Location: panitia/index.php');
                } else {
    ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: 'Username/Password yang anda masukkan tidak sesuai',
                            showConfirmButton: false,
                            timer: 1700
                        })
                    </script>
                <?php
                }
            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: 'Username/Password yang anda masukkan tidak cocok',
                        showConfirmButton: false,
                        timer: 1700
                    })
                </script>
    <?php
            }
        }
    }

    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 mx-auto py-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="text-center">DMI AWARD LOGIN SISTEM</h4>
                        <?php
                        if (isset($error)) {
                            foreach ($error as $salah) {
                        ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <div>
                                        <?= $salah; ?>
                                        <meta http-equiv="refresh" content="3">
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <form method="post" action="">
                            <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Username Login" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" data-toggle="password" autocomplete="new-password" required>
                                <p class="form-text">Tap/klik pada ikon mata untuk menampilkan password</p>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary"><i data-feather="unlock"></i> Login</button>
                            </div>
                            <div>
                                <a href="">Lupa password</a><br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
    <!--<script src="assets/js/dashboard.js"></script>-->
</body>

</html>