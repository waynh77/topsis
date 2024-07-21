<?php
// Include config file
require_once "config.php";
 // mengaktifkan session
session_start();
 
// cek apakah periode telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="sudah_login"){
	header("location:../index.php");
}
// Define variables and initialize with empty values
$periode =  "";
$periode_err   = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate periode
    $input_periode = trim($_POST["periode"]);
    if(empty($input_periode)){
        $periode_err = "Please enter periode.";
    } else{
        $periode = $input_periode;
    }
    
    // Check input errors before inserting in database
    if(empty($periode_err) ){
        $result = mysqli_query($koneksi,"SELECT * FROM periode where periode='$periode'");
        $cek = mysqli_num_rows($result);
 
        if($cek == 0) {        // Prepare an insert statement
            $sql = "INSERT INTO periode (periode) VALUES (?)";
            
            if($stmt = mysqli_prepare($koneksi, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_periode);
                
                // Set parameters
                $param_periode = $periode;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    header("location: periode.php");
                    echo '<script type="text/javascript">alert("Berhasil tambah periode!!!");</script>';
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
        // Close statement
        mysqli_stmt_close($stmt);
            
            }
        }else{
                echo '<script type="text/javascript">alert("Periode sudah ada!!!");</script>';
            }
    } 
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create periode</title>
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
                    <h2 class="mt-5">Create Periode Penilaian</h2>
                    <p>Please fill this form and submit to add periode record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Periode</label>
                            <input type="text" name="periode" class="form-control <?php echo (!empty($periode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $periode; ?>">
                            <span class="invalid-feedback"><?php echo $periode_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="periode.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    </div>
</body>
</html>

