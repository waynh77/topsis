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
$kode_err = $kriteria_err = $bobot_err="";

 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate no induk
    $input_kode = trim($_POST["kode"]);
    if(empty($input_kode)){
        $kode_err = "Please enter no induk.";
    } else{
        $kode = $input_kode;
    }
    
    // Validate kriteria name
    $input_kriteria = trim($_POST["kriteria"]);
    if(empty($input_kriteria)){
        $kriteria_err = "Please enter an kriteria name.";     
    } else{
        $kriteria = $input_kriteria;
    }

    $input_bobot = trim($_POST["bobot"]);
    if(empty($input_bobot)){
        $bobot_err = "Please enter bobot.";
    } else{
        $bobot = $input_bobot;
    }

    // Check input errors before inserting in database
    if(empty($kode_err) && empty($kriteria_err)&& empty($bobot_err)){
        // Prepare an update statement
        $sql = "UPDATE kriteria SET kode=?, kriteria=?,bobot=? WHERE id=?";
         
        if($stmt = mysqli_prepare($koneksi, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_kode, $param_kriteria,$param_bobot, $param_id);
            
            // Set parameters
            $param_kode = $kode;
            $param_kriteria = $kriteria;
            $param_bobot=$bobot;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: kriteria.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($koneksi);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM kriteria WHERE id = ?";
        if($stmt = mysqli_prepare($koneksi, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $kode = $row["kode"];
                    $kriteria = $row["kriteria"];
                    $bobot=$row['bobot'];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($koneksi);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update kriteria</h2>
                    <p>Please edit the input values and submit to update the kriteria record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" name="kode" class="form-control <?php echo (!empty($kode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $kode; ?>">
                            <span class="invalid-feedback"><?php echo $kode_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Kriteria Nilai</label>
                            <input type="text" name="kriteria" class="form-control <?php echo (!empty($kriteria_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $kriteria; ?>">
                            <span class="invalid-feedback"><?php echo $kriteria_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Bobot</label>
                            <input type="number" name="bobot" class="form-control <?php echo (!empty($bobot_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bobot; ?>">
                            <span class="invalid-feedback"><?php echo $bobot_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
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

