<?php
defined("RESMI") or die("error");
$act = isset($_GET['act']) ? $_GET['act'] : '';
switch ($act) {
    default:
?>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2">
                                <i class="bi-newspaper"></i> BERITA/PENGUMUMAN
                            </div>
                            <div>
                                <a href="?mod=berita&act=add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> tambah data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            $sql = $db->prepare("SELECT * FROM us_berita ORDER BY berita_id ASC");
                            $sql->execute();
                            $no = 1;
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Berita</th>
                                        <th>Isi Berita</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Tampil</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sql->fetchAll() as $b) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $b['berita_judul']; ?></td>
                                            <td><?= substr($b['berita_isi'], 0, 50); ?>[...]</td>
                                            <td><?= tgl_id($b['berita_tgl']); ?></td>
                                            <td>
                                                <?php
                                                if ($b['berita_tampil'] === '1') {
                                                    echo "Ya";
                                                } else {
                                                    echo "Tidak";
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="?mod=berita&act=edit&id=<?= $b['berita_id']; ?>" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                                <a href="" class="btn btn-xs btn-danger hapus-data" data-id="<?= $b['berita_id']; ?>" aria-hidden="true"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('.hapus-data').click(function(e) {
                    e.preventDefault();
                    var empid = $(this).attr('data-id');
                    var parent = $(this).parent("td").parent("tr");
                    bootbox.dialog({
                        message: "Yakin akan menghapus data ini ?",
                        title: "<i class='bi-trash'></i> Hapus Berita !",
                        buttons: {
                            success: {
                                label: "<i class='bi-arrow-clockwise'></i> Tidak",
                                className: "btn-outline-success",
                                callback: function() {
                                    $('.bootbox').modal('hide');
                                }
                            },
                            danger: {
                                label: "<i class='bi-trash'></i> Hapus",
                                className: "btn-danger",
                                callback: function() {
                                    $.ajax({
                                            type: 'POST',
                                            url: 'post.php?mod=profile&hal=berita_delete',
                                            data: 'empid=' + empid
                                        })
                                        .done(function(response) {
                                            bootbox.alert(response);
                                            parent.fadeOut('slow');
                                        })
                                        .fail(function() {
                                            bootbox.alert('Error....');
                                        })
                                }
                            }
                        }
                    });
                });
            });
        </script>
        <?php
        break;
    case 'edit':
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $sql = $db->prepare("SELECT * FROM us_berita WHERE berita_id = :idna");
        $sql->execute(array(':idna' => $id));
        $be = $sql->fetch(PDO::FETCH_ASSOC);
        if ($csrf->check_valid('post')) {
            $gump = new GUMP();
            $idna       = $_POST['idna'];
            $tanggal    = $_POST['tanggal'];
            $judul      = $_POST['judul'];
            $isi_berita = $_POST['isi_berita'];
            $tampil     = $_POST['tampil'];

            $_POST = array(
                'idna'       => $idna,
                'tanggal'    => $tanggal,
                'judul'      => $judul,
                'isi_berita' => $isi_berita,
                'tampil'     => $tampil
            );
            $_POST = $gump->sanitize($_POST);

            $gump->validation_rules(array(
                'idna'       => 'required',
                'tanggal'    => 'required',
                'judul'      => 'required',
                'isi_berita' => 'required',
                'tampil'     => 'required'
            ));
            $gump->filter_rules(array(
                'idna'       => 'trim|sanitize_numbers',
                'tanggal'    => 'trim|sanitize_string',
                'judul'      => 'trim|sanitize_string',
                'isi_berita' => 'trim|sanitize_string',
                'tampil'     => 'trim|sanitize_numbers'
            ));

            $ok = $gump->run($_POST);
            if ($ok === false) {
                $error[] = $gump->get_readable_errors(true);
            } else {
                $sql = $db->prepare("UPDATE us_berita SET berita_judul = ?, berita_isi = ?, berita_tgl = ?, berita_tampil = ? WHERE berita_id = ?");
                $sql->bindParam(1, $judul);
                $sql->bindParam(2, $isi_berita);
                $sql->bindParam(3, $tanggal);
                $sql->bindParam(4, $tampil);
                $sql->bindParam(5, $idna);
                if (!$sql->execute()) {
                    print_r($sql->errorInfo());
                } else {
        ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Proses Berhasil',
                            text: 'Data Berita berhasil disimpan',
                            showConfirmButton: true,
                            timer: 3500
                        }).then(function() {
                            window.location.href = "index.php?mod=berita";
                        })
                    </script>
        <?php
                }
            }
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <form method="post" action="">
                        <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="flex-grow-1 p-2">
                                    <i class="bi-newspaper"></i> UBAH BERITA/PENGUMUMAN
                                </div>
                                <div>
                                    <a href="?mod=berita" class="btn btn-sm btn-primary me-2"><i class="fa fa-list"></i> daftar</a>
                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi-save"></i> simpan</button>
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
                                        <meta http-equiv="refresh" content="10; url=index.php?mod=berita">
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="mb-3">
                                <label class="form-label">Judul Berita</label>
                                <input type="hidden" name="idna" value="<?= $be['berita_id']; ?>">
                                <input type="hidden" name="tanggal" value="<?= $be['berita_tgl']; ?>">
                                <input type="text" name="judul" value="<?= $be['berita_judul']; ?>" class="form-control" required="">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Isi Berita</label>
                                <textarea name="isi_berita" class="textarea"><?= $be['berita_isi']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <?php if ($be['berita_tampil'] == '1') { ?>
                                    <input class="form-check-input" type="hidden" name="tampil" value="0">
                                    <input class="form-check-input" type="checkbox" name="tampil" value="<?= $be['berita_tampil']; ?>" checked>
                                <?php } else { ?>
                                    <input class="form-check-input" type="hidden" name="tampil" value="0">
                                    <input class="form-check-input" type="checkbox" name="tampil" value="1">
                                <?php } ?>
                                <label class="form-check-label">Tampilkan?</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
}
