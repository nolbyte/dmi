<?php defined("RESMI") or die("error");
if ($csrf->check_valid('post')) {
    $gump       = new GUMP();
    $id         = $_POST['jurusan_id'];
    $kode       = $_POST['jurusan_kd'];
    $nama       = $_POST['jurusan_nama'];
    $keterangan = $_POST['jurusan_keterangan'];

    $_POST = array(
        'id'         => $id,
        'kode'       => $kode,
        'nama'       => $nama,
        'keterangan' => $keterangan
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'id'   => 'required|numeric',
        'kode' => 'required|numeric',
        'nama' => 'required'
    ));
    $gump->filter_rules(array(
        'id'         => 'trim|sanitize_numbers',
        'kode'       => 'trim|sanitize_numbers',
        'nama'       => 'trim|sanitize_string',
        'keterangan' => 'trim|sanitize_string'
    ));
    $ok = $gump->run($_POST);
    if ($ok === false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("UPDATE us_jurusan SET jurusan_kd = ?, jurusan_nama = ?, jurusan_keterangan = ? WHERE jurusan_id = ?");
        $sql->bindParam(1, $kode);
        $sql->bindParam(2, $nama);
        $sql->bindParam(3, $keterangan);
        $sql->bindParam(4, $id);
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