<?php
defined("RESMI") or die("error");

?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex">
                    <div class="flex-grow-1 p-2">
                        <i class="bi-clipboard-check"></i> ASPEK PENILAIAN
                    </div>
                    <div>
                        <a href="" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#AddData"><i class="fa fa-plus"></i> tambah data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="aspek" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th style="width: 80%">Aspek Penilaian</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT * FROM dm_aspek");
                            $sql->execute();
                            $no = 1;
                            foreach ($sql->fetchAll() as $row) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['aspek_nama'] ?></td>
                                    <td>
                                        <a href="" class="btn btn-xs btn-warning" data-backdrop="static" data-keyboard="false" data-bs-toggle="modal" data-bs-target="#EditData" data-aspek_id="<?= $row['aspek_id'] ?>" data-aspek_nama="<?= $row['aspek_nama'] ?>"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-danger btn-xs hapus-data" data-id="<?= $row['aspek_id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Tambah Aspek Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=aspek-save">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama Aspek</label>
                        <textarea name="nama" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-dismiss="modal"><i class="bi-box-arrow-left"></i> Batal</button>&nbsp;<button type="submit" class="btn btn-sm btn-success"><i class="bi-save"></i> simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- EDIT Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="EditData">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Tambah Aspek Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=aspek-edit">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <input type="hidden" name="aspek_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama Aspek</label>
                        <textarea name="aspek_nama" class="form-control" required></textarea>
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
        var ot = $('#aspek').dataTable({
            "ordering": false,
        });
        $('.hapus-data').click(function(e) {
            e.preventDefault();
            var empid = $(this).attr('data-id');
            var parent = $(this).parent("td").parent("tr");
            bootbox.dialog({
                message: "Yakin akan menghapus data ini ?<br>Data yang sudah dihapus tidak dapat dikembalikan",
                title: "<i class='bi-trash'></i> Hapus Aspek Penilaian !",
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
                                    url: 'post.php?mod=referensi&hal=aspek_delete',
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
            $(this).find('input[name="aspek_id"]').val('')
            $(this).find('textarea[name="aspek_nama"]').val('')
            if (button.data('aspek_id') != '') {
                var aspek_id = button.data('aspek_id')
                var aspek_nama = button.data('aspek_nama')
                $(this).find('input[name="aspek_id"]').val(aspek_id)
                $(this).find('textarea[name="aspek_nama"]').val(aspek_nama)
            }
        });
    });
</script>