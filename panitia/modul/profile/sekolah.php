<?php
defined("RESMI") or die("error");
$sql = $db->prepare("SELECT * FROM us_sekolah");
$sql->execute();
$s = $sql->fetch(PDO::FETCH_ASSOC);
if ($csrf->check_valid('post')) {
    $gump = new GUMP();
    $id_sekolah = $_POST['id_sekolah'];
    $sekolah    = $_POST['sekolah'];
    $alamat     = $_POST['alamat'];
    $telepon    = $_POST['telepon'];
    $email      = $_POST['email'];
    $website    = $_POST['website'];

    $_POST = array(
        'id_sekolah' => $id_sekolah,
        'sekolah'    => $sekolah,
        'alamat'     => $alamat,
        'telepon'    => $telepon,
        'email'      => $email,
        'website'    => $website
    );
    $_POST = $gump->sanitize($_POST);
    $gump->validation_rules(array(
        'id_sekolah' => 'required|integer',
        'sekolah'    => 'required',
        'alamat'     => 'required',
        'telepon'    => 'required|numeric',
        'email'      => 'required|valid_email',
        'website'    => 'required|valid_url'
    ));

    $gump->filter_rules(array(
        'id_sekolah' => 'trim|sanitize_numbers',
        'sekolah'    => 'trim|sanitize_string',
        'alamat'     => 'trim|sanitize_string',
        'telepon'    => 'trim|sanitize_numbers',
        'email'      => 'trim|sanitize_string',
        'website'    => 'trim|sanitize_string'
    ));
    $ok = $gump->run($_POST);
    if ($ok === false) {
        $error[] = $gump->get_readable_errors(true);
    } else {
        $sql = $db->prepare("UPDATE us_sekolah SET sekolah_nama = ?, sekolah_alamat = ?, sekolah_telpon = ?, sekolah_email = ?, sekolah_website = ? WHERE sekolah_id = ?");
        $sql->bindParam(1, $sekolah);
        $sql->bindParam(2, $alamat);
        $sql->bindParam(3, $telepon);
        $sql->bindParam(4, $email);
        $sql->bindParam(5, $website);
        $sql->bindParam(6, $id_sekolah);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
        } else {
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Proses Berhasil',
                    text: 'Data sekolah berhasil disimpan',
                    showConfirmButton: true,
                    timer: 3500
                }).then(function() {
                    window.location.href = "index.php?mod=sekolah";
                })
            </script>
<?php
        }
    }
}
?>
<div class="row d-flex justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <form method="post" action="">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <input type="hidden" name="id_sekolah" value="<?= $s['sekolah_id']; ?>">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <i class="bi-building"></i> PROFILE SEKOLAH
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save"></i> simpan</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($error)) {
                        foreach ($error as $error) {
                    ?>
                            <div class="alert alert-danger">
                                <h4><i class="bi-exclamation-diamond"></i> Galat</h4>
                                <?php echo $error; ?>
                                <meta http-equiv="refresh" content="15">
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Sekolah</label>
                        <input type="hidden" name="id_sekolah" value="<?= $s['sekolah_id']; ?>">
                        <input type="text" name="sekolah" class="form-control" value="<?= $s['sekolah_nama']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Sekolah</label>
                        <textarea name="alamat" class="form-control" rows="4" required=""><?= nl2br($s['sekolah_alamat']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="number" name="telepon" class="form-control" value="<?= $s['sekolah_telpon']; ?>" required="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Sekolah</label>
                        <input type="text" name="email" class="form-control" value="<?= $s['sekolah_email']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website Sekolah</label>
                        <input type="text" name="website" class="form-control" value="<?= $s['sekolah_website']; ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>