<?php
defined("RESMI") or die("error");
if ($csrf->check_valid('post')) {
    $gump  = new GUMP();
    $id    = $_POST['kontak_id'];
    $nama  = $_POST['kontak_nama'];
    $nomor = $_POST['kontak_nomor'];
    $pesan = $_POST['kontak_pesan'];
    $_POST = array(
        'nama' => $nama,
        'nomor' => $nomor,
        'pesan' => $pesan
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'nama' => 'required',
        'nomor' => 'required|numeric',
        'pesan' => 'required'
    ));
    $gump->filter_rules(array(
        'nama' => 'trim|sanitize_string',
        'nomor' => 'trim|sanitize_numbers',
        'pesan' => 'trim|sanitize_string'
    ));
    $ok = $gump->run($_POST);
    if ($ok == false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("UPDATE us_kontak SET kontak_nama = ?, kontak_nomor = ?, kontak_pesan = ? WHERE kontak_id = ?");
        $sql->bindParam(1, $nama);
        $sql->bindParam(2, $nomor);
        $sql->bindParam(3, $pesan);
        $sql->bindParam(4, $id);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Proses Berhasil',
                    text: 'Data kontak berhasil disimpan',
                    showConfirmButton: true,
                    timer: 3500
                }).then(function() {
                    window.location.href = "index.php?mod=kontak";
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
                            <meta http-equiv="refresh" content="10; url=index.php?mod=kontak">
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>