<?php
include 'config.php';

// mengaktifkan session
session_start();

// cek apakah kriteria telah login, jika belum login maka di alihkan ke halaman login
if ($_SESSION['status'] != "sudah_login") {
    header("location:../index.php");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script language="JavaScript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Topsis</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" type="image/jpeg" href="logo.jpeg" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
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
                        <div class="mt-5 mb-3 clearfix">
                            <h2>Perhitungan Topsis</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select name="periode" id="periode" class="form-control <?php echo (!empty($periode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $periode; ?>">
                                <!-- <option disabled selected> Select periode </option> -->
                                <?php
                                $sql1 = mysqli_query($koneksi, "SELECT * FROM periode");
                                while ($data = mysqli_fetch_array($sql1)) {
                                ?>
                                    <option value="<?= $data['id'] ?>"><?= $data['periode'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $periode_err; ?></span>
                        </div>

                    </div>
                    <div class="col-md-8">
                        <button class="btn btn-primary" name="showData" id="getdata"><i class="fa fa-eye"></i> Show Data Nilai </button>
                        <button class="btn btn-danger" id="showtopsis"><i class="fa fa-check"></i> Proses hitung Topsis </button>
                        <button class="btn btn-success" onclick="refresh()"><i class="fa fa-refresh"></i> Refresh Perhitungan </button>

                    </div>
                </div>
                <hr>
                <div id="result_table">
                    <p>Silahkan pilih periode kemudian klik tombol <b>Show Data Nilai</b> untuk menampilkan data nilai yang telah di input
                        atau klik tombol <b>Proses Hitung Topsis</b> untuk mengetahui ranking guru atau klik tombol <b>Refresh Perhitungan</b>
                        untuk mengosongkan data
                    </p>
                </div>
                <div id="result_topsis"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        $('#getdata').click(function() {
            let idperiod = document.getElementById('periode').value;
            refresh();
            $.ajax({
                url: "showdata.php",
                type: 'POST',
                dataType: 'json',
                data: 'idperiod=' + idperiod,
                success: function(output_string) {
                    $("#result_table").append(output_string);
                    $('#MyTable').DataTable({
                        "scrollX": true,
                        "order": [
                            [0, "asc"]
                        ],

                    });
                } // End of success function of ajax form
            }); // End of ajax call	
        });

        function refresh() {
            $("#result_table").empty();
            $("#result_topsis").empty();
        }

        $('#showtopsis').click(function() {
            let idperiod = document.getElementById('periode').value;
            refresh();
            $.ajax({
                url: "showtopsis.php",
                type: 'POST',
                dataType: 'json',
                data: 'idperiod=' + idperiod,
                success: function(output_string2) {
                    $("#result_topsis").append(output_string2);
                    $('#MyTable1').DataTable({
                        "scrollX": true,
                        "order": [
                            [0, "asc"]
                        ],
                    });
                    $('#MyTable3').DataTable({
                        "scrollX": true,
                        "order": [
                            [2, "desc"]
                        ],
                    });
                } // End of success function of ajax form
            }); // End of ajax call	
        });
    </script>
</body>

</html>