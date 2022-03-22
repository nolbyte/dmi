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
                                <i class="bi-gear-wide-connected"></i> PERIODE PENDAFTARAN
                            </div>
                            <div>
                                <a href="?mod=periode&act=add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> tambah data</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Tanggal Awal</th>
                                        <th>Tanggal Akhir</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Status</th>
                                        <th>Berbayar</th>
                                        <th>Biaya Formulir</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = $db->prepare("SELECT * FROM us_periode up LEFT JOIN us_akademik ua ON up.periode_tp=ua.akademik_id ORDER BY up.periode_kode ASC");
                                    $sql->execute();
                                    $no = 1;
                                    foreach ($sql->fetchAll() as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['periode_kode'] ?></td>
                                            <td><?= $row['periode_nama'] ?></td>
                                            <td><?= tgl_id($row['periode_tgl_awal']) ?></td>
                                            <td><?= tgl_id($row['periode_tgl_akhir']) ?></td>
                                            <td><?= $row['akademik_nama'] ?></td>
                                            <td><?= $row['periode_status'] > 0 ? 'Aktif' : 'Non Aktif' ?></td>
                                            <td><?= $row['periode_berbayar'] > 0 ? 'Ya' : 'Gratis' ?></td>
                                            <td><?= $row['periode_biaya'] ?></td>
                                            <td class="text-center">
                                                <a href="?mod=periode&act=edit&id=<?= $row['periode_id'] ?>" class="btn btn-xs btn-primary me-1"><i class="fa fa-edit"></i></a>
                                                <a href="" class="btn btn-danger btn-xs hapus-data" data-id="<?= $row['periode_id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
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
                        title: "<i class='bi-trash'></i> Hapus Data Gelombang !",
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
                                            url: 'post.php?mod=profile&hal=periode_delete',
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
    case 'add':
        if ($csrf->check_valid('post')) {
            $gump      = new GUMP();
            $kode      = $_POST['kode'];
            $nama      = $_POST['nama'];
            $akademik  = $_POST['akademik'];
            $tgl_awal  = $_POST['tgl_awal'];
            $tgl_akhir = $_POST['tgl_akhir'];
            $status    = $_POST['status'];
            $berbayar  = $_POST['berbayar'];
            $biaya     = $_POST['biaya'];

            $_POST = array(
                'kode'      => $kode,
                'nama'      => $nama,
                'akademik'  => $akademik,
                'tgl_awal'  => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'status'    => $status,
                'berbayar'  => $berbayar,
                'biaya'     => $biaya
            );
            $_POST = $gump->sanitize($_POST);
            $gump->validation_rules(array(
                'kode'      => 'required|numeric',
                'nama'      => 'required',
                'akademik'  => 'required',
                'tgl_awal'  => 'required',
                'tgl_akhir' => 'required',
                'status'    => 'required|numeric',
                'berbayar'  => 'required|numeric',
                'biaya'     => 'required|numeric'
            ));
            $gump->filter_rules(array(
                'kode'      => 'trim|sanitize_numbers',
                'nama'      => 'trim|sanitize_string',
                'akademik'  => 'trim|sanitize_string',
                'tgl_awal'  => 'trim|sanitize_string',
                'tgl_akhir' => 'trim|sanitize_string',
                'status'    => 'trim|sanitize_numbers',
                'berbayar'  => 'trim|sanitize_numbers',
                'biaya'     => 'trim|sanitize_numbers'
            ));
            $ok = $gump->run($_POST);
            if ($ok == false) {
                $error[] = $gump->get_readable_errors(true);
            } else {
                $sql = $db->prepare("INSERT INTO us_periode SET periode_kode = ?, periode_nama = ?, periode_tp = ?, periode_tgl_awal = ?, periode_tgl_akhir = ?, periode_status = ?, periode_berbayar = ?, periode_biaya = ?");
                $sql->bindParam(1, $kode);
                $sql->bindParam(2, $nama);
                $sql->bindParam(3, $akademik);
                $sql->bindParam(4, $tgl_awal);
                $sql->bindParam(5, $tgl_akhir);
                $sql->bindParam(6, $status);
                $sql->bindParam(7, $berbayar);
                $sql->bindParam(8, $biaya);
                if (!$sql->execute()) {
                    print_r($sql->errorInfo());
                } else {
        ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Proses Berhasil',
                            text: 'Data gelombang berhasil disimpan',
                            showConfirmButton: true,
                            timer: 3500
                        }).then(function() {
                            window.location.href = "index.php?mod=periode";
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
                    <form method="post" action="">
                        <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="flex-grow-1 p-2">
                                    <i class="bi-gear-wide-connected"></i> TAMBAH PERIODE PENDAFTARAN
                                </div>
                                <div>
                                    <a href="?mod=periode" class="btn btn-sm btn-primary me-2"><i class="fa fa-list"></i> daftar</a>
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
                                        <meta http-equiv="refresh" content="10; url=index.php?mod=periode">
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="mb-3">
                                <label for="" class="form-label">Kode Gelombang</label>
                                <input type="number" class="form-control" name="kode" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Gelombang</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tahun Pelajaran</label>
                                <select name="akademik" class="form-select" required>
                                    <option value="">--Tahun Ajaran--</option>
                                    <?php
                                    $sql = $db->prepare("SELECT * FROM us_akademik ORDER BY akademik_nama DESC");
                                    $sql->execute();
                                    foreach ($sql->fetchAll() as $row) {
                                        echo '<option value="' . $row['akademik_id'] . '">' . $row['akademik_nama'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" name="tgl_awal" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" name="tgl_akhir" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="0">Non Aktif</option>
                                    <option value="1">Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Formulir Berbayar?</label>
                                <select name="berbayar" class="form-select" required>
                                    <option value="0">Gratis</option>
                                    <option value="1">Berbayar</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Biaya Formulir</label>
                                <input type="number" class="form-control" name="biaya" value="0" required>
                                <p class="form-text">Isi dengan angka 0 jika gratis</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        break;
    case 'edit':
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $sql = $db->prepare("SELECT * FROM us_periode up LEFT JOIN us_akademik ua ON up.periode_tp=ua.akademik_id WHERE up.periode_id = :idna");
        $sql->execute(array(':idna' => $id));
        $edit = $sql->fetch(PDO::FETCH_ASSOC);
        if ($csrf->check_valid('post')) {
            $gump      = new GUMP();
            $id        = $_POST['id'];
            $kode      = $_POST['kode'];
            $nama      = $_POST['nama'];
            $akademik  = $_POST['akademik'];
            $tgl_awal  = $_POST['tgl_awal'];
            $tgl_akhir = $_POST['tgl_akhir'];
            $status    = $_POST['status'];
            $berbayar  = $_POST['berbayar'];
            $biaya     = $_POST['biaya'];

            $_POST = array(
                'kode'      => $kode,
                'nama'      => $nama,
                'akademik'  => $akademik,
                'tgl_awal'  => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'status'    => $status,
                'berbayar'  => $berbayar,
                'biaya'     => $biaya
            );
            $_POST = $gump->sanitize($_POST);
            $gump->validation_rules(array(
                'kode'      => 'required|numeric',
                'nama'      => 'required',
                'akademik'  => 'required',
                'tgl_awal'  => 'required',
                'tgl_akhir' => 'required',
                'status'    => 'required|numeric',
                'berbayar'  => 'required|numeric',
                'biaya'     => 'required|numeric'
            ));
            $gump->filter_rules(array(
                'kode'      => 'trim|sanitize_numbers',
                'nama'      => 'trim|sanitize_string',
                'akademik'  => 'trim|sanitize_string',
                'tgl_awal'  => 'trim|sanitize_string',
                'tgl_akhir' => 'trim|sanitize_string',
                'status'    => 'trim|sanitize_numbers',
                'berbayar'  => 'trim|sanitize_numbers',
                'biaya'     => 'trim|sanitize_numbers'
            ));
            $ok = $gump->run($_POST);
            if ($ok == false) {
                $error[] = $gump->get_readable_errors(true);
            } else {
                $sql = $db->prepare("UPDATE us_periode SET periode_kode = ?, periode_nama = ?, periode_tp = ?, periode_tgl_awal = ?, periode_tgl_akhir = ?, periode_status = ?, periode_berbayar = ?, periode_biaya = ? WHERE periode_id = ?");
                $sql->bindParam(1, $kode);
                $sql->bindParam(2, $nama);
                $sql->bindParam(3, $akademik);
                $sql->bindParam(4, $tgl_awal);
                $sql->bindParam(5, $tgl_akhir);
                $sql->bindParam(6, $status);
                $sql->bindParam(7, $berbayar);
                $sql->bindParam(8, $biaya);
                $sql->bindParam(9, $id);
                if (!$sql->execute()) {
                    print_r($sql->errorInfo());
                } else {
        ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Proses Berhasil',
                            text: 'Data gelombang berhasil disimpan',
                            showConfirmButton: true,
                            timer: 3500
                        }).then(function() {
                            window.location.href = "index.php?mod=periode";
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
                    <form method="post" action="">
                        <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                        <input type="hidden" name="id" value="<?= $edit['periode_id'] ?>">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="flex-grow-1 p-2">
                                    <i class="bi-gear-wide-connected"></i> TAMBAH PERIODE PENDAFTARAN
                                </div>
                                <div>
                                    <a href="?mod=periode" class="btn btn-sm btn-primary me-2"><i class="fa fa-list"></i> daftar</a>
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
                                        <meta http-equiv="refresh" content="10; url=index.php?mod=periode">
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="mb-3">
                                <label for="" class="form-label">Kode Gelombang</label>
                                <input type="number" class="form-control" name="kode" value="<?= $edit['periode_kode'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Gelombang</label>
                                <input type="text" name="nama" class="form-control" value="<?= $edit['periode_nama'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tahun Pelajaran</label>
                                <select name="akademik" class="form-select" required>
                                    <option value="<?= $edit['periode_tp'] ?>"><?= $edit['akademik_nama'] ?></option>
                                    <?php
                                    $sql = $db->prepare("SELECT * FROM us_akademik ORDER BY akademik_nama DESC");
                                    $sql->execute();
                                    foreach ($sql->fetchAll() as $row) {
                                        echo '<option value="' . $row['akademik_id'] . '">' . $row['akademik_nama'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" name="tgl_awal" value="<?= $edit['periode_tgl_awal'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" name="tgl_akhir" value="<?= $edit['periode_tgl_akhir'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="<?= $edit['periode_status'] ?>"><?= $edit['periode_status'] > 0 ? 'Aktif' : 'Non Aktif' ?></option>
                                    <option value="0">Non Aktif</option>
                                    <option value="1">Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Formulir Berbayar?</label>
                                <select name="berbayar" class="form-select" required>
                                    <option value="<?= $edit['periode_berbayar'] ?>"><?= $edit['periode_berbayar'] > 0 ? 'Berbayar' : 'Gratis' ?></option>
                                    <option value="0">Gratis</option>
                                    <option value="1">Berbayar</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Biaya Formulir</label>
                                <input type="number" class="form-control" name="biaya" value="<?= $edit['periode_biaya'] ?>" value="0" required>
                                <p class="form-text">Isi dengan angka 0 jika gratis</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
        break;
}
?>