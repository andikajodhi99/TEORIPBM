<?php
//koneksi ke database
$db = mysqli_connect("localhost","root","","angkringan");

function query($query){
	global $db;
  	$result= mysqli_query($db,$query);
  	$rows=[];
  	while ($row=mysqli_fetch_assoc($result)) {
  		$rows[]=$row;
  	}
  	return $rows;
     }
     
     function registrasi($data){
      global $db;
      $username=($data["username"]);
      $password=($data["password"]);

      //cek username sudah ada belum 
      $result=mysqli_query($db,"SELECT username FROM adimin
        WHERE username='$username'");
      if (mysqli_fetch_assoc($result)) {
        echo "
        <script>
        alert('username yang dipilih sudah terdaftar!');
        </script>";
        return false;
      }
    
      // enkripsi password
      $password=md5($password);
      //tambahkanuserbaru ke database
      mysqli_query($db,"INSERT INTO admin
            VALUES('','$username','$password')");
      return mysqli_affected_rows($db);
    }
?>