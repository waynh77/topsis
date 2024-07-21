<!DOCTYPE html>
<html>
<head>
	<title>Nilai Guru</title>
	<link rel="stylesheet" type="text/css" href="styleLogin.css">
    <link rel="shortcut icon"  href="logo.ico"/>
<body>
	<br/>
	<br/>
	<center><h1>Sistem Penilaian Pemilihan Guru Prestasi</h1></center>	
	<center><h1>Dengan Metode Topsis</h1></center>	
	<div class="login">
	<center><img src="school.png" alt="logo" width="150" height="200"></center>
		<form action="login.php" method="post" onSubmit="return validasi()">
			<div>
				<label>Username:</label>
				<input type="text" name="username" id="username" />
			</div>
			<div>
				<label>Password:</label>
				<input type="password" name="password" id="password" />
			</div>			
			<div>
				<center>
				<input type="submit" value="Login" class="tombol">
</center>
			</div>
		</form>
		<?php if(isset($_GET['pesan'])) {  ?>
	<label style="color:red;"><?php echo $_GET['pesan']; ?></label>
	<?php } ?>	
	</div>
</body>
 
<script type="text/javascript">
	function validasi() {
		var username = document.getElementById("username").value;
		var password = document.getElementById("password").value;		
		if (username != "" && password!="") {
			return true;
		}else{
			alert('Username dan Password harus di isi !');
			return false;
		}
	}
 
</script>
</html>