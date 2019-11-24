<?php
require 'config.php';
   if(isset($_POST["register"])){
      if(registrasi($_POST)> 0){
         
         header("location:login.html");
      }
      else{
         header("location:login.html");
      }
   }
?>