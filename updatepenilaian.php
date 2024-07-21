<?php
// Include config file
require_once "config.php";
 
// mengaktifkan session
session_start();
 
// cek apakah penilaian telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="sudah_login"){
	header("location:../index.php");
} 
// Define variables and initialize with empty values
$kriteria = $keterangan= "";
$bobot=5;
$kriteria_err = $keterangan_err =$bobot_err= "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate no induk
    $input_kriteria = trim($_POST["kriteria"]);
    if(empty($input_kriteria)){
        $kriteria_err = "Please enter kriteria.";
    } else{
        $kriteria = $input_kriteria;
    }
    
    // Validate penilaian name
    $input_keterangan = trim($_POST["keterangan"]);
    if(empty($input_keterangan)){
        $keterangan_err = "Please enter an keterangan.";     
    } else{
        $keterangan = $input_keterangan;
    }
    
    $input_bobot = trim($_POST["bobot"]);
    if(empty($input_bobot)){
        $bobot_err = "Please enter bobot.";     
    } else{
        $bobot = $input_bobot;
    }

    // Check input errors before inserting in database
    if(empty($kriteria_err) && empty($keterangan_err)&& empty($bobot_err)){
        // Prepare an update statement
        $sql = "UPDATE penilaian SET kriteria=?, keterangan=?,bobot=? WHERE id=?";
         
        if($stmt = mysqli_prepare($koneksi, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_kriteria, $param_keterangan,$param_bobot, $param_id);
            
            // Set parameters
            $param_kriteria = $kriteria;
            $param_keterangan = $keterangan;
            $param_bobot=$bobot;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: penilaian.php");
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
        $sql = "SELECT * FROM penilaian WHERE id = ?";
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
                    $kriteria = $row["kriteria"];
                    $keterangan = $row["keterangan"];
                    $bobot = $row["bobot"];
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
        // mysqli_close($koneksi);
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
                    <h2 class="mt-5">Update Penilaian</h2>
                    <p>Please edit the input values and submit to update the penilaian record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>Kriteria</label>
                            <select name="kriteria" id="kriteria" class="form-control <?php echo (!empty($kriteria_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $kriteria; ?>">
                                <option selected><?php echo $kriteria;?></option>
                                <?php 
                                    $sql=mysqli_query($koneksi,"SELECT * FROM kriteria");
                                    while ($data=mysqli_fetch_array($sql)) {
                                    ?>
                                    <option value="<?=$data['kriteria']?>"><?=$data['kriteria']?></option> 
                                    <?php
                                    }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $kriteria_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <select name="keterangan" id="keterangan" class="form-control <?php echo (!empty($keterangan_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $keterangan; ?>" onchange="myFunc()">
                            <option selected><?php echo $keterangan;?></option>
                                    <option value="Sangat Baik">Sangat Baik</option> 
                                    <option value="Baik">Baik</option> 
                                    <option value="Cukup">Cukup</option> 
                                    <option value="Kurang">Kurang</option> 
                                    <option value="Sangat Kurang">Sangat Kurang</option>                                 

                                </select>
                            <span class="invalid-feedback"><?php echo $keterangan_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Bobot</label>
                            <input type="number" name="bobot" id="bobot" class="form-control <?php echo (!empty($bobot_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bobot; ?>" min='1'max='5' >
                            <span class="invalid-feedback"><?php echo $bobot_err;?></span>
                        </div>                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="penilaian.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    </div>
</body>
</html>

<script>
function myFunc() {
  let x = document.getElementById("keterangan").value;
//   alert(x);
  let y=0;
  switch(x){
    case "Sangat Baik":
        y=5;
        break;
        case "Baik":
            y=4;
            break
            case "Cukup":
                y=3;
                break;
                case 'Kurang':
                y=2;
                break;
                default:
                    y=1;
  }
  document.getElementById("bobot").value = y;  
}
</script>