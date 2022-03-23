<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
if ($csrf->check_valid('post')) {
    $gump        = new GUMP();
    $nama_mesjid = $_POST['nama_mesjid'];
    $alamat      = $_POST['alamat'];
    $rt          = $_POST['rt'];
    $rw          = $_POST['rw'];
    $desa        = $_POST['desa'];
    $kecamatan   = $_POST['kecamatan'];
    $kabupaten   = $_POST['kabupaten'];
    $provinsi    = $_POST['provinsi'];
    $kategori    = $_POST['kategori'];
    $pic         = $_POST['pic'];
    $aspek       = $_POST['aspek'];
    $nilai       = $_POST['nilai'];
    $_POST = array(
        'nama_mesjid' => $nama_mesjid,
        'alamat'      => $alamat,
        'rt'          => $rt,
        'rw'          => $rw,
        'desa'        => $desa,
        'kecamatan'   => $kecamatan,
        'kabupaten'   => $kabupaten,
        'provinsi'    => $provinsi,
        'kategori'    => $kategori,
        'pic'         => $pic,
        'aspek'       => $aspek,
        'nilai'       => $nilai
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'nama_mesjid' => 'required',
        'alamat'      => 'required',
        'rt'          => 'required|numeric',
        'rw'          => 'required|numeric',
        'desa'        => 'required',
        'kecamatan'   => 'required',
        'kabupaten'   => 'required',
        'provinsi'    => 'required',
        'kategori'    => 'required|numeric',
        'pic'         => 'required',
        'aspek'       => 'required|numeric',
        'nilai'       => 'required|numeric',
        'berkas'      => 'file_size,7168kb'
    ));
    $gump->filter_rules(array(
        'nama_mesjid' => 'trim|sanitize_string',
        'alamat'      => 'trim|sanitize_string',
        'rt'          => 'trim|sanitize_numbers',
        'rw'          => 'trim|sanitize_numbers',
        'desa'        => 'trim|sanitize_string',
        'kecamatan'   => 'trim|sanitize_string',
        'kabupaten'   => 'trim|sanitize_string',
        'provinsi'    => 'trim|sanitize_string',
        'kategori'    => 'trim|sanitize_numbers',
        'pic'         => 'trim|sanitize_string',
        'aspek'       => 'trim|sanitize_numbers',
        'nilai'       => 'trim|sanitize_numbers'
    ));
    $ok = $gump->run($_POST);
    if ($ok == false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("INSERT INTO dm_mesjid SET mesjid_nama = ?, mesjid_jalan = ?, mesjid_rt = ?, mesjid_rw = ?, mesjid_desa = ?, mesjid_kecamatan = ?, mesjid_kota = ?, mesjid_provinsi =?, mesjid_pic = ?, mesjid_kategori = ?");
        $sql->bindParam(1, $nama_mesjid);
        $sql->bindParam(2, $alamat);
        $sql->bindParam(3, $rt);
        $sql->bindParam(4, $rw);
        $sql->bindParam(5, $desa);
        $sql->bindParam(6, $kecamatan);
        $sql->bindParam(7, $kabupaten);
        $sql->bindParam(8, $provinsi);
        $sql->bindParam(9, $pic);
        $sql->bindParam(10, $kategori);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
            $lastID = $db->lastInsertId();
            $size = '';
            $target_dir = "upload/";
            $files = $_FILES;
            $Jberkas = COUNT($files['berkas']['name']);
            for ($i = 0; $i < $Jberkas; $i++) {
                $aspeknya = $aspek[$i];
                $nilainya = $nilai[$i];
                $namaFiles = $files['berkas']['name'][$i];
                $lokasiTmp = $files['berkas']['tmp_name'][$i];
                $size = $files['berkas']['size'][$i];
                $namaBaru = $lastID . '-' . $namaFiles;
                $lokasiBaru = $target_dir . $namaBaru;
                if (!empty($namaFiles)) {
                    $fileType = pathinfo($lokasiBaru, PATHINFO_EXTENSION);
                    $allowTypes = array('pdf');
                    move_uploaded_file($lokasiTmp, $lokasiBaru);
                    $sql = $db->prepare("INSERT INTO dm_nilai SET nilai_mesjid = ?, nilai_aspek = ?, nilai_file = ?, nilai_poin = ?");
                    $sql->bindParam(1, $lastID);
                    $sql->bindParam(2, $aspeknya);
                    $sql->bindParam(3, $namaBaru);
                    $sql->bindParam(4, $nilainya);
                    if (!$sql->execute()) {
                        print_r($sql->errorInfo());
                    } else {
?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Proses Berhasil',
                                text: 'Self Assessment berhasil dikirim',
                                showConfirmButton: true,
                                timer: 1000
                            }).then(function() {
                                window.location.href = "index.php";
                            })
                        </script>
                    <?php
                    }
                } else {
                    $sql = $db->prepare("INSERT INTO dm_nilai SET nilai_mesjid = ?, nilai_aspek = ?, nilai_poin = ?");
                    $sql->bindParam(1, $lastID);
                    $sql->bindParam(2, $aspeknya);
                    $sql->bindParam(3, $nilainya);
                    if (!$sql->execute()) {
                        print_r($sql->errorInfo());
                    } else {
                    ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Proses Berhasil',
                                text: 'Self Assessment berhasil dikirim',
                                showConfirmButton: true,
                                timer: 1000
                            }).then(function() {
                                window.location.href = "index.php";
                            })
                        </script>
