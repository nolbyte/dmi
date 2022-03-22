<?php
defined("RESMI") or die("error");

?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex">
                    <div class="flex-grow-1 p-2">
                        <i class="bi-clipboard-check"></i> KRITERIA PENILAIAN
                    </div>
                    <div>
                        <a href="" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#AddData"><i class="fa fa-plus"></i> tambah data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Nama Kriteria</th>
                                <th>Poin</th>
                                <th class="w-50">Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT * FROM dm_kriteria");
                            $sql->execute();
                            $no = 1;
                            foreach ($sql->fetchAll() as $row) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['kriteria_nama'] ?></td>
                                    <td><?= $row['kriteria_poin'] ?></td>
                                    <td><?= $row['kriteria_keterangan'] ?></td>
                                    <td>
                                        <a href="" class="btn btn-xs btn-warning" data-backdrop="static" data-keyboard="false" data-bs-toggle="modal" data-bs-target="#EditData" data-kriteria_id="<?= $row['kriteria_id'] ?>" data-kriteria_nama="<?= $row['kriteria_nama'] ?>" data-kriteria_poin="<?= $row['kriteria_poin'] ?>" data-kriteria_keterangan="<?= $row['kriteria_keterangan'] ?>"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-danger btn-xs hapus-data" data-id="<?= $row['kriteria_id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
<!-- Add Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="AddData">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Tambah Kriteria Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=kriteria-save">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama Kriteria</label>
                        <input type="text" name="nama" class="form-control" placeholder="Belum Ada, Ada, dll" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Poin/Nilai</label>
                        <input type="number" class="form-control" name="poin"></input>
                        <p class="form-text">Berupa angka, 1 dst</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-dismiss="modal"><i class="bi-box-arrow-left"></i> Batal</button>&nbsp;<button type="submit" class="btn btn-sm btn-success"><i class="bi-save"></i> simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="EditData">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Ubah Kriteria Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=kriteria-edit">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <input type="hidden" name="kriteria_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama Kriteria</label>
                        <input type="text" name="kriteria_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Poin/Nilai</label>
                        <input type="number" class="form-control" name="kriteria_poin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="kriteria_keterangan" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-dismiss="modal"><i class="bi-box-arrow-left"></i> Batal</button>&nbsp;<button type="submit" class="btn btn-sm btn-success"><i class="bi-save"></i> simpan</button>
                </div>
            </form>
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
                message: "Yakin akan menghapus data ini ?<br>Data yang sudah dihapus tidak dapat dikembalikan",
                title: "<i class='bi-trash'></i> Hapus Kriteria Penilaian !",
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
                                    url: 'post.php?mod=referensi&hal=kriteria_delete',
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
        $('#EditData').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            $(this).find('input[name="kriteria_id"]').val('')
            $(this).find('input[name="kriteria_nama"]').val('')
            $(this).find('input[name="kriteria_poin"]').val('')
            $(this).find('textarea[name="kriteria_keterangan"]').val('')
            if (button.data('kriteria_id') != '') {
                var kriteria_id = button.data('kriteria_id')
                var kriteria_nama = button.data('kriteria_nama')
                var kriteria_poin = button.data('kriteria_poin')
                var kriteria_keterangan = button.data('kriteria_keterangan')
                $(this).find('input[name="kriteria_id"]').val(kriteria_id)
                $(this).find('input[name="kriteria_nama"]').val(kriteria_nama)
                $(this).find('input[name="kriteria_poin"]').val(kriteria_poin)
                $(this).find('textarea[name="kriteria_keterangan"]').val(kriteria_keterangan)
            }
        });
    });
</script>