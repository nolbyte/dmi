<?php
defined("RESMI") or die("error");

use Ramsey\Uuid\Uuid;

if ($csrf->check_valid('post')) {
    $gump       = new GUMP();
    $uid        = Uuid::uuid4()->toString();
    $nama       = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $status     = $_POST['status'];
    $_POST = array(
        'nama'       => $nama,
        'keterangan' => $keterangan,
        'status'     => $status
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'nama'       => 'required',
        'keterangan' => 'required',
        'status'     => 'required|numeric'
    ));
    $gump->filter_rules(array(
        'nama'       => 'trim|sanitize_string',
        'keterangan' => 'trim|sanitize_string',
        'status'     => 'trim|sanitize_numbers'
    ));
    $ok = $gump->run($_POST);
    if ($ok === false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("INSERT INTO us_akademik SET akademik_id = ?, akademik_nama = ?, akademik_detail = ?, akademik_status = ?");
        $sql->bindParam(1, $uid);
        $sql->bindParam(2, $nama);
        $sql->bindParam(3, $keterangan);
        $sql->bindParam(4, $status);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Proses Berhasil',
                    text: 'Data tahuan ajaran berhasil disimpan',
                    showConfirmButton: true,
                    timer: 3500
                }).then(function() {
                    window.location.href = "index.php?mod=akademik";
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
                            <meta http-equiv="refresh" content="10; url=index.php?mod=akademik">
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>