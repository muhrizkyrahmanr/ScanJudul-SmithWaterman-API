<?php
$koneksi = new mysqli("localhost", "root", "", "db_scanjudul");
if ($koneksi->connect_errno) {
    echo "Gagal melakukan koneksi ke database: " . $koneksi->connect_error;
}
?>