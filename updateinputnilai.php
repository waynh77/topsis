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
$periode = $guru= 0;
$periode_err = $guru_err  = "";
 
$sql=mysqli_query($koneksi,"SELECT * FROM kriteria");
while ($data=mysqli_fetch_array($sql)) {
    ${$data['kriteria']}=5;
    ${$data['kriteria'].'_err'}="";
}
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate periode
    $input_periode = trim($_POST["periode"]);
    if(empty($input_periode)){
        $periode_err = "Please enter periode.";
    } else{
        $periode = $input_periode;
    }    

// Validate penilaian periode
    $input_guru = trim($_POST["guru"]);
    if(empty($input_guru)){
        $guru_err = "Please enter guru.";
    } else{
        $guru = $input_guru;
    }    

    $sql=mysqli_query($koneksi,"SELECT * FROM kriteria");
    while ($data=mysqli_fetch_array($sql)) {
        ${'input_'.$data['kriteria']} = $_POST[$data['kriteria']];
        if(empty(${'input_'.$data['kriteria']})){
            ${$data['kriteria'].'_err'} = "Please enter nilai ".$data['kriteria'];
        } else{
            ${$data['kriteria']} = ${'input_'.$data['kriteria']};
        }   
    }

    // Check input errors before inserting in database
    if(empty($periode_err) && empty($guru_err) ){
        // Prepare an update statement
        $sql = "UPDATE nilaihead SET idperiode=?, idguru=? WHERE id=?";
         
        if($stmt = mysqli_prepare($koneksi, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iii", $param_periode, $param_guru,$param_id);
                
            // Set parameters
            $param_periode = $periode;
            $param_guru = $guru;
            $param_id=$id;
        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    $idhead=$id;

                    $sql=mysqli_query($koneksi,"SELECT * FROM nilaidetail");
                    while ($data=mysqli_fetch_array($sql)) {
                        $kriteria=$data['kriteria'];
                        $nilai=${$data['kriteria']};
                        $sql2 = "UPDATE nilaidetail SET nilai=? WHERE idhead=? and kriteria=?";

                        if($stmt2 = mysqli_prepare($koneksi, $sql2)){
                            mysqli_stmt_bind_param($stmt2, "iis", $param_nilai,$param_idhead, $param_kriteria);
                
                            // Set parameters
                            $param_idhead = $idhead;
                            $param_kriteria = $kriteria;
                            $param_nilai=$nilai;
                            if(mysqli_stmt_execute($stmt2)){
                                echo '<script type="text/javascript">alert("Berhasil update nilai!!!");</script>';                            
                            }else{
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }
                    }
                    header("location: inputnilai.php");
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
}else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM nilaihead WHERE id = ?";
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
                    $periode = $row["idperiode"];
                    $guru = $row["idguru"];
                    $sql2=mysqli_query($koneksi,"SELECT * FROM periode where id=".$row['idperiode']);
                    $data1=mysqli_fetch_assoc($sql2);
                    $periode1 = $data1["periode"];
                    $sql3=mysqli_query($koneksi,"SELECT * FROM guru where id=".$row['idguru']);
                    $data2=mysqli_fetch_assoc($sql3);
                    $guru1 = $data2["namaGuru"];

                    $sql=mysqli_query($koneksi,"SELECT * FROM nilaidetail where idhead=".$id);
                    while ($data=mysqli_fetch_array($sql)) {
                        ${$data['kriteria']}=$data['nilai'];
                    }

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
                    <h2 class="mt-5">Update Input Nilai</h2>
                    <p>Please edit the input values and submit to update the input nilai record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>Pilih Periode</label>
                            <select name="periode" id="periode" class="form-control <?php echo (!empty($periode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $periode; ?>">
                            <option selected value="<?php echo $periode;?>"><?php echo $periode1;?></option>
                                <?php 
                                    $sql1=mysqli_query($koneksi,"SELECT * FROM periode");
                                    while ($data=mysqli_fetch_array($sql1)) {
                                    ?>
                                    <option value="<?=$data['id']?>"><?=$data['periode']?></option> 
                                    <?php
                                    }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $periode_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Pilih Guru</label>
                            <select name="guru" id="guru" class="form-control <?php echo (!empty($guru_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $guru; ?>" >
                            <option selected value="<?php echo $guru;?>"><?php echo $guru1;?></option>
                                <?php 
                                    $sql2=mysqli_query($koneksi,"SELECT * FROM guru");
                                    while ($data=mysqli_fetch_array($sql2)) {
                                    ?>
                                    <option value="<?=$data['id']?>"><?=$data['namaGuru']?></option> 
                                    <?php
                                    }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $guru_err;?></span>
                        </div>
                        <h3>Input Nilai</h3>
                        <div class="form-group">
                            <?php 
                                    $sql3=mysqli_query($koneksi,"SELECT * FROM kriteria");
                                    while ($data=mysqli_fetch_array($sql3)) {
                                    ?>
                                        <div class="form-group">
                                            <label>Nilai <?=$data['kriteria']?></label>
                                            <input type="number" name="<?=$data['kriteria']?>" id="<?=$data['kriteria']?>" class="form-control <?php echo (!empty(${$data['kriteria'].'_err'})) ? 'is-invalid' : ''; ?>" value="<?php echo ${$data['kriteria']}; ?>" min='1'max='5' >
                                            <span class="invalid-feedback"><?php echo ${$data['kriteria'].'_err'};?></span>
                                        </div>
                                    <?php
                                    }
                                ?>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="inputnilai.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    </div>
</body>
</html>