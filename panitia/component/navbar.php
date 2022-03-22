<?php
defined("RESMI") or die("error");
?>
<ul class="navbar-nav mr-auto">
    <li class="nav-item active">
        <a class="nav-link text-white" href="index.php">Home</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Referensi</a>
        <div class="dropdown-menu" aria-labelledby="dropdown02">
            <a class="dropdown-item" href="?mod=kategori-mesjid">Kategori Mesjid</a>
            <a class="dropdown-item" href="?mod=aspek-nilai">Aspek Penilaian</a>
            <a class="dropdown-item" href="?mod=kriteria-nilai">Kriteria Penilaian</a>
        </div>
    </li>
    <li class="nav-item active">
        <a class="nav-link text-white" href="?mod=assessment-list">Assessment List</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pengguna</a>
        <div class="dropdown-menu" aria-labelledby="dropdown02">
            <a class="dropdown-item" href="">Daftar Pengguna</a>
            <a class="dropdown-item" href="">Password</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
    </li>
</ul>
<ul class="navbar-nav ms-auto text-white" style="padding-right: 8px;">

</ul>