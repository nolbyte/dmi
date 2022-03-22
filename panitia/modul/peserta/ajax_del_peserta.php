<?php
defined("RESMI") or die("error");

if ($_REQUEST['empid']) {
    //$kelas = $_POST['empid'];
    $sql = $db->prepare("DELETE FROM us_pendaftar WHERE siswa_uid = :siswana");
    $sql->execute(array(':siswana' => $_REQUEST['empid']));
    if ($sql) {
        echo "Data Peserta berhasil dihapus";
    }
}
