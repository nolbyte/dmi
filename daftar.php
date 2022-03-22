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
$per = isset($_GET['per']) ? $_GET['per'] : '';
$sql = $db->prepare("SELECT * FROM pmb_periode pp LEFT JOIN us_akademik ua ON pp.per_akademik=ua.akademik_id LEFT JOIN us_sistem us ON pp.per_sistem=us.sistem_id WHERE pp.per_id = :idna");
$sql->execute(array(':idna' => $per));
$periode = $sql->fetch(PDO::FETCH_ASSOC);
//tangani form
use Ramsey\Uuid\Uuid;
//nomor pendaftaran
$tgl_daf = date("m/d/Y");
$tgl = date("Ydm");
$u = $db->prepare("SELECT reg_id FROM us_pendaftar ORDER BY reg_id DESC LIMIT 1");
$u->execute();
$n = $u->fetch(PDO::FETCH_ASSOC);
$reg_id = $n['reg_id'];
$urut = $reg_id++;
$no_daftar = 'AFM/PMB-2223/' . $tgl . sprintf('%04s', $urut);

if ($csrf->check_valid('post')) {
    $gump        = new GUMP();
    $uid         = Uuid::uuid4()->toString();
    $nik         = $_POST['nik'];
    $nama        = $_POST['nama'];
    $tempatLahir = $_POST['tempatLahir'];
    $tglLahir    = $_POST['tglLahir'];
    $kelamin     = $_POST['kelamin'];
    $sekolah     = $_POST['sekolah'];
    $jurusan     = $_POST['jurusan'];
    $hp          = $_POST['hp'];
    $email       = $_POST['email'];
    $password    = $_POST['password'];
    $survey      = $_POST['survey'];
    $_POST = array(
        'nik'         => $nik,
        'nama'        => $nama,
        'tempatLahir' => $tempatLahir,
        'tglLahir'    => $tglLahir,
        'kelamin'     => $kelamin,
        'sekolah'     => $sekolah,
        'jurusan'     => $jurusan,
        'hp'          => $hp,
        'email'       => $email,
        'password'    => $password,
        'survey'      => $survey
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'nik'         => 'required|numeric|exact_len,16',
        'nama'        => 'required|valid_name',
        'tempatLahir' => 'required',
        'tglLahir'    => 'required',
        'kelamin'     => 'required',
        'sekolah'     => 'required',
        'jurusan'     => 'required',
        'hp'          => 'required',
        'email'       => 'required|valid_email',
        'password'    => 'required|min_len,8',
        'survey'      => 'required'
    ));
    $gump->filter_rules(array(
        'nik'         => 'trim|sanitize_numbers',
        'nama'        => 'trim|sanitize_string',
        'tempatLahir' => 'trim|sanitize_string',
        'tglLahir'    => 'trim|sanitize_string',
        'kelamin'     => 'trim|sanitize_string',
        'sekolah'     => 'trim|sanitize_string',
        'jurusan'     => 'trim|sanitize_string',
        'hp'          => 'trim|sanitize_numbers',
        'email'       => 'trim|sanitize_email',
        'survey'      => 'trim|sanitize_string'
    ));
    $ok = $gump->run($_POST);
    $mail = $db->prepare("SELECT reg_email FROM us_pendaftar WHERE reg_email = :email");
    $mail->execute(array(':email' => $email));
    $cmail = $mail->fetch();
    if ($ok == false || $cmail) {
        $error[] = $gump->get_readable_errors(true);
        $error[] = '<i class="bi-exclamation-circle"></i> Alamat email sudah digunakan<br>';
    } else {
        $pass = password_hash($password, PASSWORD_BCRYPT, $options);
        $activasion = md5(uniqid(rand(), true));
        $sql = $db->prepare("INSERT INTO us_pendaftar SET reg_uid = ?, reg_no_daftar = ?, reg_tgl_daftar = ?, reg_nama = ?, reg_tmpt_lahir = ?, reg_tgl_lahir = ?, reg_nik = ?, reg_kelamin = ?, reg_hp = ?, reg_sekolah = ?, reg_jurusan = ?, reg_survey = ?, reg_gelombang = ?, reg_email = ?, reg_password = ?, reg_status = ?");
        $sql->bindParam(1, $uid);
        $sql->bindParam(2, $no_daftar);
        $sql->bindParam(3, $tgl_daf);
        $sql->bindParam(4, $nama);
        $sql->bindParam(5, $tempatLahir);
        $sql->bindParam(6, $tglLahir);
        $sql->bindParam(7, $nik);
        $sql->bindParam(8, $kelamin);
        $sql->bindParam(9, $hp);
        $sql->bindParam(10, $sekolah);
        $sql->bindParam(11, $jurusan);
        $sql->bindParam(12, $survey);
        $sql->bindParam(13, $periode['per_id']);
        $sql->bindParam(14, $email);
        $sql->bindParam(15, $pass);
        $sql->bindParam(16, $activasion);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
            $id = $db->lastInsertId();
            $token = urlencode(encryptor('encrypt', $id));
            //kirim email
            $to = $email;
            $subject = "Konfirmasi Pendaftaran PMB Akfar Mahadhika";
            $body = "Terima kasih telah mendaftar di Akademi Farmasi Mahadhika.\n\n Untuk mengaktifkan akun anda, silahkan ikuti tautan berikut:\n\n " . DIR . "aktivasi.php?x=$token&y=$activasion\n\n Regard Panitia PMB \n\nMohon untuk tidak membalas/me-reply melalui alamat email ini\n\n";
            $additionalheaders = "From: akfar@mahadhika.or.id\r\n";
            $additionalheaders .= "Reply-To: akfar@mahadhika.or.id";
            $additionalheaders = "MIME-Version: 1.0\r\n";
            mail($to, $subject, $body, $additionalheaders);
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil',
                    text: 'Cek inbox/spam email untuk aktivasi akun',
                    showConfirmButton: true,
                    timer: 3500
                }).then(function() {
                    window.location.href = "index.php";
                })
            </script>
