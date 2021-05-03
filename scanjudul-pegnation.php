<?php
error_reporting(0);
header('Content-Type:application/json');
include 'koneksi.php';
include 'smithwaterman.php';
include 'snippet.php';
$judul =  $koneksi->real_escape_string(htmlentities(trim(isset($_POST['judul']) ? $_POST['judul']:"")));
if(strlen($judul)>=1){
    if(strlen($judul)<3){
          $json_array = array(
                  'error' => true,
                  'message' => "Judul skripsi terlalu pendek"
          );
          echo json_encode($json_array);
          return;
    }
}
  $halaman = 10;
  $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
  $mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
  $result = $koneksi->query("SELECT * FROM tbl_daftar_judul");
   if($judul != ""){
       $where = "";
       $judul_skripsi = preg_split('/[\s]+/', $judul);
       $total_judul = count($judul_skripsi);
       foreach($judul_skripsi as $key=>$kunci){
              $where .= "judul_skripsi LIKE '%$kunci%'";
              if($key != ($total_judul - 1)){
                                  $where .= " OR ";
              }
        }
        $results = $koneksi->query("SELECT judul_skripsi, nama_mahasiswa, tahun, no_buku FROM tbl_daftar_judul WHERE $where");
   }else{
        $results = $koneksi->query("SELECT judul_skripsi, nama_mahasiswa, tahun, no_buku FROM tbl_daftar_judul ORDER BY tahun DESC LIMIT $mulai, $halaman");
   }
    $num = $results->num_rows;
if($num == 0){
          $json_array = array(
                'error' => true,
                'message' => "Pencarian Judul Tidak Ditemukan"
          ); 
}else{
          $response = array();
          // $listdata = 0;
          while($row = $results->fetch_assoc()){
            if($judul != ""){
                  //menampilkan data
                  $sw = new SmithWaterman($judul,$row['judul_skripsi']);
                  $score = $sw->getScore();
                  $persen = (int)number_format($sw->getPresen(), 0);

                  $sw = new Snippet($judul,$row['judul_skripsi']);
                  $html = $sw->get_html();

               //    $preProsesJudul = preg_replace("/[^a-zA-Z]/", "", strtolower($judul));
               //    $preProsesDataJudul = preg_replace("/[^a-zA-Z]/", "", strtolower($row['judul_skripsi']));
               //    $perbandingan = 0;
               //    if(strlen($preProsesJudul) != strlen($preProsesDataJudul)){	
	              //     if(strlen($preProsesJudul) > strlen($preProsesDataJudul)){
	              //     		$perbandingan = strlen($preProsesJudul) - strlen($preProsesDataJudul);
	              //     }else{
	              //     		$perbandingan = strlen($preProsesDataJudul) - strlen($preProsesJudul);
	              //     }
	              // }

	              // if($perbandingan == 0){	
                  // if($persen >= 50){
                  		// $listdata++;
                  		array_push($response,
                        array(
                              'judul_skripsi' => $row['judul_skripsi'],
                              'nama_mahasiswa' => $row['nama_mahasiswa'],
                              'tahun' => $row['tahun'],
                              'no_buku' => $row['no_buku'],
                              'kemiripan' => $persen,
                              'kata_mirip' => $html
                          	)
                        );
              	   // }
                  // }
                  // if($listdata >= 1){

	              // }else{
	              // 	  $json_array = array(
               //  				'error' => true,
               //  				'message' => "Pencarian Judul Tidak Ditemukan"
          					// 	); 
	              // }
            }else{
                array_push($response,
                        array(
                              'judul_skripsi' => $row['judul_skripsi'],
                              'nama_mahasiswa' => $row['nama_mahasiswa'],
                              'tahun' => $row['tahun'],
                              'no_buku' => $row['no_buku'] )
                        );
            }
          }
          if($judul != ""){
              function mengurutkan_kemiripan($building_a, $building_b) {
                    return $building_b["kemiripan"] - $building_a["kemiripan"];
              }
              usort($response, "mengurutkan_kemiripan");
          }
          $json_array = array(
                      'error' => false,
                      'listjudul' => $response
          );
}
    echo json_encode($json_array);
?>