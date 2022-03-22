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
include 'header.php';
?>
<main class="container py-4">
    <div class="row">
        <?php
        //$regID = trim($_GET['x']);
        $active = trim($_GET['y']);
        if (isset($_GET['x']) && !empty($active)) {
            $cokot = urldecode($_GET['x']);
            $idna = encryptor('decrypt', $cokot);
            $sql = $db->prepare("UPDATE us_pendaftar SET reg_status = 'Yes' WHERE reg_id = :idna AND reg_status = :statusna");
            $sql->execute(array(
                ':idna'     => $idna,
                ':statusna' => $active
            ));
            if ($sql->rowCount() == 1) {
                //kirim email
                $to = 'akfar@mahadhika.or.id';
                $subject = "Konfirmasi Pendaftaran PMB Akfar Mahadhika";
                $body = "Pendaftar baru telah melakukan aktivasi akun\n\n Silahkan cek di website sipmb\n\n " . DIR . "\n\n Regard Panitia PMB \n\nMohon untuk tidak membalas/me-reply melalui alamat email ini\n\n";
                $additionalheaders = "From: akfar@mahadhika.or.id\r\n";
                $additionalheaders .= "Reply-To: akfar@mahadhika.or.id";
                $additionalheaders = "MIME-Version: 1.0\r\n";
                mail($to, $subject, $body, $additionalheaders);
        ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Aktivasi Berhasil',
                        text: 'Silahkan login untuk memulai',
                        showConfirmButton: true,
                        timer: 3500
                    }).then(function() {
                        window.location.href = "login.php";
                    })
                </script>
            <?php
            } else {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Akun Gagal Diaktivasi',
                        text: 'Silahkan hubungi panitia PMB',
                        showConfirmButton: true,
                        timer: 3500
                    }).then(function() {
                        window.location.href = "index.php";
                    })
                </script>
        <?php
            }
        }
        ?>
    </div>
</main>
<?php include 'footer.php' ?>