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
$periode = $guru= 0;
$periode_err = $guru_err  = "";
 
$sql=mysqli_query($koneksi,"SELECT * FROM kriteria");
while ($data=mysqli_fetch_array($sql)) {
    ${$data['kriteria']}=5;
    ${$data['kriteria'].'_err'}="";
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
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
        $result = mysqli_query($koneksi,"SELECT * FROM nilaihead where idperiode='$periode' and idguru='$guru'");
        $cek = mysqli_num_rows($result);
 
        if($cek == 0) {        
            // Prepare an insert statement
            $sql = "INSERT INTO nilaihead (idperiode, idguru) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($koneksi, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ii", $param_periode, $param_guru);
                
                // Set parameters
                $param_periode = $periode;
                $param_guru = $guru;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    $sql1=mysqli_query($koneksi,"SELECT * FROM nilaihead order by id desc limit 1");
                    $data=mysqli_fetch_assoc($sql1);
                    $idhead=$data['id'];

                    $sql=mysqli_query($koneksi,"SELECT * FROM kriteria");
                    while ($data=mysqli_fetch_array($sql)) {
                        $kriteria=$data['kriteria'];
                        $nilai=${$data['kriteria']};
                        $sql2 = "INSERT INTO nilaidetail (idhead, kriteria,nilai) VALUES (?, ?,?)";

                        if($stmt2 = mysqli_prepare($koneksi, $sql2)){
                            mysqli_stmt_bind_param($stmt2, "isi", $param_idhead,$param_kriteria, $param_nilai);
                
                            // Set parameters
                            $param_idhead = $idhead;
                            $param_kriteria = $kriteria;
                            $param_nilai=$nilai;
                            if(mysqli_stmt_execute($stmt2)){
                                echo '<script type="text/javascript">alert("Berhasil tambah nilai!!!");</script>';                            
                            }else{
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }
                        }
                    header("location: inputnilai.php");
                    echo '<script type="text/javascript">alert("Berhasil tambah nilai!!!");</script>';
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
        // Close statement
        mysqli_stmt_close($stmt);
            
            }
        }else{
                echo '<script type="text/javascript">alert("Nilai sudah ada!!!");</script>';
            }
    } 
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Nilai Guru</title>
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
                    <h2 class="mt-5">Create Nilai Guru</h2>
                    <p>Please fill this form and submit to add nilai guru record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                            <label>Pilih Periode</label>
                            <select name="periode" id="periode" class="form-control <?php echo (!empty($periode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $periode; ?>">
                                <!-- <option disabled selected> Select periode </option> -->
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
                                <!-- <option disabled selected> Select periode </option> -->
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
                                    $sql=mysqli_query($koneksi,"SELECT * FROM kriteria");
                                    while ($data=mysqli_fetch_array($sql)) {
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

