<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
define("RESMI", "OK");

if (!isset($_SESSION['idADM'])) {
  header("Location: ../index.php");
}

require('../config/database.php');
require('../config/fungsi.php');
require('../config/csrf-token.php');
require('../vendor/autoload.php');
//token
$csrf = new csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);

if (isset($_GET['mod'])) {
  $mod = sanitasi($_GET['mod']);
  $hal = sanitasi($_GET['hal']);

  include('modul/' . $mod . '/' . $hal . '.php');
}
