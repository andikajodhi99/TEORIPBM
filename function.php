<?php 
//koneksi ke database
$db = mysqli_connect("localhost","root","","angkringan");

// query
function query($query){
	global $conn;
  	$result= mysqli_query($conn,$query);
  	$rows=[];
  	while ($row=mysqli_fetch_assoc($result)) {
  		$rows[]=$row;
  	}
  	return $rows;
  	}

function tambah($data){
  	global $conn;


  // upload gambar
    $gambar =upload();
    if (!$gambar) {
      return false;
    }

  	$nama = htmlspecialchars($data["nama"]);
    $nim =htmlspecialchars($data["nim"]);
 	  $jurusan =htmlspecialchars($data["jurusan"]);
 	  $email =htmlspecialchars($data["email"]);
 	  $query = "INSERT INTO mahasiswa
 				VALUES
 				('','$gambar','$nama','$nim','$jurusan','$email')
 				";
 				mysqli_query($conn, $query);
 			return mysqli_affected_rows($conn);
  	}
function upload(){
  $namaFile =$_FILES['gambar']['name'];
  $ukuranFile =$_FILES['gambar']['size'];
  $error =$_FILES['gambar']['error'];
  $tmpName =$_FILES['gambar']['tmp_name'];
  // cek apakah tidak ada gambar yang diupload
  if ($error===4) {
    echo"
    <script>
    alert('pilih gambar terlebih dahulu');
    </script>";
    return false;
  }
  $ekstensiGambarValid=['jpg','jpeg','png'];
  $ekstensiGambar=explode('.', $namaFile);
  $ekstensiGambar=strtolower(end($ekstensiGambar));
  // cek ekstensi
  if  (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    echo"
    <script>
    alert('yang diupload bukan gambar!');
    </script>";
    return false;
  }
  // cek ukuran
  if ($ukuranFile>1000000) {
    echo "<script>
    alert('ukuran terlalu besar!');
    </script>";
    return false;
  }
  // lolos pengencekan, gambar siap diupload
  $namaFileBaru=uniqid();
  $namaFileBaru.='.';
  $namaFileBaru.=$ekstensiGambar;
  move_uploaded_file($tmpName, 'img/'.$namaFileBaru);
  return $namaFileBaru;



}


function hapus($id){
	global $conn;
	mysqli_query($conn,"DELETE FROM mahasiswa WHERE id =$id");
	return mysqli_affected_rows($conn);
}
    function ubah($data){
    global $conn;
    $id = $data["id"];
    $gambarlama =htmlspecialchars($data["gambarlama"]);
    //  cek user sudah pilih atau belum gambar
    if ($_FILES['gambar']['error']===4) {
       $gambar=$gambarlama;
    }else{
      $gambar=upload();
    }
    $nama = htmlspecialchars($data["nama"]);
    $nim =htmlspecialchars($data["nim"]);
    $jurusan =htmlspecialchars($data["jurusan"]);
    $email =htmlspecialchars($data["email"]);
    $query = "UPDATE  mahasiswa SET
            gambar= '$gambar',
            nama= '$nama',
            nim= '$nim',
            jurusan= '$jurusan',
            email='$email'
            WHERE id=$id
            ";
        mysqli_query($conn, $query);
      return mysqli_affected_rows($conn);
    }
    function cari($keyword){
    $query = "SELECT * FROM mahasiswa 
    WHERE
    nama LIKE '%$keyword%' OR 
    nim LIKE '%$keyword%' OR 
    jurusan LIKE '%$keyword%' OR 
    email LIKE '%$keyword%'";
    ;
    return query($query);
}
function registrasi($data){
  global $conn;
  $username=stripcslashes($data["username"]);
  $password=mysqli_real_escape_string($conn,$data["password"]);
  $password2=mysqli_real_escape_string($conn,$data["password2"]);
  // cek username sudah ada belum 
  $result=mysqli_query($conn,"SELECT username FROM user
    WHERE username='$username'");
  if (mysqli_fetch_assoc($result)) {
    echo "
    <script>
    alert('username yang dipilih sudah terdaftar!');
    </script>";
    return false;
  }


  // cek konfirmasi password
  if ($password!==$password2) {
        echo "
    <script>
    alert('konfirmasi password tidak sesuai!');
    </script>";
    return false;
  }
  // enkripsi password
  $password=password_hash($password, PASSWORD_DEFAULT);
  //tambahkanuserbaru ke database
  mysqli_query($conn,"INSERT INTO user
        VALUES('','$username','$password')");
  return mysqli_affected_rows($conn);
}
 ?>