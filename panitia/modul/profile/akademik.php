<?php
defined("RESMI") or die("error");
?>
<div class="row d-flex justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex">
                    <div class="flex-grow-1 p-2">
                        <i class="bi-gear-wide-connected"></i> DATA TAHUN AJARAN
                    </div>
                    <div>
                        <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#AddData"><i class="fa fa-plus"></i> tambah data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Detail</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT * FROM us_akademik ORDER BY akademik_nama DESC");
                            $sql->execute();
                            $no = 1;
                            foreach ($sql->fetchAll() as $row) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['akademik_nama'] ?></td>
                                    <td><?= $row['akademik_detail'] ?></td>
                                    <td><?= $row['akademik_status'] > 0 ? 'Aktif' : 'Non Aktif' ?></td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-xs btn-primary" data-backdrop="static" data-keyboard="false" data-bs-toggle="modal" data-bs-target="#EditData" data-akademik_id="<?= $row['akademik_id'] ?>" data-akademik_nama="<?= $row['akademik_nama'] ?>" data-akademik_detail="<?= $row['akademik_detail'] ?>" data-akademik_status="<?= $row['akademik_status'] ?>"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-danger btn-xs hapus-data" data-id="<?= $row['akademik_id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Tambah Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=akademik-save">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Tahun Ajaran 2021/2022" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
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
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Ubah Tahun Ajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=akademik-edit">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <input type="hidden" name="akademik_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama</label>
                        <input type="text" name="akademik_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="akademik_detail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="akademik_status" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
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
                message: "Yakin akan menghapus data ini ?",
                title: "<i class='bi-trash'></i> Hapus Tahun Ajaran !",
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
                                    url: 'post.php?mod=profile&hal=akademik_delete',
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
            $(this).find('input[name="akademik_id"]').val('')
            $(this).find('input[name="akademik_nama"]').val('')
            $(this).find('input[name="akademik_detail"]').val('')
            $(this).find('select[name="akademik_status"]').val('')
            if (button.data('akademik_id') != '') {
                var akademik_id = button.data('akademik_id')
                var akademik_nama = button.data('akademik_nama')
                var akademik_detail = button.data('akademik_detail')
                var akademik_status = button.data('akademik_status')
                $(this).find('input[name="akademik_id"]').val(akademik_id)
                $(this).find('input[name="akademik_nama"]').val(akademik_nama)
                $(this).find('input[name="akademik_detail"]').val(akademik_detail)
                $(this).find('select[name="akademik_status"]').val(akademik_status)
            }
        });
    });
</script>