<?php
                    }
                }
            }
        }
    }
}
?>

<main class="container-fluid">
    <div class="row">
        <div class="col-md-8 px-0">
            <img class="img-fluid" src="assets/img/istiqlal5.jpg">
        </div>
        <div class="col-md-4 g-3">
            <div class="d-flex">
                <div class="flex-grow-1 p-3">
                    <p class="text-end">SELF ASSESSMENT DMI AWARD 2022<br>
                        <a style="text-decoration: none;" href="#" onclick="show('register');">daftar sekarang</a>
                    </p>
                </div>
                <div class="me-3">
                    <img class="img-fluid" src="assets/img/logo-dmi-award.png" style="max-width: 80px;">
                </div>
            </div>
            <div id="login" class="mt-3 mb-4">
                <h6 class="text-info text-center">Sign In Self Assessment Mesjid</h6>
                <form method="post" action="">
                    <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                    <div class="mb-3 mx-5">
                        <input type="text" name="usernamae" class="form-control" placeholder="username" required>
                    </div>
                    <div class="mb-3 mx-5">
                        <input type="password" name="password" class="form-control" placeholder="password" id="password" data-toggle="password" autocomplete="current-password" required>
                    </div>
                    <div class="d-flex mx-5">
                        <div class="flex-grow-1 p-1">
                            <a style="text-decoration: none" href="">Lupa Password?</a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi-lock"></i> Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="register" class="mt-3 mb-4" style="display: none;">
                <h6 class="text-info text-center">Sign Up Self Assessment Mesjid</h6>
                <form method="post" action="">
                    <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                    <div class="mb-3 mx-5">
                        <input type="text" name="pengurus" class="form-control" placeholder="Nama Pengurus/DKM" required>
                    </div>
                    <div class="mb-3 mx-5">
                        <input type="text" name="nama_mesjid" class="form-control" placeholder="Nama Mesjid" required>
                    </div>
                    <div class="mb-3 mx-5">
                        <input type="number" name="hp_pengurus" class="form-control" placeholder="HP Pengurus, Format 628xxx" required>
                    </div>
                    <div class="mb-3 mx-5">
                        <input type="email" name="email_pengurus" class="form-control" placeholder="Email Pengurus" required>
                    </div>
                    <div class="mb-3 mx-5">
                        <input type="password" name="password" class="form-control" placeholder="password, minimal 8 karakter" pattern=".{8,}" id="password" data-toggle="password" autocomplete="new-password" title="Password minimal 8 karakter" required>
                    </div>
                    <div class="d-flex mx-5">
                        <div class="flex-grow-1 p-1">
                            <a style="text-decoration: none" href="#" onclick="show('login');">Sign In</a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi-lock"></i> Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php' ?>