<?php
        }
    }
}
?>

<main class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="flex-grow-1"><i class="bi-clipboard-check"></i> FORMULIR PENDAFTARAN ONLINE</div>
                        <div><a href="index.php" class="btn btn-sm btn-primary"><i class="bi-arrow-left-circle fs-6"></i> KEMBALI</a></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            if (isset($error)) {
                            ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <div>
                                        <h5>Kesalahan ditemukan</h5>
                                        <?php
                                        foreach ($error as $row) {
                                            echo $row;
                                        }
                                        ?>
                                        <meta http-equiv="refresh" content="5">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-4">
                            <span class="text-info fs-6"><?= $periode['per_nama'] . '</span><br>' . tgl_id($periode['per_tgl_awal']) . ' - ' . tgl_id($periode['per_tgl_akhir']) ?>
                        </div>
                        <div class="ms-3">
                            <span class="text-info fs-6">Sistem Kuliah</span><br><?= $periode['sistem_nama'] ?>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 mb-3">
                        * Semua kolom wajib diisi.<br>
                        * Kode aktivasi akan dikirim melalui email yang didaftarkan.<br>
                        * Periksa kotak inbox atau spam email untuk melakukan aktivasi setelah pengisian formulir berhasil.
                    </div>
                    <form method="post" action="">
                        <div class="d-flex mb-3">
                            <div class="flex-grow-1"></div>
                            <div>
                                <button type="submit" class="btn btn-primary"><i class="bi-check-all fs-5"></i> Simpan Pendaftaran</button>
                            </div>
                        </div>
                        <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                        <div class="mb-3">
                            <label for="" class="col-form-label">Nomor Induk Kependudukan (NIK)</label>
                            <input type="number" name="nik" class="form-control" placeholder="NIK sesuai KTP" pattern="[0-9]{16}" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap tanpa gelar dan singkatan sesuai KK. Contoh: Avril Lavigne" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Tempat Lahir</label>
                            <input type="text" name="tempatLahir" class="form-control" placeholder="Tempat lahir sesuai KK" required>
                        </div>
                        <div class="mb-3">
                            <div class="label col-form-label">Tanggal Lahir</div>
                            <input type="date" name="tglLahir" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Jenis Kelamin</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="kelamin" value="L">
                                <label class="form-check-label">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="kelamin" value="P">
                                <label class="form-check-label">Perempuan</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Asal Sekolah</label>
                            <input type="text" name="sekolah" class="form-control" placeholder="Nama asal sekolah, SMA/SMK/MA/ Sederajat" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Jurusan Sekolah</label>
                            <input type="text" name="jurusan" class="form-control" placeholder="Jurusan sekolah. IPA/IPS/Farmasi" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">HP</label>
                            <input type="number" class="form-control" name="hp" placeholder="62813xxx" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email yang bisa dihubungi" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-fomr-label">Password Login</label>
                            <input type="password" class="form-control" name="password" placeholder="Password Login, minimal 8 karakter" id="password" data-toggle="password" autocomplete="new-password" required>
                            <p class="form-text">klik/tap pada ikon mata untuk menampilkan password</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Sumber Informasi</label>
                            <select name="survey" class="form-select" required>
                                <option value="Kerabat/Keluarga">Kerabat/Keluarga</option>
                                <option value="Guru/Kepala Sekolah">Guru/Kepala Sekolah</option>
                                <option value="Presentasi">Presentasi</option>
                                <option value="Website">Website</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Mesin Pencari / Google">Mesin Pencari / Google</option>
                            </select>
                        </div>
                        <div class="d-flex mt-3">
                            <div class="flex-grow-1"></div>
                            <div>
                                <button type="submit" class="btn btn-primary"><i class="bi-check-all fs-5"></i> Simpan Pendaftaran</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</main>
<?php include 'footer.php' ?>