<?php
defined("RESMI") or die("error");

if ($_REQUEST['empid']) {
    //$kelas = $_POST['empid'];
    $sql = $db->prepare("DELETE FROM dm_aspek WHERE aspek_id = :idna");
    $sql->execute(array(':idna' => $_REQUEST['empid']));
    if ($sql) {
        echo "Data aspek penilaian berhasil dihapus";
    }
}
