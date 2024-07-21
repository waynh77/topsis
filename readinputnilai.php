<?php
 require_once "config.php";
 
 // mengaktifkan session
 session_start();
  
 // cek apakah penilaian telah login, jika belum login maka di alihkan ke halaman login
 if($_SESSION['status'] !="sudah_login"){
     header("location:../index.php");
 }// Check existence of id parameter before processing further
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
                $noinduk = $data2["noInduk"];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Input Nilai</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>    
    <link rel="stylesheet" type="text/css" href="style.css">
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
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Input Nilai</h1>
                    <div class="form-group">
                        <label>Periode</label>
                        <p><b><?php echo $periode1; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>No Induk</label>
                        <p><b><?php echo $noinduk; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Nama Guru</label>
                        <p><b><?php echo $guru1; ?></b></p>
                    </div>
                    <h3>Nilai Guru</h3>
                        <div class="form-group">
                            <?php 
                                    $sql3=mysqli_query($koneksi,"SELECT * FROM kriteria");
                                    while ($data=mysqli_fetch_array($sql3)) {
                                    ?>
                                        <div class="form-group">
                                            <label>Nilai <?=$data['kriteria']?></label>
                                            <p><b><?php echo ${$data['kriteria']}; ?></b></p>
                                        </div>
                                    <?php
                                    }
                                ?>
                        </div>
                    <p><a href="inputnilai.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
    </div>
</body>
</html>
