<?php 
include 'config.php';
 
// mengaktifkan session
session_start();
 
// cek apakah user telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="sudah_login"){
	header("location:../index.php");
}
  
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nilai Guru</title>
    <script src="js/FileSaver.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" type="image/jpeg" href="logo.jpeg"/>
</head>

<body>
<div id="menu"></div>

<div class="main">
<div class="wrapper">
        <div class="container-fluid">
<br>
  <center><h3>Sistem Penilaian Pemilihan Guru Prestasi Dengan Metode Topsis</h3></center>	
  <?php echo "Selamat Datang ". $_SESSION['username'];?>
  <hr>
</div>
</div>
<center>
<img src="sekolah.png" width='100%'/>
</center>
</div>

</body>

<script>
  

$(document).ready(function () {

    //adds menu.html content into any "#menu" element
    $('#menu').load('menu.html');
});  



</script>

