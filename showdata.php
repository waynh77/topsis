<?php
require_once "config.php";

// mengaktifkan session
session_start();

// cek apakah user telah login, jika belum login maka di alihkan ke halaman login
if ($_SESSION['status'] != "sudah_login") {
    header("location:../index.php");
}
$idperiod = $_POST['idperiod'];
    $sql = "SELECT `a`.*,periode.periode,guru.noInduk,guru.namaGuru,nilaidetail.kriteria,nilaidetail.nilai
                    FROM `nilaihead` AS `a` 
                    INNER JOIN guru on a.idguru=guru.id
                    INNER JOIN periode on a.idperiode=periode.id
                    INNER JOIN nilaidetail on a.id=nilaidetail.idhead
                    WHERE a.idperiode=" . $idperiod." order by a.id";

    $output_string = "<h3>Data Nilai</h3>";

    if ($result = mysqli_query($koneksi, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            $output_string .= '<table class="display" width="100%" id="MyTable">';
            $output_string .= "<thead>\n";
            $output_string .= "<tr>\n";
            $output_string .= "<th>#</th>\n";
            $output_string .= "<th>Periode</th>\n";
            $output_string .= "<th>Nomor Induk</th>\n";
            $output_string .= "<th>Nama Guru</th>\n";
            $output_string .= "<th>Kriteria</th>\n";
            $output_string .= "<th>Nilai</th>\n";
            $output_string .= "</tr>\n";
            $output_string .= "</thead>\n";
            $output_string .= "<tbody>";
            while ($row = mysqli_fetch_array($result)) {
                $output_string .= "<tr>\n";
                $output_string .= "<td>" . $row['id'] . "</td>\n";
                $output_string .= "<td>" . $row['periode'] . "</td>\n";
                $output_string .= "<td>" . $row['noInduk'] . "</td>\n";
                $output_string .= "<td>" . $row['namaGuru'] . "</td>\n";
                $output_string .= "<td>" . $row['kriteria'] . "</td>\n";
                $output_string .= "<td>" . $row['nilai'] . "</td>\n";
                $output_string .= "</tr>\n";
            }
            $output_string .= "</tbody>\n";
            $output_string .= "</table>";
            // Free result set
            mysqli_free_result($result);
            echo json_encode($output_string);
        } else {
            $output_string='<h3>Tidak dapat diproses karena data kosong, silahkan input data terlebih dahulu</h3>';
            echo json_encode($output_string);
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    // Close connection
    mysqli_close($koneksi);

