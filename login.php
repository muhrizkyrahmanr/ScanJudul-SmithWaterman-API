<?php
header('Content-Type:application/json');
include 'koneksi.php';
$stambuk = $koneksi->real_escape_string(htmlentities(trim(isset($_POST['stambuk']) ? $_POST['stambuk']:"")));
$password = $koneksi->real_escape_string(htmlentities(trim(isset($_POST['password']) ? $_POST['password']:"")));
if($stambuk=="" || $password==""){
	$json_array = array(
		'error' => true,
		'message' => "Stambuk atau Password masih kosong",
	);
}else{
$password = md5($password);
$results = $koneksi->query("SELECT stambuk, nama, alamat, no_hp FROM tbl_user where stambuk='$stambuk' and password='$password'");
$ceklogin = $results->num_rows;
if($ceklogin > 0){
	$row = $results->fetch_assoc();
	$json_array = array(
                         'error' => false,
                         'stambuk' => $row['stambuk'],
                         'nama' => $row['nama'],
                         'alamat' => $row['alamat'],
                         'nohp' => $row['no_hp']
                   );
}else{
			$json_array = array(
                         'error' => true,
                         'message' => "Stambuk atau Password yang anda masukkan salah"
             );
}
}
echo json_encode($json_array);
?>