<?php
// Include config file
require_once "config.php";
 // mengaktifkan session
session_start();
 
// cek apakah guru telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="sudah_login"){
	header("location:../index.php");
}
// Define variables and initialize with empty values
$noinduk = $namaguru= "";
$noinduk_err = $namaguru_err  = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate noinduk
    $input_noinduk = trim($_POST["noinduk"]);
    if(empty($input_noinduk)){
        $noinduk_err = "Please enter no induk.";
    } else{
        $noinduk = $input_noinduk;
    }
    
    // Validate guru noinduk
    $input_namaguru = trim($_POST["namaguru"]);
    if(empty($input_namaguru)){
        $namaguru_err = "Please enter nama guru.";     
    } else{
        $namaguru = $input_namaguru;
    }
    
    // Check input errors before inserting in database
    if(empty($noinduk_err) && empty($namaguru_err) ){
        $result = mysqli_query($koneksi,"SELECT * FROM guru where noinduk='$noinduk'");
        $cek = mysqli_num_rows($result);
 
        if($cek == 0) {        // Prepare an insert statement
            $sql = "INSERT INTO guru (noinduk, namaguru) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($koneksi, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_noinduk, $param_namaguru);
                
                // Set parameters
                $param_noinduk = $noinduk;
                $param_namaguru = $namaguru;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    header("location: guru.php");
                    echo '<script type="text/javascript">alert("Berhasil tambah guru!!!");</script>';
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
        // Close statement
        mysqli_stmt_close($stmt);
            
            }
        }else{
                echo '<script type="text/javascript">alert("No induk sudah ada!!!");</script>';
            }
    } 
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create guru</title>
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
                    <h2 class="mt-5">Create Guru</h2>
                    <p>Please fill this form and submit to add guru record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nomor Induk</label>
                            <input type="text" name="noinduk" class="form-control <?php echo (!empty($noinduk_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $noinduk; ?>">
                            <span class="invalid-feedback"><?php echo $noinduk_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Nama Guru</label>
                            <input type="text" name="namaguru" class="form-control <?php echo (!empty($namaguru_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $namaguru; ?>">
                            <span class="invalid-feedback"><?php echo $namaguru_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="guru.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    </div>
</body>
</html>

