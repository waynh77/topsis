<?php 
include 'config.php';
 
// mengaktifkan session
session_start();
 
// cek apakah guru telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="sudah_login"){
	header("location:../index.php");
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Master Guru</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" type="image/jpeg" href="logo.jpeg"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
  
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function(){
            $('#menu').load('menu.html');
            $('[data-toggle="tooltip"]').tooltip();   
            $('#MyTable').DataTable( {
                "scrollX": true,
        "order": [[ 0, "desc" ]],

    } );
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
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Input Nilai Guru</h2>
                        <a href="createinputnilai.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New </a>
                    </div>
                    <?php
                    
                    // Attempt select query execution
                    $sql = "SELECT `a`.*,periode.periode,guru.noInduk,guru.namaGuru
                    FROM `nilaihead` AS `a`
                    INNER JOIN guru on a.idguru=guru.id
                    INNER JOIN periode on a.idperiode=periode.id";
                    if($result = mysqli_query($koneksi, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="display" width="100%" id="MyTable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Periode</th>";
                                        echo "<th>Nomor Induk</th>";
                                        echo "<th>Nama Guru</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['periode'] . "</td>";
                                        echo "<td>" . $row['noInduk'] . "</td>";
                                        echo "<td>" . $row['namaGuru'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="readinputnilai.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="updateinputnilai.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="deleteinputnilai.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($koneksi);
                    ?>
                </div>
            </div>        
        </div>
    </div>

                </div>
</body>
</html>


