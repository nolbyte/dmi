<?php
defined("RESMI") or die("error");
$mod = isset($_GET['mod']) ? $_GET['mod'] : '';
switch ($mod) {
    case 'kategori-mesjid': {
            include 'modul/referensi/kategori_mesjid.php';
            break;
        }
    case 'kategori-save': {
            include 'modul/referensi/kategori_save.php';
            break;
        }
    case 'kategori-edit': {
            include 'modul/referensi/kategori_edit.php';
            break;
        }
    case 'aspek-nilai': {
            include 'modul/referensi/aspek_nilai.php';
            break;
        }
    case 'aspek-save': {
            include 'modul/referensi/aspek_save.php';
            break;
        }
    case 'aspek-edit': {
            include 'modul/referensi/aspek_edit.php';
            break;
        }
    case 'kriteria-nilai': {
            include 'modul/referensi/kriteria_nilai.php';
            break;
        }
    case 'kriteria-save': {
            include 'modul/referensi/kriteria_save.php';
            break;
        }
    case 'kriteria-edit': {
            include 'modul/referensi/kriteria_edit.php';
            break;
        }
    case 'assessment-list': {
            include 'modul/peserta/assessment.php';
            break;
        }
    default: {
            include 'dashboard.php';
        }
}
