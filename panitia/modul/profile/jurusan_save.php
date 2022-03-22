<?php
defined("RESMI") or die("error");
if ($csrf->check_valid('post')) {
    $gump       = new GUMP();
    $kode       = $_POST['kode'];
    $nama       = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $_POST = array(
        'kode'       => $kode,
        'nama'       => $nama,
        'keterangan' => $keterangan
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'kode' => 'required|numeric',
        'nama' => 'required'
    ));
    $gump->filter_rules(array(
        'kode'       => 'trim|sanitize_numbers',
        'nama'       => 'trim|sanitize_string',
        'keterangan' => 'trim|sanitize_string'
    ));
    $ok = $gump->run($_POST);
    if ($ok === false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("INSERT INTO us_jurusan SET jurusan_kd = ?, jurusan_nama = ?, jurusan_keterangan = ?");
        $sql->bindParam(1, $kode);
        $sql->bindParam(2, $nama);
        $sql->bindParam(3, $keterangan);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Proses Berhasil',
                    text: 'Data jurusan berhasil disimpan',
                    showConfirmButton: true,
                    timer: 3500
                }).then(function() {
                    window.location.href = "index.php?mod=jurusan";
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
                            <meta http-equiv="refresh" content="10; url=index.php?mod=jurusan">
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>