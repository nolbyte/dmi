<?php
defined("RESMI") or die("error");
?>
<div class="row d-flex justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex">
                    <div class="flex-grow-1 p-2">
                        <i class="bi-gear-wide-connected"></i> DATA JURUSAN
                    </div>
                    <div>
                        <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#AddData"><i class="bi-plus"></i> tambah data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Jurusan</th>
                                <th>Nama Jurusan</th>
                                <th>Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->prepare("SELECT * FROM us_jurusan");
                            $sql->execute();
                            $no = 1;
                            foreach ($sql->fetchAll() as $row) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['jurusan_kd'] ?></td>
                                    <td><?= $row['jurusan_nama'] ?></td>
                                    <td><?= $row['jurusan_keterangan'] ?></td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-xs btn-primary" data-backdrop="static" data-keyboard="false" data-bs-toggle="modal" data-bs-target="#EditData" data-jurusan_id="<?= $row['jurusan_id'] ?>" data-jurusan_kd="<?= $row['jurusan_kd'] ?>" data-jurusan_nama="<?= $row['jurusan_nama'] ?>" data-jurusan_keterangan="<?= $row['jurusan_keterangan'] ?>"><i class="fa fa-edit"></i></a>
                                        <a href="" class="btn btn-danger btn-xs hapus-data" data-id="<?= $row['jurusan_id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Tambah Data Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=jurusan-save">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Kode Jurusan</label>
                        <input type="number" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                        <p class="form-text">Kosongkan jika tidak ada keterangan</p>
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
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi-gear-wide-connected"></i> Ubah Data Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="?mod=jurusan-edit">
                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>">
                <input type="hidden" name="jurusan_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="">Kode Jurusan</label>
                        <input type="number" name="jurusan_kd" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="jurusan_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="jurusan_keterangan" class="form-control">
                        <p class="form-text">Kosongkan jika tidak ada keterangan</p>
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
                title: "<i class='bi-trash'></i> Hapus Data Jurusan !",
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
                                    url: 'post.php?mod=profile&hal=jurusan_delete',
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
            $(this).find('input[name="jurusan_id"]').val('')
            $(this).find('input[name="jurusan_kd"]').val('')
            $(this).find('input[name="jurusan_nama"]').val('')
            $(this).find('input[name="jurusan_keterangan"]').val('')
            if (button.data('jurusan_id') != '') {
                var jurusan_id = button.data('jurusan_id')
                var jurusan_kd = button.data('jurusan_kd')
                var jurusan_nama = button.data('jurusan_nama')
                var jurusan_keterangan = button.data('jurusan_keterangan')
                $(this).find('input[name="jurusan_id"]').val(jurusan_id)
                $(this).find('input[name="jurusan_kd"]').val(jurusan_kd)
                $(this).find('input[name="jurusan_nama"]').val(jurusan_nama)
                $(this).find('input[name="jurusan_keterangan"]').val(jurusan_keterangan)
            }
        });
    });
</script>