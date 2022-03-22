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

<main class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="bg-dark">
                    <img src="assets/img/dmi.png" class="card-img-top" alt="Dewan Mesjid Indonesia" style="max-width:600px">
                </div>
                <div class="card-body">
                    <h3 class="text-center mb-5">SELF ASSESSMENT - ANGKET PENILAIAN MANDIRI DMI AWARD</h3>
                    <div class="col-md-6">
                        <?php
                        if (isset($error)) {
                        ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <div>
                                    <h5>Kesalahan ditemukan</h5>
                                    <?php
                                    foreach ($error as $row) {
                                        echo $row . '<br>';
                                    }
                                    ?>
                                    <meta http-equiv="refresh" content="125">
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <div>
                                    kolom bertanda * wajib diisi
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data" onSubmit="return validate();">
                        <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                        <div class="mb-3">
                            <label for="" class="col-form-label">Nama Masjid/Mushola <span class="text-danger">*</span></label>
                            <input type="text" name="nama_mesjid" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Kategori Mesjid <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select" required>
                                <option value="">--Pilih Kategori--</option>
                                <?php
                                $sql = $db->prepare("SELECT * FROM dm_kategori");
                                $sql->execute();
                                foreach ($sql->fetchAll() as $row) {
                                    echo '<option value="' . $row['kategori_id'] . '">' . $row['kategori_nama'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Alamat Jalan / Gang <span class="text-danger">*</span></label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">RT <span class="text-danger">*</span></label>
                            <input type="number" class="form-control w-25" name="rt" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">RW <span class="text-danger">*</span></label>
                            <input type="number" class="form-control w-25" name="rw" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" name="desa" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" name="kecamatan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                            <input type="text" name="kabupaten" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" name="provinsi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Nama Penilai (Perwakilan Pengurus/DKM) <span class="text-danger">*</span></label>
                            <input type="text" name="pic" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Alamat Email Penilai <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="pic_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Nomor HP Penilai <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="pic_hp" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Nama Ketua Pengurus Mesjid/DKM <span class="text-danger">*</span></label>
                            <input type="text" name="ketua" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="col-form-label">Nomor HP Ketua Pengurus Mesjid/DKM <span class="text-danger">*</span></label>
                            <input type="number" name="ketua_hp" class="form-control" required>
                        </div>
                        <div class="alert alert-info">
                            <ol>
                                <li>
                                    Untuk mengetahui tingkat penilaian masjid/musholla, maka diharapkan anda mengisi angket ini dengan jujur dan apa adanya, serta disertakan bukti atau informasi akurat lainnya.
                                </li>
                                <li>
                                    Untuk kolom “Bukti Pendukung * (Nama Program / Kegiatan/ Dokumen/ Sertifikat)”, anda mengisi/upload dengan lengkap nama program/kegiatan/dokumen pendukungnya.
                                </li>
                                <li>Dokumen pendukung yang diupload berupa berkas PDF</li>
                            </ol>
                        </div>
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kriteria Penilaian</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = $db->prepare("SELECT * FROM dm_kriteria");
                                    $sql->execute();
                                    foreach ($sql->fetchAll() as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $row['kriteria_nama'] ?></td>
                                            <td><?= $row['kriteria_keterangan'] ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-danger">
                            Silahkan pilih kriteria penilaian di bawah sesuai dengan aspek pernyataan yang tersedia, sertakan bukti jika ada dengan mengupload berkas PDF dengan ukuran maksimal 5MB.
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">Nomor</th>
                                        <th class="w-50 text-center align-middle">Pernyataan/Aspek</th>
                                        <th class="text-center align-middle">Penilaian</th>
                                        <th class="text-center align-middle">Bukti Pendukung<br>(Nama Program / Kegiatan/ Dokumen / Sertifikat)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="bg-warning">Contoh<br>No.1</td>
                                        <td class="bg-warning">
                                            Struktur organisasi yang dilengkapi dengan uraian kerja (Job Description) dan penempatan pengurus yang sesuai dengan tuntutan kompetensinya.
                                        </td>
                                        <td class="bg-warning">
                                            3.DITERAPKAN & SEBAGIAN
                                        </td>
                                        <td class="bg-warning">
                                            1.a. Struktur Organisasi sejak 2015 hingga sekarang. (Belum ada Uraian kerja & penempatan sesuai kompetensinya )
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-warning">Contoh<br>No.22</td>
                                        <td class="bg-warning">
                                            Terselenggaranya kepengurusan khusus & kegiatan terkait masjid ramah anak.
                                        </td>
                                        <td class="bg-warning">
                                            4.KONSISTEN & LEBIH
                                        </td>
                                        <td class="bg-warning">
                                            22.a. Struktur Kepengurusan/ Panitia Program Ramah Anak. 22.b. Laporan pelaksanaan program masjid ramah anak yang sudah berjalan 4 tahun.
                                        </td>
                                    </tr>
                                    <?php
                                    $sql = $db->prepare("SELECT * FROM dm_aspek");
                                    $sql->execute();
                                    $no = 1;
                                    foreach ($sql->fetchAll() as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><input type="hidden" name="aspek[]" value="<?= $row['aspek_id'] ?>"><?= $row['aspek_nama'] ?></td>
                                            <td>
                                                <select name="nilai[]" class="form-select" required>
                                                    <?php
                                                    $sql = $db->prepare("SELECT * FROM dm_kriteria ORDER BY kriteria_id ASC");
                                                    $sql->execute();
                                                    foreach ($sql->fetchAll() as $row) {
                                                        echo '<option value="' . $row['kriteria_poin'] . '">' . $row['kriteria_nama'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea name="bukti" class="form-control" required></textarea>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="2">Link Google Drive</td>
                                        <td colspan="2">
                                            <input type="text" class="form-control" name="link_gdrive" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" value="" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Demikian penilaian mandiri yang telah diisi dengan jujur dan apa adanya, apabila dikemudian hari ada hal-hal yang perlu di konfirmasikan kembali, maka kami siap menyertakan bukti atau informasi detail lainnya serta akan membantu proses pengecekan / observasi langsung (bila dibutuhkan).
                            </label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-sm btn-success" id="kirim"><i class="bi-upload"></i> Kirim Penilaian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php' ?>