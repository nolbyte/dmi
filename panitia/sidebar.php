<div class="card shadow mb-3">
    <div class="card-header bg-success bg-gradient text-white">
        Formulir PPDB
    </div>
    <div class="card-body">
        <div class="list-group">
            <a href="?mod=data-pribadi" class="list-group-item list-group-item-action">Data Pribadi</a>
            <a href="?mod=data-wali" class="list-group-item list-group-item-action">Data Wali</a>
            <a href="?mod=data-periodik" class="list-group-item list-group-item-action">Data Periodik</a>
            <a href="?mod=data-prestasi" class="list-group-item list-group-item-action">Data Prestasi</a>
            <a href="?mod=data-beasiswa" class="list-group-item list-group-item-action">Data Beasiswa</a>
            <a href="?mod=data-sekolah" class="list-group-item list-group-item-action">Data Sekolah Asal</a>
        </div>
    </div>
</div>
<div class="card shadow mb-3">
    <div class="card-header bg-success bg-gradient text-white">
        Informasi PPDB
    </div>
    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item"><i class="fa fa-whatsapp"></i> <a target="_blank" href="https://api.whatsapp.com/send?phone=<?= $sekolah['sekolah_hp']; ?>&amp;text=<?= $sekolah['sekolah_pesan']; ?>"><?= $sekolah['sekolah_hp']; ?></a> - <?= $sekolah['sekolah_kontak1']; ?></li>
            <li class="list-group-item"><i class="fa fa-whatsapp"></i> <a target="_blank" href="https://api.whatsapp.com/send?phone=<?= $sekolah['sekolah_hp2']; ?>&amp;text=<?= $sekolah['sekolah_pesan']; ?>"><?= $sekolah['sekolah_hp2']; ?></a> - <?= $sekolah['sekolah_kontak2']; ?></li>
            <li class="list-group-item"><i class="fa fa-whatsapp"></i> <a target="_blank" href="https://api.whatsapp.com/send?phone=<?= $sekolah['sekolah_hp3']; ?>&amp;text=<?= $sekolah['sekolah_pesan']; ?>"><?= $sekolah['sekolah_hp3']; ?></a> - <?= $sekolah['sekolah_kontak3']; ?></li>
            <li class="list-group-item"><i class="fa fa-whatsapp"></i> <a target="_blank" href="https://api.whatsapp.com/send?phone=<?= $sekolah['sekolah_hp4']; ?>&amp;text=<?= $sekolah['sekolah_pesan']; ?>"><?= $sekolah['sekolah_hp4']; ?></a> - <?= $sekolah['sekolah_kontak4']; ?></li>
        </ul>
        <p class="small">Tap/klik pada nomor diatas untuk menghubungi langsung melalui Whatsapp</p>
    </div>
</div>
<div class="card shadow mb-3">
    <div class="card-header bg-success bg-gradient text-white">
        Link Sekolah
    </div>
    <div class="card-body">
        <div class="d-flex">
            <div class="me-2"><img src="../assets/img/logo/logo-m1-50.png" alt="SMK Mahadhika 1"></div>
            <div>
                <a href="https://ppdb1.mahadhika.sch.id" target="_blank" class="product-title">
                    SMK Mahadhika 1
                </a><br>
                <span class="fs-7">
                    Akuntansi Keuangan Lembaga<br>Otomatisasi Tata Kelola Perkantoran
                </span>
            </div>
        </div>
        <div class="d-flex">
            <div class="me-2"><img src="../assets/img/logo/logo-m2-50.png" alt="SMK Mahadhika 1"></div>
            <div>
                <a href="https://ppdb2.mahadhika.sch.id" target="_blank" class="product-title">
                    SMK Mahadhika 2
                </a><br>
                <span class="fs-7">
                    Teknik Kendaraan Ringan<br>Teknik Elektronika
                </span>
            </div>
        </div>
        <div class="d-flex">
            <div class="me-2"><img src="../assets/img/logo/logo-m3-50.png" alt="SMK Mahadhika 1"></div>
            <div>
                <a href="https://smk3.mahadhika.sch.id/ppdb" target="_blank" class="product-title">
                    SMK Mahadhika 3
                </a><br>
                <span class="fs-7">
                    Akomodasi Perhotelan
                </span>
            </div>
        </div>
    </div>
</div>