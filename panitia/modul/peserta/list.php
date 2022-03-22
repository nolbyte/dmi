<?php
defined("RESMI") or die("error");
$act = isset($_GET['act']) ? $_GET['act'] : '';
switch ($act) {
    default:
?>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header p-2">
                        <i class="bi-person-lines-fill"></i> Data Pendaftar
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Jurusan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $status = 1;
                                    $sql = $db->prepare("SELECT * FROM us_pendaftar up LEFT JOIN us_jurusan uj ON up.siswa_jurusan=uj.jurusan_id LEFT JOIN us_periode upe ON up.siswa_gelombang=upe.periode_id LEFT JOIN us_ortu uo ON up.siswa_uid=uo.ot_siswa WHERE upe.periode_status = :statusna ORDER BY siswa_tgl_daftar DESC, siswa_nama ASC");
                                    $sql->execute(array(':statusna' => $status));
                                    $no = 1;
                                    foreach ($sql->fetchAll() as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row['siswa_nama'] ?></td>
                                            <td><?= $row['siswa_no_daftar'] ?></td>
                                            <td><?= tgl_id($row['siswa_tgl_daftar']) ?></td>
                                            <td><?= $row['jurusan_nama'] ?></td>
                                            <td>
                                                <a href="" data-id="<?= $row['siswa_uid'] ?>" data-bs-toggle="modal" data-bs-target="#detail" class="btn btn-sm btn-primary me-1 btnView"><i class="bi-search"></i></a>
                                                <a href="#" class="btn btn-xs btn-danger hapus-peserta" data-siswa="<?= $row['siswa_uid'] ?>"><i class="bi-x fs-5"></i>
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
        <!-- Modal -->
        <div class="modal fade" id="detail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Peserta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <i class="fa fa-refresh fa-spin loading" style="display: none;"></i> Sedang mengambil data
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('.btnView').click(function() {
                    var siswaId = $(this).data('id');
                    $('.loading').show();
                    //var data = 'makul='+ encodeURIComponent(makulId)  & 'semester='+ encodeURIComponent(smtId);
                    //var data = 'makulId='+ makulId+'&smtId='+ smtId;
                    $.ajax({
                        url: 'post.php?mod=peserta&hal=ajax_detail',
                        type: 'post',
                        data: {
                            siswa: siswaId
                        },
                        success: function(data) {
                            $('.modal-body').html(data);
                            $('#detail').modal('show');
                            $('.loading').hide();
                            if (!data) {
                                alert('kosong');
                                return;
                            }
                        }

                    });
                });
                $('.hapus-peserta').click(function(e) {
                    e.preventDefault();
                    var empid = $(this).attr('data-siswa');
                    var parent = $(this).parent("td").parent("tr");
                    bootbox.dialog({
                        message: "Yakin akan menghapus data ini ?",
                        title: "<i class='bi-trash'></i> Hapus Data Peserta !",
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
                                            url: 'post.php?mod=peserta&hal=ajax_del_peserta',
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
    case 'view':
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $sql = $db->prepare("SELECT * FROM us_pendaftar up LEFT JOIN us_jurusan uj ON up.siswa_jurusan=uj.jurusan_id LEFT JOIN us_periode upe ON up.siswa_gelombang=upe.periode_id LEFT JOIN us_ortu uo ON up.siswa_uid=uo.ot_siswa WHERE up.siswa_uid = :siswana");
        $sql->execute(array(':siswana' => $id));
        $siswa = $sql->fetch(PDO::FETCH_ASSOC);
    ?>
        <div class="row d-flex justify-content-center">
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2"><i class="bi-clipboard-check"></i> KETERANGAN PENDAFTARAN</div>
                            <div class="p-2"><a href="?mod=peserta" class="btn btn-sm btn-primary"><i class="bi-list-ul"></i> daftar peserta</a></div>
                        </div>
                    </div>
                    <div class="card-body">
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
                        <div class="table-responsive">
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
                                    <th class="bg-light p-2 me-3">Survey PPDB</th>
                                    <td class="p-2">Informasi diperoleh dari: <?= $siswa['siswa_survey'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
    case 'pribadi':
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $sql = $db->prepare("SELECT * FROM us_pendaftar up JOIN us_jurusan uj ON up.siswa_jurusan=uj.jurusan_id WHERE siswa_uid = :id");
        $sql->execute(array(':id' => $id));
        $ed = $sql->fetch(PDO::FETCH_ASSOC);

        if ($csrf->check_valid('post')) {
            $gump = new GUMP();
            $siswa_id        = $_POST['siswa_id'];
            $nama            = $_POST['nama'];
            $jurusan         = $_POST['jurusan'];
            $jenis_kelamin   = $_POST['jenis_kelamin'];
            $nisn            = $_POST['nisn'];
            $nik             = $_POST['nik'];
            $kk              = $_POST['kk'];
            $tmpt_lahir      = $_POST['tmpt_lahir'];
            $tgllahir        = $_POST['tgllahir'];
            $no_akta         = $_POST['no_akta'];
            $agama           = $_POST['agama'];
            $warganegara     = $_POST['warganegara'];
            $kebutuhan       = $_POST['kebutuhan'];
            $jalan           = $_POST['jalan'];
            $rt              = $_POST['rt'];
            $rw              = $_POST['rw'];
            $dusun           = $_POST['dusun'];
            $kelurahan       = $_POST['kelurahan'];
            $kecamatan       = $_POST['kecamatan'];
            $kode_pos        = $_POST['kode_pos'];
            $lintang         = $_POST['lintang'];
            $bujur           = $_POST['bujur'];
            $tinggal         = $_POST['tinggal'];
            $transportasi    = $_POST['transportasi'];
            $anak_ke         = $_POST['anak_ke'];
            $kip             = $_POST['kip'];
            $status_kip      = $_POST['status_kip'];
            $alasan_kip      = $_POST['alasan_kip'];
            $kesejahteraan   = $_POST['kesejahteraan'];
            $no_kartu        = $_POST['no_kartu'];
            $nama_kartu      = $_POST['nama_kartu'];
            $telp            = $_POST['telp'];
            $hp              = $_POST['hp'];

            $_POST = $gump->sanitize($_POST);
            $gump->validation_rules(array(
                'siswa_id'        => 'required',
                'nama'            => 'required',
                'jurusan'         => 'required',
                'jenis_kelamin'   => 'required',
                'nisn'            => 'required|numeric|exact_len,10',
                'nik'             => 'required|numeric|exact_len,16',
                'kk'              => 'required|numeric|exact_len,16',
                'tmpt_lahir'      => 'required',
                'tgllahir'        => 'required',
                'no_akta'         => 'required',
                'agama'           => 'required',
                'warganegara'     => 'required',
                'kebutuhan'       => 'required',
                'jalan'           => 'required',
                'rt'              => 'required|numeric',
                'rw'              => 'required|numeric',
                'dusun'           => 'required',
                'kelurahan'       => 'required',
                'kecamatan'       => 'required',
                'kode_pos'        => 'required|numeric',
                'lintang'         => 'required',
                'bujur'           => 'required',
                'tinggal'         => 'required',
                'transportasi'    => 'required',
                'anak_ke'         => 'required|numeric',
                'kip'             => 'required',
                'status_kip'      => 'required',
                'alasan_kip'      => 'required',
                'telp'            => 'required',
                'hp'              => 'required|numeric'
            ));
            $gump->filter_rules(array(
                'siswa_id'        => 'trim|sanitize_numbers',
                'nama'            => 'trim|sanitize_string',
                'jurusan'         => 'trim|sanitize_string',
                'jenis_kelamin'   => 'trim|sanitize_string',
                'nisn'            => 'trim|sanitize_numbers',
                'nik'             => 'trim|sanitize_numbers',
                'kk'              => 'trim|sanitize_numbers',
                'tmpt_lahir'      => 'trim|sanitize_string',
                'tgllahir'        => 'trim|sanitize_string',
                'no_akta'         => 'trim|sanitize_string',
                'agama'           => 'trim|sanitize_string',
                'warganegara'     => 'trim|sanitize_string',
                'kebutuhan'       => 'trim|sanitize_string',
                'jalan'           => 'trim|sanitize_string',
                'rt'              => 'trim|sanitize_numbers',
                'rw'              => 'trim|sanitize_numbers',
                'dusun'           => 'trim|sanitize_string',
                'kelurahan'       => 'trim|sanitize_string',
                'kecamatan'       => 'trim|sanitize_string',
                'kode_pos'        => 'trim|sanitize_numbers',
                'lintang'         => 'trim|sanitize_string',
                'bujur'           => 'trim|sanitize_string',
                'tinggal'         => 'trim|sanitize_string',
                'transportasi'    => 'trim|sanitize_string',
                'anak_ke'         => 'trim|sanitize_numbers',
                'kip'             => 'trim|sanitize_string',
                'status_kip'      => 'trim|sanitize_string',
                'alasan_kip'      => 'trim|sanitize_string',
                'kesejahteraan'   => 'trim|sanitize_string',
                'no_kartu'        => 'trim|sanitize_string',
                'nama_kartu'      => 'trim|sanitize_string',
                'telp'            => 'trim|sanitize_string',
                'hp'              => 'trim|sanitize_numbers'
            ));
            $bisa = $gump->run($_POST);
            if ($bisa === false) {
                $error[] = $gump->get_readable_errors(true);
            } else {
                $sqlp = $db->prepare("UPDATE us_pendaftar SET siswa_jurusan = ?, siswa_nama = ?, siswa_kelamin = ?, siswa_nisn = ?, siswa_nik = ?, siswa_noKK = ?, siswa_tempatLahir = ?, siswa_tglLahir = ?, siswa_noAktaLahir = ?, siswa_agama = ?, siswa_kewarganegaraan = ?, siswa_kebutuhan = ?, siswa_alamat_jln = ?, siswa_rt = ?, siswa_rw = ?, siswa_dusun = ?, siswa_kelurahan = ?, siswa_kecamatan = ?, siswa_kode_pos = ?, siswa_lintang = ?, siswa_bujur = ?, siswa_tinggal = ?, siswa_transport = ?, siswa_anak_ke = ?, siswa_kip = ?, siswa_status_kip = ?, siswa_alasan_kip = ?, siswa_krt_sejahtera = ?, siswa_krt_no = ?, siswa_krt_nama = ?, siswa_telp_rmh = ?, siswa_hp = ? WHERE siswa_uid = ?");
                $sqlp->bindParam(1, $jurusan);
                $sqlp->bindParam(2, $nama);
                $sqlp->bindParam(3, $jenis_kelamin);
                $sqlp->bindParam(4, $nisn);
                $sqlp->bindParam(5, $nik);
                $sqlp->bindParam(6, $kk);
                $sqlp->bindParam(7, $tmpt_lahir);
                $sqlp->bindParam(8, $tgllahir);
                $sqlp->bindParam(9, $no_akta);
                $sqlp->bindParam(10, $agama);
                $sqlp->bindParam(11, $warganegara);
                $sqlp->bindParam(12, $kebutuhan);
                $sqlp->bindParam(13, $jalan);
                $sqlp->bindParam(14, $rt);
                $sqlp->bindParam(15, $rw);
                $sqlp->bindParam(16, $dusun);
                $sqlp->bindParam(17, $kelurahan);
                $sqlp->bindParam(18, $kecamatan);
                $sqlp->bindParam(19, $kode_pos);
                $sqlp->bindParam(20, $lintang);
                $sqlp->bindParam(21, $bujur);
                $sqlp->bindParam(22, $tinggal);
                $sqlp->bindParam(23, $transportasi);
                $sqlp->bindParam(24, $anak_ke);
                $sqlp->bindParam(25, $kip);
                $sqlp->bindParam(26, $status_kip);
                $sqlp->bindParam(27, $alasan_kip);
                $sqlp->bindParam(28, $kesejahteraan);
                $sqlp->bindParam(29, $no_kartu);
                $sqlp->bindParam(30, $nama_kartu);
                $sqlp->bindParam(31, $telp);
                $sqlp->bindParam(32, $hp);
                $sqlp->bindParam(33, $siswa_id);
                if (!$sqlp->execute()) {
                    print_r($sqlp->errorInfo());
                } else {
        ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Proses Berhasil',
                            text: 'Data pribadi berhasil disimpan',
                            showConfirmButton: true,
                            timer: 3500
                        }).then(function() {
                            window.location.href = "index.php?mod=data-pribadi";
                        })
                    </script>
        <?php
                }
            }
        }
        ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <i class="bi-person-fill"></i> FORMULIR PENDAFTAR
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="?mod=peserta&act=pribadi&id=<?= $ed['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Pribadi</a>
                            <a href="?mod=peserta&act=wali&id=<?= $ed['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Wali</a>
                            <a href="?mod=peserta&act=periodik&id=<?= $ed['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Periodik</a>
                            <a href="?mod=peserta&act=prestasi&id=<?= $ed['siswa_uid'] ?>i" class="list-group-item list-group-item-action">Data Prestasi</a>
                            <a href="?mod=peserta&act=beasiswa&id=<?= $ed['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Beasiswa</a>
                            <a href="?mod=peserta&act=sekolah&id=<?= $ed['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Sekolah Asal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2"><i class="bi-clipboard-check"></i> KETERANGAN PENDAFTARAN</div>
                            <div class="P-2"><a href="?mod=peserta&act=view&id=<?= $ed['siswa_uid'] ?>" class="btn btn-sm btn-warning me-1"><i class="bi-arrow-clockwise"></i> kembali</a><button type="submit" class="btn btn-sm btn-primary"><i class="bi-save"></i> simpan data</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($error)) {
                            foreach ($error as $error) {
                        ?>
                                <div class="alert alert-danger">
                                    <h4><i class="icon fa fa-ban"></i> Galat</h4>
                                    <?php echo $error; ?>
                                    <meta http-equiv="refresh" content="15">
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="alert alert-warning mb-3">Mohon lengkapi data-data berikut dengan jelas dan benar</div>
                        <div class="mb-3">
                            <label for="" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $ed['siswa_nama']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Pilihan Jurusan</label>
                            <?php
                            $d = $db->prepare("SELECT * FROM us_jurusan ORDER BY jurusan_id ASC");
                            $d->execute();
                            ?>
                            <select name="jurusan" class="form-select">
                                <option value="<?= $ed['jurusan_id']; ?>" selected><?= $ed['jurusan_nama']; ?></option>
                                <option value="">-- Pilih Jurusan --</option>
                                <?php foreach ($d->fetchAll() as $row) { ?>
                                    <option value="<?= $row['jurusan_id']; ?>"><?= $row['jurusan_nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Jenis Kelamin</label><br>
                            <?php
                            if ($ed['siswa_kelamin'] == "L") { ?>
                                <label class="radio-inline"><input type="radio" name="jenis_kelamin" value="<?= $ed['siswa_kelamin']; ?>" checked> Laki-laki</label> &nbsp;<label class="radio-inline"><input type="radio" name="jenis_kelamin" value="P"> Perempuan</label>
                            <?php } else { ?>
                                <label class="radio-inline"><input type="radio" name="jenis_kelamin" value="L"> Laki-laki</label> &nbsp;<label class="radio-inline"><input type="radio" name="jenis_kelamin" value="<?= $ed['siswa_kelamin']; ?>" checked> Perempuan</label>
                            <?php } ?>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">NISN</label>
                            <input type="number" name="nisn" class="form-control" value="<?= $ed['siswa_nisn']; ?>" required>
                            <p class="form-text">10 digit NISN - <span class="text-danger">* Wajib diisi</span></p>
                            <p class="form-text">Periksa NISN di tautan <a target="_blank" href="https://nisn.data.kemdikbud.go.id/index.php/Cindex/formcaribynama">ini</a> </p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">NIK - Nomor Induk Kependudukan</label>
                            <input type="number" name="nik" class="form-control" value="<?= $ed['siswa_nik']; ?>" required>
                            <p class="form-text">16 Digit NIK - <span class="text-danger">* Wajib diisi</span></p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">No Kartu Keluarga</label>
                            <input type="number" name="kk" class="form-control" value="<?= $ed['siswa_noKK']; ?>" required>
                            <p class="form-text"><span class="text-danger">* Wajib diisi</span></p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Tempat Lahir</label>
                            <input type="text" name="tmpt_lahir" class="form-control" value="<?= $ed['siswa_tempatLahir']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgllahir" class="form-control" value="<?= $ed['siswa_tglLahir']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">
                                No Registrasi Akta Lahir
                            </label>
                            <input type="text" name="no_akta" class="form-control" value="<?= $ed['siswa_noAktaLahir']; ?>" required>
                            <p class="form-text">Isi dengan angka 0 jika tidak ada</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">
                                Agama dan Kepercayaan
                            </label>
                            <select name="agama" class="form-select" required>
                                <option value="<?= $ed['siswa_agama']; ?>" selected><?= $ed['siswa_agama']; ?></option>
                                <option value="">--Pilh Agama & Kepercayaan--</option>
                                <?php
                                $sqlag = $db->prepare("SELECT agama_kd, agama_nama FROM us_agama ORDER BY agama_id ASC");
                                $sqlag->execute();
                                foreach ($sqlag->fetchAll() as $row) { ?>
                                    <option value="<?= $row['agama_kd'] . ')' . $row['agama_nama']; ?>"><?= $row['agama_kd'] . ')' . $row['agama_nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kewargamegaraan</label>
                            <select name="warganegara" class="form-select" required>
                                <option value="<?= $ed['siswa_kewarganegaraan']; ?>" selected><?= $ed['siswa_kewarganegaraan']; ?></option>
                                <option value="">--Pilih Kewarganegaraan--</option>
                                <option value="Indonesia (WNI)">Indonesia (WNI)</option>
                                <option value="Asing (WNA)">Asing (WNA)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Berkebutuhan Khusus</label>
                            <select name="kebutuhan" class="form-select" required>
                                <option value="<?= $ed['siswa_kebutuhan']; ?>" selected><?= $ed['siswa_kebutuhan']; ?></option>
                                <option value="">--Pilih Jenis Kebutuhan Khusus</option>
                                <?php
                                $sqlk = $db->prepare("SELECT kebutuhan_kd, kebutuhan_nama FROM us_kebutuhan ORDER BY kebutuhan_id ASC");
                                $sqlk->execute();
                                foreach ($sqlk->fetchAll() as $row) { ?>
                                    <option value="<?= $row['kebutuhan_kd'] . ' ' . $row['kebutuhan_nama']; ?>"><?= $row['kebutuhan_kd'] . ' ' . $row['kebutuhan_nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Alamat Jalan</label>
                            <input type="text" name="jalan" class="form-control" value="<?= $ed['siswa_alamat_jln']; ?>" required>
                            <p class="form-text">Lengkap dengan nama jalan/gang, dan nomor rumah</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">RT</label>
                            <input type="number" name="rt" class="form-control" value="<?= $ed['siswa_rt']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">RW</label>
                            <input type="number" name="rw" class="form-control" value="<?= $ed['siswa_rw']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Dusun / Kampung</label>
                            <input type="text" name="dusun" class="form-control" value="<?= $ed['siswa_dusun']; ?>" required>
                            <p class="form-text">Beri tanda - jika tidak ada</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan" class="form-control" value="<?= $ed['siswa_kelurahan']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="<?= $ed['siswa_kecamatan']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Kode Pos</label>
                            <input type="number" name="kode_pos" class="form-control" value="<?= $ed['siswa_kode_pos']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Posisi Lintang Tempat Tinggal</label>
                            <input type="text" name="lintang" class="form-control" value="<?= $ed['siswa_lintang']; ?>" required>
                            <p style="padding-top:5px;" class="help-text"><button class="btn btn-primary" onclick="getLocation()">Lihat posisi lintang dan bujur</button></p>
                            <p class="form-text" id="lokasi"></p>
                            <p class="form-text">Isi dengan angka 0 jika tidak mengerti</p>
                            <script>
                                var x = document.getElementById("lokasi");

                                function getLocation() {
                                    if (navigator.geolocation) {
                                        navigator.geolocation.getCurrentPosition(showPosition);
                                    } else {
                                        x.innerHTML = "Geolocation tidak didukung oleh browser yang kamu gunakan.";
                                    }
                                }

                                function showPosition(position) {
                                    x.innerHTML = "Lintang: " + position.coords.latitude +
                                        "<br>Bujur: " + position.coords.longitude + "<br>Pastekan masing-masing posisi ke kolom yang tersedia";
                                }
                            </script>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Posisi Bujur Tempat Tinggal</label>
                            <input type="text" name="bujur" class="form-control" value="<?= $ed['siswa_bujur']; ?>" required>
                            <p class="form-text">Isi dengan angka 0 jika tidak mengerti</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Tempat Tinggal</label>
                            <select name="tinggal" class="form-select">
                                <option value="<?= $ed['siswa_tinggal']; ?>" selected><?= $ed['siswa_tinggal']; ?></option>
                                <option value="">--Pilih Tempat Tinggal--</option>
                                <?php
                                $sqlt = $db->prepare("SELECT tinggal_kd, tinggal_nama FROM us_tinggal ORDER BY tinggal_id ASC");
                                $sqlt->execute();
                                foreach ($sqlt->fetchall() as $row) { ?>
                                    <option value="<?= $row['tinggal_kd'] . ')' . $row['tinggal_nama']; ?>"><?= $row['tinggal_kd'] . ')' . $row['tinggal_nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Moda Transportasi</label>
                            <select name="transportasi" class="form-select" required>
                                <option value="<?= $ed['siswa_transport']; ?>" selected><?= $ed['siswa_transport']; ?></option>
                                <option value="">--Pilih Moda Transportasi</option>
                                <?php
                                $sqlm = $db->prepare("SELECT transport_kd, transport_nama FROM us_transportasi ORDER BY transport_id");
                                $sqlm->execute();
                                foreach ($sqlm->fetchall() as $row) { ?>
                                    <option value="<?= $row['transport_kd'] . ')' . $row['transport_nama']; ?>"><?= $row['transport_kd'] . ')' . $row['transport_nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Anak ke-</label>
                            <input type="number" name="anak_ke" class="form-control" value="<?= $ed['siswa_anak_ke']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Apakah punya KIP</label>
                            <select name="kip" class="form-select" required>
                                <option value="<?= $ed['siswa_kip'] ?>"><?= $ed['siswa_kip'] ?></option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                            <p class="form-text">Kartu Indonesia Pintar</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Apakah akan tetap menerima KIP</label>
                            <select name="status_kip" class="form-select" required>
                                <option value="<?= $ed['siswa_status_kip'] ?>"><?= $ed['siswa_status_kip'] ?></option>
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Alasan Menolak KIP</label>
                            <select name="alasan_kip" class="form-select" required>
                                <option value="<?= $ed['siswa_alasan_kip']; ?>" selected><?= $ed['siswa_alasan_kip']; ?></option>
                                <option value="">--Pilih Alasan--</option>
                                <option value="01)Dilarang pemda karena menerima bantuan serupa">01)Dilarang pemda karena menerima bantuan serupa</option>
                                <option value="02)Menolak">02)Menolak</option>
                                <option value="03)sudah mampu">03)Sudah mampu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Jenis Kesejahteraan</label>
                            <select name="kesejahteraan" class="form-select">
                                <option value="<?= $ed['siswa_krt_sejahtera']; ?>" selected><?= $ed['siswa_krt_sejahtera']; ?></option>
                                <option value="">-- Pilih Jenis Kesejahteraan --</option>
                                <option value="01) PKH">01) PKH</option>
                                <option value="02) PIP">02) PIP</option>
                                <option value="03) Kartu Perlindungan Sosial">03) Kartu Perlindungan Sosial</option>
                                <option value="04) Kartu Keluarga Sejahtera">04) Kartu Keluarga Sejahtera</option>
                                <option value="05) Kartu Kesehatan">05) Kartu Kesehatan</option>
                            </select>
                            <p class="form-text">Kosongkan jika tidak memiliki</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">No. Kartu</label>
                            <input type="text" name="no_kartu" class="form-control" value="<?= $ed['siswa_krt_no']; ?>">
                            <p class="form-text">Kosongkan jika tidak memiliki</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Nama di Kartu</label>
                            <input type="text" name="nama_kartu" class="form-control" value="<?= $ed['siswa_krt_nama']; ?>">
                            <p class="form-text">Kosongkan jika tidak memiliki</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">No. Telpon Rumah</label>
                            <input type="number" name="telp" class="form-control" value="<?= $ed['siswa_telp_rmh']; ?>" required>
                            <p class="form-text">Isi dengan angka 0 jika tidak memiliki telpon rumah</p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">No HP/Ponsel</label>
                            <input type="number" name="hp" class="form-control" value="<?= $ed['siswa_hp']; ?>" required>
                            <p class="form-text">Format: 6281xxx</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-danger">Periksa kembali isian kamu sebelum disimpan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        break;
    case 'wali':
        $id = isset($_GET['id']) ? $_GET['id'] : '';

    ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <i class="bi-person-fill"></i> FORMULIR PENDAFTAR
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="?mod=peserta&act=pribadi&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Pribadi</a>
                            <a href="?mod=peserta&act=wali&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Wali</a>
                            <a href="?mod=peserta&act=periodik&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Periodik</a>
                            <a href="?mod=peserta&act=prestasi&id=<?= $siswa['siswa_uid'] ?>i" class="list-group-item list-group-item-action">Data Prestasi</a>
                            <a href="?mod=peserta&act=beasiswa&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Beasiswa</a>
                            <a href="?mod=peserta&act=sekolah&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Sekolah Asal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2"><i class="bi-clipboard-check"></i> KETERANGAN PENDAFTARAN</div>
                            <div class="P-2"><a href="?mod=peserta" class="btn btn-sm btn-primary"><i class="bi-list-ul"></i> daftar peserta</a></div>
                        </div>
                    </div>
                    <div class="card-body">


                    </div>
                </div>
            </div>
        </div>
    <?php
        break;
    case 'periodik':
        $id = isset($_GET['id']) ? $_GET['id'] : '';

    ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <i class="bi-person-fill"></i> FORMULIR PENDAFTAR
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="?mod=peserta&act=pribadi&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Pribadi</a>
                            <a href="?mod=peserta&act=wali&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Wali</a>
                            <a href="?mod=peserta&act=periodik&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Periodik</a>
                            <a href="?mod=peserta&act=prestasi&id=<?= $siswa['siswa_uid'] ?>i" class="list-group-item list-group-item-action">Data Prestasi</a>
                            <a href="?mod=peserta&act=beasiswa&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Beasiswa</a>
                            <a href="?mod=peserta&act=sekolah&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Sekolah Asal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2"><i class="bi-clipboard-check"></i> KETERANGAN PENDAFTARAN</div>
                            <div class="P-2"><a href="?mod=peserta" class="btn btn-sm btn-primary"><i class="bi-list-ul"></i> daftar peserta</a></div>
                        </div>
                    </div>
                    <div class="card-body">


                    </div>
                </div>
            </div>
        </div>
    <?php
        break;
    case 'prestasi':
        $id = isset($_GET['id']) ? $_GET['id'] : '';

    ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <i class="bi-person-fill"></i> FORMULIR PENDAFTAR
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="?mod=peserta&act=pribadi&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Pribadi</a>
                            <a href="?mod=peserta&act=wali&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Wali</a>
                            <a href="?mod=peserta&act=periodik&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Periodik</a>
                            <a href="?mod=peserta&act=prestasi&id=<?= $siswa['siswa_uid'] ?>i" class="list-group-item list-group-item-action">Data Prestasi</a>
                            <a href="?mod=peserta&act=beasiswa&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Beasiswa</a>
                            <a href="?mod=peserta&act=sekolah&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Sekolah Asal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2"><i class="bi-clipboard-check"></i> KETERANGAN PENDAFTARAN</div>
                            <div class="P-2"><a href="?mod=peserta" class="btn btn-sm btn-primary"><i class="bi-list-ul"></i> daftar peserta</a></div>
                        </div>
                    </div>
                    <div class="card-body">


                    </div>
                </div>
            </div>
        </div>
    <?php
        break;
    case 'beasiswa':
        $id = isset($_GET['id']) ? $_GET['id'] : '';

    ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <i class="bi-person-fill"></i> FORMULIR PENDAFTAR
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="?mod=peserta&act=pribadi&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Pribadi</a>
                            <a href="?mod=peserta&act=wali&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Wali</a>
                            <a href="?mod=peserta&act=periodik&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Periodik</a>
                            <a href="?mod=peserta&act=prestasi&id=<?= $siswa['siswa_uid'] ?>i" class="list-group-item list-group-item-action">Data Prestasi</a>
                            <a href="?mod=peserta&act=beasiswa&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Beasiswa</a>
                            <a href="?mod=peserta&act=sekolah&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Sekolah Asal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2"><i class="bi-clipboard-check"></i> KETERANGAN PENDAFTARAN</div>
                            <div class="P-2"><a href="?mod=peserta" class="btn btn-sm btn-primary"><i class="bi-list-ul"></i> daftar peserta</a></div>
                        </div>
                    </div>
                    <div class="card-body">


                    </div>
                </div>
            </div>
        </div>
    <?php
        break;
    case 'sekolah':
        $id = isset($_GET['id']) ? $_GET['id'] : '';

    ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <i class="bi-person-fill"></i> FORMULIR PENDAFTAR
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="?mod=peserta&act=pribadi&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Pribadi</a>
                            <a href="?mod=peserta&act=wali&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Wali</a>
                            <a href="?mod=peserta&act=periodik&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Periodik</a>
                            <a href="?mod=peserta&act=prestasi&id=<?= $siswa['siswa_uid'] ?>i" class="list-group-item list-group-item-action">Data Prestasi</a>
                            <a href="?mod=peserta&act=beasiswa&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Beasiswa</a>
                            <a href="?mod=peserta&act=sekolah&id=<?= $siswa['siswa_uid'] ?>" class="list-group-item list-group-item-action">Data Sekolah Asal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1 p-2"><i class="bi-clipboard-check"></i> KETERANGAN PENDAFTARAN</div>
                            <div class="P-2"><a href="?mod=peserta" class="btn btn-sm btn-primary"><i class="bi-list-ul"></i> daftar peserta</a></div>
                        </div>
                    </div>
                    <div class="card-body">


                    </div>
                </div>
            </div>
        </div>
<?php
        break;
}
