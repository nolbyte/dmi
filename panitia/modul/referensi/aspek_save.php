<?php
defined("RESMI") or die("error");
if ($csrf->check_valid('post')) {
    $gump = new GUMP();
    $nama = $_POST['nama'];
    $_POST = array(
        'nama' => $nama
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'nama' => 'required'
    ));
    $gump->filter_rules(array(
        'nama' => 'trim|sanitize_string'
    ));
    $ok = $gump->run($_POST);
    if ($ok == false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("INSERT INTO dm_aspek SET aspek_nama = ?");
        $sql->bindParam(1, $nama);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Proses Berhasil',
                    text: 'Aspek penilaian berhasil disimpan',
                    showConfirmButton: true,
                    timer: 1500
                }).then(function() {
                    window.location.href = "index.php?mod=aspek-nilai";
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
                            <meta http-equiv="refresh" content="6; url=index.php?mod=aspek_nilai">
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>