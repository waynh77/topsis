<?php
// Include config file
require_once "config.php";
 // mengaktifkan session
session_start();
 
// cek apakah kriteria telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="sudah_login"){
	header("location:../index.php");
}
// Define variables and initialize with empty values
$kode = $kriteria= "";
$bobot=0;
$kode_err = $kriteria_err  = "";
 $bobot_err=0;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate kode
    $input_kode = trim($_POST["kode"]);
    if(empty($input_kode)){
        $kode_err = "Please enter kode.";
    } else{
        $kode = $input_kode;
    }
    
    // Validate kriteria kode
    $input_kriteria = trim($_POST["kriteria"]);
    if(empty($input_kriteria)){
        $kriteria_err = "Please enter nama kriteria.";     
    } else{
        $kriteria = $input_kriteria;
    }
    
    // Validate kriteria kode
    $input_bobot = trim($_POST["bobot"]);
    if(empty($input_bobot)){
        $bobot_err = "Please enter bobot.";     
    } else{
        $bobot = $input_bobot;
    }

    // Check input errors before inserting in database
    if(empty($kode_err) && empty($kriteria_err)&& empty($bobot_err) ){
        $result = mysqli_query($koneksi,"SELECT * FROM kriteria where kode='$kode'");
        $cek = mysqli_num_rows($result);
 
        if($cek == 0) {        // Prepare an insert statement
            $sql = "INSERT INTO kriteria (kode, kriteria,bobot) VALUES (?, ?,?)";
            
            if($stmt = mysqli_prepare($koneksi, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssi", $param_kode, $param_kriteria,$param_bobot);
                
                // Set parameters
                $param_kode = $kode;
                $param_kriteria = $kriteria;
                $param_bobot=$bobot;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    header("location: kriteria.php");
                    echo '<script type="text/javascript">alert("Berhasil tambah kriteria penilaian!!!");</script>';
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
        // Close statement
        mysqli_stmt_close($stmt);
            
            }
        }else{
                echo '<script type="text/javascript">alert("Kode sudah ada!!!");</script>';
            }
    } 
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create kriteria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" type="image/jpeg" href="logo.jpeg"/>
    <script>
        $(document).ready(function(){
            $('#menu').load('menu.html');
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
<div id="menu"></div>

<div class="main">

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="mt-5">Create kriteria</h2>
                    <p>Please fill this form and submit to add kriteria record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" name="kode" class="form-control <?php echo (!empty($kode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $kode; ?>">
                            <span class="invalid-feedback"><?php echo $kode_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Nama Kriteria Nilai</label>
                            <input type="text" name="kriteria" class="form-control <?php echo (!empty($kriteria_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $kriteria; ?>">
                            <span class="invalid-feedback"><?php echo $kriteria_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Bobot</label>
                            <input type="number" name="bobot" class="form-control <?php echo (!empty($bobot_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bobot; ?>">
                            <span class="invalid-feedback"><?php echo $bobot_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="kriteria.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    </div>
</body>
</html>

