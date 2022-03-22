<?php
defined("RESMI") or die("error");
if ($csrf->check_valid('post')) {
    $gump       = new GUMP();
    $nama       = $_POST['nama'];
    $poin       = $_POST['poin'];
    $keterangan = $_POST['keterangan'];
    $_POST = array(
        'nama'       => $nama,
        'poin'       => $poin,
        'keterangan' => $keterangan
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'nama'       => 'required',
        'poin'       => 'required|numeric',
        'keterangan' => 'required'
    ));
    $gump->filter_rules(array(
        'nama'       => 'trim|sanitize_string',
        'poin'       => 'trim|sanitize_numbers',
        'keterangan' => 'trim|sanitize_string'
    ));
    $ok = $gump->run($_POST);
    if ($ok == false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("INSERT INTO dm_kriteria SET kriteria_nama = ?, kriteria_poin = ?, kriteria_keterangan = ?");
        $sql->bindParam(1, $nama);
        $sql->bindParam(2, $poin);
        $sql->bindParam(3, $keterangan);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Proses Berhasil',
                    text: 'Kriteria Penilaian berhasil disimpan',
                    showConfirmButton: true,
                    timer: 1500
                }).then(function() {
                    window.location.href = "index.php?mod=kriteria-nilai";
                })
            </script>
<?php
        }
    }
}
?>
<div class="row d-flex justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <?php
                if (isset($error)) {
                    foreach ($error as $error) {
                ?>
                        <div class="alert alert-danger">
                            <h4><i class="bi-exclamation-diamond"></i> Galat</h4>
                            <?php echo $error; ?>
                            <meta http-equiv="refresh" content="6; url=index.php?mod=kriteria-nilai">
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>