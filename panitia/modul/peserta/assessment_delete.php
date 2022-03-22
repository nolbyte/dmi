<?php
defined("RESMI") or die("error");

if ($_REQUEST['empid']) {
    //$kelas = $_POST['empid'];
    $sql = $db->prepare("DELETE a.*, b.* FROM dm_mesjid a INNER JOIN dm_nilai b ON a.mesjid_id=b.nilai_mesjid WHERE a.mesjid_id = :idna");
    $sql->execute(array(':idna' => $_REQUEST['empid']));
    if ($sql) {
        echo "Data self assessment berhasil dihapus";
    }
}
