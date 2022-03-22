<?php
defined("RESMI") or die("error");

?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex">
                    <div class="flex-grow-1 p-2">
                        <i class="bi-clipboard-check"></i> KATEGORI MESJID
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
                                <th class="w-25">Nomor</th>
                                <th>Nama Kategori</th>
                                <th>Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT * FROM dm_kategori");
                            $sql->execute();
                            $no = 1;
                            foreach ($sql->fetchAll() as $row) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['kategori_nama'] ?></td>
                                    <td><?= $row['kategori_keterangan'] ?></td>
                                    <td>
                                        <a href="" class="btn btn-xs btn-warning" data-backdrop="static" data-keyboard="false" data-bs-toggle="modal" data-bs-target="#EditData" data-kategori_id="<?= $row['kategori_id'] ?>" data-kategori_nama="<?= $row['kategori_nama'] ?>" data-kategori_keterangan="<?= $row['kategori_keterangan'] ?>"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-danger btn-xs hapus-data" data-id="<?= $row['kategori_id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Tambah Kategori Mesjid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=kategori-save">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama Kategori</label>
                        <input type="text" name="nama" class="form-control" placeholder="Mesjid Agung, Mesjid Raya, dll " required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan"></textarea>
                        <p class="form-text">Opsional, jika diperlukan</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-dismiss="modal"><i class="bi-box-arrow-left"></i> Batal</button>&nbsp;<button type="submit" class="btn btn-sm btn-success"><i class="bi-save"></i> simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Data -->
<div class="modal fade" tabindex="-1" role="dialog" id="EditData">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Ubah Kategori Mesjid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=kategori-edit">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <input type="hidden" name="kategori_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Nama Kategori</label>
                        <input type="text" name="kategori_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="kategori_keterangan"></textarea>
                        <p class="form-text">Opsional, jika diperlukan</p>
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
                title: "<i class='bi-trash'></i> Hapus Kategori Mesjid !",
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
                                    url: 'post.php?mod=referensi&hal=kategori_delete',
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
            $(this).find('input[name="kategori_id"]').val('')
            $(this).find('input[name="kategori_nama"]').val('')
            $(this).find('textarea[name="kategori_nama"]').val('')
            if (button.data('kategori_id') != '') {
                var kategori_id = button.data('kategori_id')
                var kategori_nama = button.data('kategori_nama')
                var kategori_keterangan = button.data('kategori_keterangan')
                $(this).find('input[name="kategori_id"]').val(kategori_id)
                $(this).find('input[name="kategori_nama"]').val(kategori_nama)
                $(this).find('textarea[name="kategori_keterangan"]').val(kategori_keterangan)
            }
        });
    });
</script>