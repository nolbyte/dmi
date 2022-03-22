<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
define("RESMI", "OK");

//konfigurasi
require('config/database.php');
require('config/csrf-token.php');
require('config/fungsi.php');

$data = $_POST['data'];
$id   = $_POST['id'];
$n = strlen($id);
$m = ($n == 2 ? 5 : ($n == 5 ? 8 : 13));
if ($data == "kabupaten") {
?>
    <select id="form_kab" name="kabupaten" class="form-select" required>
        <option value="">--Pilih Kabupaten/Kota--</option>
        <?php
        $sql = $db->prepare("SELECT kode, nama FROM dm_wilayah WHERE LEFT(kode,'$n')='$id' AND CHAR_LENGTH(kode)=$m ORDER BY nama");
        $sql->execute();
        foreach ($sql->fetchAll() as $row) {
            echo '<option value="' . $row['kode'] . '">' . $row['nama'] . '</option>';
        }
        ?>
    </select>
<?php
} elseif ($data == "kecamatan") {
?>
    <select id="form_kec" name="kecamatan" class="form-select" required>
        <option value="">--Pilih Kecamatan--</option>
        <?php
        $sql = $db->prepare("SELECT kode, nama FROM dm_wilayah WHERE LEFT(kode,'$n')='$id' AND CHAR_LENGTH(kode)=$m ORDER BY nama");
        $sql->execute();
        foreach ($sql->fetchAll() as $row) {
            echo '<option value="' . $row['kode'] . '">' . $row['nama'] . '</option>';
        }
        ?>
    </select>
<?php
} elseif ($data == "desa") {
?>
    <select id="form_des" name="kecamatan" class="form-select" required>
        <option value="">--Pilih Desa/Kelurahan--</option>
        <?php
        $sql = $db->prepare("SELECT kode, nama FROM dm_wilayah WHERE LEFT(kode,'$n')='$id' AND CHAR_LENGTH(kode)=$m ORDER BY nama");
        $sql->execute();
        foreach ($sql->fetchAll() as $row) {
            echo '<option value="' . $row['kode'] . '">' . $row['nama'] . '</option>';
        }
        ?>
    </select>
<?php
}
