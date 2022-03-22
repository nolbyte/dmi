<?php
$url = $_SERVER['REQUEST_URI'];
//option password
$options = [
    'cost' => 12,
];
function acakHuruf()
{
    $panjangacak = 6;
    $base = 'ABCDEFGHKLMNOPQRSTWXYZ123456789';
    $max = strlen($base) - 1;
    $acak = '';
    mt_srand((float)microtime() * 1000000);

    while (strlen($acak) < $panjangacak) {
        $acak .= $base[mt_rand(0, $max)];
    }
    return $acak;
}

function sanitasi($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//enkripsi parameter
function encryptor($action, $string)
{
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = 'wirosableng212';
    $secret_iv = 'bondjames007';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
function format_rp($angka)
{
    $jadi = "Rp. " . number_format($angka, 0, ',', '.');
    return $jadi . ",-";
}
function tgl_id($timestamp = '', $date_format = 'j F Y', $suffix = 'WIB')
{
    if (trim($timestamp) == '') {
        $timestamp = time();
    } elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
        '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
        '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
        '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
        '/November/', '/December/',
    );
    $replace = array(
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
        'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'Sepember',
        'Oktober', 'November', 'Desember',
    );
    $date = date($date_format, $timestamp);
    $date = preg_replace($pattern, $replace, $date);
    $date = "{$date}";
    return $date;
}
function createRange($start, $end, $format = 'Y-m-d')
{
    $start  = new DateTime($start);
    $end    = new DateTime($end);
    $invert = $start > $end;

    $dates = array();
    $dates[] = $start->format($format);
    while ($start != $end) {
        $start->modify(($invert ? '-' : '+') . '1 day');
        $dates[] = $start->format($format);
    }
    return $dates;
}

function tanggalan($waktu)
{
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date('H:i:s', strtotime($waktu));

    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam";

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari, $tanggal $bulan $tahun";
}

function smt_aktif()
{
    global $db;
    $status = 1;
    $sql = $db->prepare("SELECT * FROM us_akademik WHERE akademik_status = :status");
    $sql->execute(array(':status' => $status));
    $akd = $sql->fetch(PDO::FETCH_ASSOC);
    if ($akd) {
        if ($akd['akademik_status'] == 0) {
            echo 'Tidak ada semester aktif';
        } else {
            echo $akd['akademik_nama'];
        }
    }
    return $akd;
}

function prodi($default = "", $require = 'required')
{
    global $db;
    $sql = $db->prepare("SELECT prodi_id, prodi_nama, prodi_jenjang FROM us_prodi");
    $sql->execute();
    $result = '<select class="form-control" ' . $require . '>';
    //$result .= '<option value="">-- Program Studi --</option>';
    while ($prodi = $sql->fetch(PDO::FETCH_ASSOC)) {
        $selected = ($default == $prodi['prodi_id']) ? 'selected' : '';
        $result .= '<option value="' . $prodi['prodi_id'] . '" ' . $selected . '>' . $prodi['prodi_jenjang'] . ' - ' . $prodi['prodi_nama'] . '</option>';
    }
    $result .= '</select>';
    return $result;
}

function periode($default = "", $require = 'required')
{
    global $db;
    $sql = $db->prepare("SELECT akademik_id, akademik_nama FROM us_akademik ORDER BY akademik_nama DESC");
    $sql->execute();
    $result = '<select name="periode" class="form-control" ' . $require . '>';
    $result .= '<option value="">-- Periode --</option>';
    while ($periode = $sql->fetch(PDO::FETCH_ASSOC)) {
        $selected = ($default == $periode['akademik_id']) ? 'selected' : '';
        $result .= '<option value="' . $periode['akademik_id'] . '" ' . $selected . '>' . $periode['akademik_nama'] . '</option>';
    }
    $result .= '</select>';
    return $result;
}

function kurikulum($default = "", $require = 'required')
{
    global $db;
    $sql = $db->prepare("SELECT semester_id, semester_nama FROM us_semester ORDER BY semester_nama DESC");
    $sql->execute();
    $result = '<select name="kurikulum" class="form-control" ' . $require . '>';
    $result .= '<option value="">-- Kurikulum Semester --</option>';
    while ($kuri = $sql->fetch(PDO::FETCH_ASSOC)) {
        $selected = ($default == $kuri['semester_id']) ? 'selected' : '';
        $result .= '<option value="' . $kuri['semester_id'] . '" ' . $selected . '>' . $kuri['semester_nama'] . '</option>';
    }
    $result .= '</select>';
    return $result;
}

function dosen($default = "", $require = 'required')
{
    global $db;
    $sql = $db->prepare("SELECT dosen_uid, dosen_nama FROM us_dosen ORDER BY dosen_nama ASC");
    $sql->execute();
    $result = '<select name="dosen" class="form-control data-dosen" ' . $require . '>';
    $result .= '<option value="">-- Pilih Dosen --</option>';
    while ($dosen = $sql->fetch(PDO::FETCH_ASSOC)) {
        $selected = ($default == $dosen['dosen_uid']) ? 'selected' : '';
        $result .= '<option value="' . $dosen['dosen_uid'] . '"' . $selected . '>' . $dosen['dosen_nama'] . '</option>';
    }
    $result .= '</select>';
    return $result;
}

function semester($require = 'required')
{
    $result = '<select name="semester" class="form-control" ' . $require . '>';
    $result .= '<option value="">-- Semester --</option>';
    for ($i = 1; $i <= 8; $i++) {
        $result .= '<option value="' . $i . '">' . $i . '</option>';
    }
    $result .= '</select>';
    return $result;
}
