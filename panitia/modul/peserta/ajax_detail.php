<?php
defined("RESMI") or die("error");
if (isset($_POST['siswa'])) {
    $siswa_uid = $_POST['siswa'];
    $sql = $db->prepare("SELECT * FROM us_pendaftar up LEFT JOIN us_jurusan uj ON up.siswa_jurusan=uj.jurusan_id LEFT JOIN us_periode upe ON up.siswa_gelombang=upe.periode_id LEFT JOIN us_ortu uo ON up.siswa_uid=uo.ot_siswa WHERE up.siswa_uid = :siswana");
    $sql->execute(array(':siswana' => $siswa_uid));
    $siswa = $sql->fetch(PDO::FETCH_ASSOC);
}
?>
<div class="d-flex">
    <div class="mb-2 me-2">
        <a target="_blank" href="../cetak/Bukti_Daftar.php?id=<?= $siswa['siswa_uid'] ?>" class="btn btn-sm btn-primary"><i class="bi-printer"></i> cetak bukti daftar</a>
    </div>
    <div class="mb-2 me-2">
        <?php
        $sql = $db->prepare("SELECT ot_siswa FROM us_ortu WHERE ot_siswa = :siswana");
        $sql->execute(array(':siswana' => $siswa['siswa_uid']));
        $wali = $sql->fetchColumn();
        if ($wali) {
        ?>
            <a target="_blank" href="../cetak/cetak_Form.php?id=<?= $siswa['siswa_uid'] ?>" class="btn btn-sm btn-primary"><i class="bi-printer"></i> cetak formulir</a>
        <?php
        } else {
            echo '<p class="text-info p-1">Peserta Belum Melengkapi Formulir Pendaftaran</p>';
        }
        ?>
    </div>
</div>
<table class="table table-bordered">
    <tr>
        <th class="bg-light p-2 me-3 w-25">Nomor Pendaftaran</th>
        <td class="p-2"><?= $siswa['siswa_no_daftar'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Tanggal Pendaftaran</th>
        <td class="p-2"><?= tgl_id($siswa['siswa_tgl_daftar']) ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Periode Pendaftaran</th>
        <td class="p-2"><?= $siswa['periode_nama'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Nama </th>
        <td class="p-2"><?= $siswa['siswa_nama'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Jenis Kelamin</th>
        <td class="p2"><?= $siswa['siswa_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Tempat lahir</th>
        <td class="p-2"><?= $siswa['siswa_tempatLahir'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Tanggal Lahir</th>
        <td class="p-2"><?= tgl_id($siswa['siswa_tglLahir']) ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Nomor Ponsel</th>
        <td class="p-2"><?= $siswa['siswa_hp'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Alamat Email</th>
        <td class="p-2"><?= $siswa['siswa_email'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Pilihan Jurusan</th>
        <td class="p-2"><?= $siswa['jurusan_nama'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">No. Ijazah SD</th>
        <td class="p-2"><?= $siswa['siswa_ijazah_sd'] ?></td>
    </tr>
    <tr>
        <th class="bg-light p-2 me-3">Survey PPDB</th>
        <td class="p-2">Informasi diperoleh dari: <?= $siswa['siswa_survey'] ?></td>
    </tr>
</table>