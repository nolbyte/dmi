<?php
defined("RESMI") or die("error");
?>
<div class="col-md-12">
    <div class="card shadow">
        <div class="card-header">
            <i class="bi-clipboard-check"></i> 10 DAFTAR SELF ASSESSMENT TERBARU
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama Mesjid</th>
                            <th>Alamat</th>
                            <th>Penilai/DKM</th>
                            <th>Kategori Mesjid</th>
                            <th>Nilai</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = $db->prepare("SELECT * FROM dm_mesjid dm LEFT JOIN dm_kategori dk ON dm.mesjid_kategori=dk.kategori_id ORDER BY dm.mesjid_nama ASC");
                        $sql->execute();
                        $no = 1;
                        foreach ($sql->fetchAll() as $row) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['mesjid_nama'] ?></td>
                                <td>
                                    <?= $row['mesjid_jalan'] . ', RT ' . $row['mesjid_rt'] . ', RW ' . $row['mesjid_rw'] . '<br> Desa/Kelurahan: ' . $row['mesjid_desa'] . '<br> Kecamatan: ' . $row['mesjid_kecamatan'] . '<br> Kabupaten/Kota: ' . $row['mesjid_kota'] . '<br> Provinsi: ' . $row['mesjid_provinsi'] ?>
                                </td>
                                <td><?= $row['mesjid_pic'] ?></td>
                                <td><?= $row['kategori_nama'] ?></td>
                                <td>
                                    <?php
                                    $sql = $db->prepare("SELECT SUM(nilai_poin) AS total_nilai FROM dm_nilai dn LEFT JOIN dm_mesjid dm ON dn.nilai_mesjid=dm.mesjid_id WHERE dm.mesjid_id = :idna");
                                    $sql->execute(array(':idna' => $row['mesjid_id']));
                                    $nilai = $sql->fetch(PDO::FETCH_ASSOC);
                                    $total_nilai = $nilai['total_nilai'];
                                    echo $total_nilai;
                                    ?>
                                </td>
                                <td>
                                    <a href="?mod=assessment-list&act=detail&id=<?= $row['mesjid_id'] ?>" class="btn btn-xs btn-warning me-2"><i class="bi-search"></i></a>
                                    <a href="" class="btn btn-xs btn-danger hapus-data" data-id="<?= $row['mesjid_id'] ?>"><i class="bi-trash"></i></a>
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
<!-- Modal -->
<div class="modal fade" id="detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Self Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <i class="fa fa-refresh fa-spin loading" style="display: none;"></i> Sedang mengambil data, tunggu sebentar
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.btnView').click(function() {
            var mesjidId = $(this).data('id');
            $('.loading').show();
            //var data = 'makul='+ encodeURIComponent(makulId)  & 'semester='+ encodeURIComponent(smtId);
            //var data = 'makulId='+ makulId+'&smtId='+ smtId;
            $.ajax({
                url: 'post.php?mod=peserta&hal=assessment_detail',
                type: 'post',
                data: {
                    mesjid: mesjidId
                },
                success: function(data) {
                    $('.modal-body').html(data);
                    $('#detail').modal('show');
                    $('.loading').hide();
                    if (!data) {
                        alert('belum ada data yang bisa ditampilkan');
                        return;
                    }
                }

            });
        });
        $('.hapus-data').click(function(e) {
            e.preventDefault();
            var empid = $(this).attr('data-id');
            var parent = $(this).parent("td").parent("tr");
            bootbox.dialog({
                message: "Yakin akan menghapus data ini ?<br>Data yang sudah dihapus tidak dapat dikembalikan",
                title: "<i class='bi-trash'></i> Hapus Self Assessment !",
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
                                    url: 'post.php?mod=peserta&hal=assessment_delete',
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