<?php
	$nama = $_POST['nama'];
	$harga = $_POST['harga'];
	$stok = $_POST['stok'];
	
	$foto = $_FILES['gambar']['name'];
	$tmpName = $_FILES['gambar']['tmp_name'];
	$size = $_FILES['gambar']['size'];
	$type = $_FILES['gambar']['type'];
	
	$maxsize = 1500000;
	$typeYgBoleh = array ("image/jpeg","image/png","image/pjpeg");
	
	$dirFoto= "pict";
	if(!is_dir($dirFoto))
		mkdir ($dirFoto);
		$fileTujuanFoto = $dirFoto."/".$foto;
	
	$dirThumb = "thumb";
	if(!is_dir($dirThumb))
		mkdir ($dirThumb);
		$fileTujuanThumb=$dirThumb."/t_".$foto;
	
	$dataValid="YA";
	
	if ($size>0){
		if($size > $maxsize){
			echo "Ukuran file terlalu besar <br/>";
			$dataValid="TIDAK";
		}
		if(!in_array($type, $typeYgBoleh)){
			echo "Type File tidak dikenal <br/>";
			$dataValid="TIDAK";
		}
	}
	if (strlen(trim($nama))==0) {
		echo "Nama Barang Harus diisi ! <br/>";
		$dataValid = "TIDAK";
	}
	if (strlen(trim($harga))==0) {
		echo "Harga Barang Harus diisi ! <br/>";
		$dataValid = "TIDAK";
	}
	if (strlen(trim($stok))==0) {
		echo "Stok Barang Harus diisi ! <br/>";
		$dataValid = "TIDAK";
	}
	if ($dataValid == "TIDAK") {
		echo "Masih ada kesalahan, silahkan perbaiki ! <br/>";
		echo "<input type='button' value='kembali' onClick='self.history.back()'>";
		exit;
	}
	
	include "koneksi.php";
	
	$sql =	"insert into barang
			(nama,harga,stok,foto)
			values 
			('$nama',$harga,$stok,'$foto') ";
			$hasil = mysqli_query($kon,$sql);
		
	if (!$hasil) {
		echo "Gagal simpan,silahkan diulangi! <br/>";
		echo mysqli_error($kon);
		echo "<br/><input type='button' value='kembali' onClick='self.history.back()'>";
		exit;
	} else {
		echo "Simpan data berhasil ";
	}
	if ($size>0){
		if(!move_uploaded_file($tmpName,$fileTujuanFoto)){
			echo "Gagal upload gambar . . <br/>";
			echo "<a href='barang_tampil.php'>daftar barang</a>";
			exit;
		} else {
			buat_thumbnail($fileTujuanFoto,$fileTujuanThumb);
		}
	}
	echo "<br>File sudah di uppload . <br/>";
	function buat_thumbnail($file_src, $file_dst){
		list($w_src,$h_src,$type)=getImageSize($file_src);
		
		switch ($type) {
			case 1 :
				$img_src = imagecreatefromgif($file_src);
				break;
			case 2 :
				$img_src = imagecreatefromjpeg($file_src);
				break;
			case 3 :
				$img_src = imagecreatefrompng($file_src);
				break;
		}
	$thumb = 100; 
	if ($w_src > $h_src) {
		$w_dst =$thumb;  
		$h_dst =round($thumb / $w_src * $h_src);
	} else {
		$w_dst =round($thumb / $h_src * $w_src);
		$h_dst =$thumb;
	}
	
	$img_dst = imagecreatetruecolor($w_dst, $h_dst); 
	
	imagecopyresampled($img_dst,$img_src,0,0,0,0,$w_dst,$h_dst,$w_src,$h_src);
	imagejpeg($img_dst,$file_dst);
	imagedestroy($img_src);
	imagedestroy($img_dst);
	}
?>
