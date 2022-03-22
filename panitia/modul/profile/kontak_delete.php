<?php
defined("RESMI") or die("error");

if ($_REQUEST['empid']) {
    //$kelas = $_POST['empid'];
    $sql = $db->prepare("DELETE FROM us_kontak WHERE kontak_id = :idna");
    $sql->execute(array(':idna' => $_REQUEST['empid']));
    if ($sql) {
        echo "Data kontak berhasil dihapus";
    }
}
