<?php

use LDAP\Result;

require_once "config.php";

// mengaktifkan session
session_start();

// cek apakah user telah login, jika belum login maka di alihkan ke halaman login
if ($_SESSION['status'] != "sudah_login") {
    header("location:../index.php");
}
$idperiod = $_POST['idperiod'];
$sql = "SELECT periode.periode,guru.noInduk,guru.namaGuru,a.idperiode,
    MAX(CASE WHEN nilaidetail.kriteria='loyalitas' then nilaidetail.nilai END) 'loyalitas',
    MAX(CASE WHEN nilaidetail.kriteria='supervisi' then nilaidetail.nilai END) 'supervisi',
    MAX(CASE WHEN nilaidetail.kriteria='teladan' then nilaidetail.nilai END) 'teladan',
    MAX(CASE WHEN nilaidetail.kriteria='administrasi' then nilaidetail.nilai END) 'administrasi',
    MAX(CASE WHEN nilaidetail.kriteria='kehadiran' then nilaidetail.nilai END) 'kehadiran'
    FROM `nilaihead` AS `a` 
    INNER JOIN guru on a.idguru=guru.id
    INNER JOIN periode on a.idperiode=periode.id
    INNER JOIN nilaidetail on a.id=nilaidetail.idhead
    WHERE a.idperiode=" . $idperiod . " GROUP BY periode.periode,guru.noInduk,guru.namaGuru,a.idperiode";

$x1 = $x2 = $x3 = $x4 = $x5 = 0;
$r = 0;
$output_string = "<h3><i>Hasil Perhitungan</i></h3>";
$output_string .= "<h4>Data Nilai</h4>";
$output_string2 = "<h5><i><u>Perhitungan 2</u></i></h5>";
if ($result = mysqli_query($koneksi, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        $output_string .= '<table class="display" width="100%" id="MyTable1">';
        $output_string .= "<thead>\n";
        $output_string .= "<tr>\n";
        $output_string .= "<th>Nomor Induk</th>\n";
        $output_string .= "<th>Nama Guru</th>\n";
        $output_string .= "<th>Loyalitas</th>\n";
        $output_string .= "<th>Supervisi</th>\n";
        $output_string .= "<th>Teladan</th>\n";
        $output_string .= "<th>Administrasi</th>\n";
        $output_string .= "<th>Kehadiran</th>\n";
        $output_string .= "</tr>\n";
        $output_string .= "</thead>\n";
        $output_string .= "<tbody>";
        while ($row = mysqli_fetch_array($result)) {
            $output_string .= "<tr>\n";
            $output_string .= "<td>" . $row['noInduk'] . "</td>\n";
            $output_string .= "<td>" . $row['namaGuru'] . "</td>\n";
            $output_string .= "<td>" . $row['loyalitas'] . "</td>\n";
            $output_string .= "<td>" . $row['supervisi'] . "</td>\n";
            $output_string .= "<td>" . $row['teladan'] . "</td>\n";
            $output_string .= "<td>" . $row['administrasi'] . "</td>\n";
            $output_string .= "<td>" . $row['kehadiran'] . "</td>\n";
            $output_string .= "</tr>\n";
            $x1 += $row['loyalitas'] * $row['loyalitas'];
            $x2 += $row['teladan'] * $row['teladan'];
            $x3 += $row['kehadiran'] * $row['kehadiran'];
            $x4 += $row['administrasi'] * $row['administrasi'];
            $x5 += $row['supervisi'] * $row['supervisi'];
        }
        $output_string .= "</tbody>\n";
        $output_string .= "</table>";
        $output_string .= "<h5><i><u>Perhitungan 1</u></i></h5>";
        $output_string .= "<h5>X1=" . $x1 . ", X2=" . $x2 . ", X3=" . $x3 . ", X4=" . $x4 . ", X5=" . $x5 . "</h5><br>";

        $result2 = mysqli_query($koneksi, $sql);
        while ($row = mysqli_fetch_array($result2)) {
            $r++;
            $output_string2 .= "<p>R1" . $r . " = " . $row['loyalitas'] / $x1 . ", R2" . $r . " = " . $row['teladan'] / $x2 . ", R3" . $r . " = " . $row['kehadiran'] / $x3 .
                ", R4" . $r . " = " . $row['administrasi'] / $x4 . ", R5" . $r . " = " . $row['supervisi'] / $x5 . "</p>";
        }
        $output_string .= $output_string2;

        $maxy1 = $maxy2 = $maxy3 = $maxy4 = $maxy5 = 0;
        $miny1 = $miny2 = $miny3 = $miny4 = $miny5 = 0;
        $y = $y1 = $y2 = $y3 = $y4 = $y5 = 0;
        $y11 = $y12 = $y13 = $y14 = $y15 = 0;
        $y21 = $y22 = $y23 = $y24 = $y25 = 0;
        $y31 = $y32 = $y33 = $y34 = $y35 = 0;
        $y41 = $y42 = $y43 = $y44 = $y45 = 0;
        $y51 = $y52 = $y53 = $y54 = $y55 = 0;
        $str1 = $str2 = $str3 = $str4 = $str5 = "";
        $output_string3 = "<h5><i><u>Perhitungan 3</u></i></h5>";

        $sql2 = "select * from kriteria order by id";
        $result1 = mysqli_query($koneksi, $sql2);
        
        while ($row1 = mysqli_fetch_array($result1)) {
            if ($row1['kriteria'] == 'Loyalitas') {
                $maxy1 = 0;
                $y = 1;
                $r = 0;
                $result3 = mysqli_query($koneksi, $sql);
                while ($row2 = mysqli_fetch_array($result3)) {
                    if ($r == 0) {
                        $miny1 = $row1['bobot'] * $row2['loyalitas'] / $x1;
                    }
                    $r++;
                    $y1 = $row1['bobot'] * $row2['loyalitas'] / $x1;
                    if ($y1 > $maxy1) {
                        $maxy1 = $y1;
                    }
                    if ($y1 < $miny1) {
                        $miny1 = $y1;
                    }
                    switch ($y) {
                        case 1:
                            $y11 = $y1;
                            break;
                        case 2:
                            $y12 = $y1;
                            break;
                        case 3:
                            $y13 = $y1;
                            break;
                        case 4:
                            $y14 = $y1;
                            break;
                        case 5:
                            $y15 = $y1;
                            break;
                    }
                    $y++;
                    $str1 .= " ".$y1;
                }
            }
            if ($row1['kriteria'] == 'Teladan') {
                $maxy2 = 0;
                $y = 1;
                $r = 0;
                $result2 = mysqli_query($koneksi, $sql);
                while ($row2 = mysqli_fetch_array($result2)) {
                    if ($r == 0) {
                        $miny2 = $row1['bobot'] * $row2['teladan'] / $x2;
                    }
                    $r++;
                    $y2 = $row1['bobot'] * $row2['teladan'] / $x2;
                    if ($y2 > $maxy2) {
                        $maxy2 = $y2;
                    }
                    if ($y2 < $miny2) {
                        $miny2 = $y2;
                    }
                    switch ($y) {
                        case 1:
                            $y21 = $y2;
                            break;
                        case 2:
                            $y22 = $y2;
                            break;
                        case 3:
                            $y23 = $y2;
                            break;
                        case 4:
                            $y24 = $y2;
                            break;
                        case 5:
                            $y25 = $y2;
                            break;
                    }
                    $y++;
                    $str2 .= " ".$y2;
                }
            }
            if ($row1['kriteria'] == 'Kehadiran') {
                $maxy3 = 0;
                $y = 1;
                $r = 0;
                $result2 = mysqli_query($koneksi, $sql);
                while ($row2 = mysqli_fetch_array($result2)) {
                    if ($r == 0) {
                        $miny3 = $row1['bobot'] * $row2['kehadiran'] / $x3;
                    }
                    $r++;
                    $y3 = $row1['bobot'] * $row2['kehadiran'] / $x3;
                    if ($y3 > $maxy3) {
                        $maxy3 = $y3;
                    }
                    if ($y3 < $miny3) {
                        $miny3 = $y3;
                    }
                    switch ($y) {
                        case 1:
                            $y31 = $y3;
                            break;
                        case 2:
                            $y32 = $y3;
                            break;
                        case 3:
                            $y33 = $y3;
                            break;
                        case 4:
                            $y34 = $y3;
                            break;
                        case 5:
                            $y35 = $y3;
                            break;
                    }
                    $y++;
                    $str3 .= " ".$y3;
                }
            }
            if ($row1['kriteria'] == 'Administrasi') {
                $maxy4 = 0;
                $y = 1;
                $r = 0;
                $result2 = mysqli_query($koneksi, $sql);
                while ($row2 = mysqli_fetch_array($result2)) {
                    if ($r == 0) {
                        $miny4 = $row1['bobot'] * $row2['administrasi'] / $x4;
                    }
                    $r++;
                    $y4 = $row1['bobot'] * $row2['administrasi'] / $x4;
                    if ($y4 > $maxy4) {
                        $maxy4 = $y4;
                    }
                    if ($y4 < $miny4) {
                        $miny4 = $y4;
                    }
                    switch ($y) {
                        case 1:
                            $y41 = $y4;
                            break;
                        case 2:
                            $y42 = $y4;
                            break;
                        case 3:
                            $y43 = $y4;
                            break;
                        case 4:
                            $y44 = $y4;
                            break;
                        case 5:
                            $y45 = $y4;
                            break;
                    }
                    $y++;
                    $str4 .= " ".$y4;
                }
            }
            if ($row1['kriteria'] == 'Supervisi') {
                $maxy5 = 0;
                $y = 1;
                $r = 0;
                $result2 = mysqli_query($koneksi, $sql);
                while ($row2 = mysqli_fetch_array($result2)) {
                    if ($r == 0) {
                        $miny5 = $row1['bobot'] * $row2['supervisi'] / $x5;
                    }
                    $r++;
                    $y5 = $row1['bobot'] * $row2['supervisi'] / $x5;
                    if ($y5 > $maxy5) {
                        $maxy5 = $y5;
                    }
                    if ($y5 < $miny5) {
                        $miny5 = $y5;
                    }
                    switch ($y) {
                        case 1:
                            $y51 = $y5;
                            break;
                        case 2:
                            $y52 = $y5;
                            break;
                        case 3:
                            $y53 = $y5;
                            break;
                        case 4:
                            $y54 = $y5;
                            break;
                        case 5:
                            $y55 = $y5;
                            break;
                    }
                    $y++;
                    $str5 .= " ".$y5;
                }
            }
        }
        $output_string3 .= "<p>Y1 " . $str1 . ", Max = " . $maxy1 . ", Min = " . $miny1. "</p>";
        $output_string3 .= "<p>Y2 " . $str2 . ", Max = " . $maxy2 . ", Min = " . $miny2. "</p>";
        $output_string3 .= "<p>Y3 " . $str3 . ", Max = " . $maxy3 . ", Min = " . $miny3. "</p>";
        $output_string3 .= "<p>Y4 " . $str4 . ", Max = " . $maxy4 . ", Min = " . $miny4. "</p>";
        $output_string3 .= "<p>Y5 " . $str5 . ", Max = " . $maxy5 . ", Min = " . $miny5. "</p>";

        $output_string .= $output_string3;

        $output_string4 = "<h5><i><u>Perhitungan 4</u></i></h5>";
        $d1max=$d2max=$d3max=$d4max=$d5max=0;
        $d1min=$d2min=$d3min=$d4min=$d5min=0;

        $d1max=(pow($maxy1-$y11,2))+(pow($maxy2-$y21,2))+(pow($maxy3-$y31,2))+(pow($maxy4-$y41,2))+(pow($maxy5-$y51,2));
        $d2max=(pow($maxy1-$y12,2))+(pow($maxy2-$y22,2))+(pow($maxy3-$y32,2))+(pow($maxy4-$y42,2))+(pow($maxy5-$y52,2));
        $d3max=(pow($maxy1-$y13,2))+(pow($maxy2-$y23,2))+(pow($maxy3-$y33,2))+(pow($maxy4-$y43,2))+(pow($maxy5-$y53,2));
        $d4max=(pow($maxy1-$y14,2))+(pow($maxy2-$y24,2))+(pow($maxy3-$y34,2))+(pow($maxy4-$y44,2))+(pow($maxy5-$y54,2));
        $d5max=(pow($maxy1-$y15,2))+(pow($maxy2-$y25,2))+(pow($maxy3-$y35,2))+(pow($maxy4-$y45,2))+(pow($maxy5-$y55,2));

        $d1min=(pow($miny1-$y11,2))+(pow($miny2-$y21,2))+(pow($miny3-$y31,2))+(pow($miny4-$y41,2))+(pow($miny5-$y51,2));
        $d2min=(pow($miny1-$y12,2))+(pow($miny2-$y22,2))+(pow($miny3-$y32,2))+(pow($miny4-$y42,2))+(pow($miny5-$y52,2));
        $d3min=(pow($miny1-$y13,2))+(pow($miny2-$y23,2))+(pow($miny3-$y33,2))+(pow($miny4-$y43,2))+(pow($miny5-$y53,2));
        $d4min=(pow($miny1-$y14,2))+(pow($miny2-$y24,2))+(pow($miny3-$y34,2))+(pow($miny4-$y44,2))+(pow($miny5-$y54,2));
        $d5min=(pow($miny1-$y15,2))+(pow($miny2-$y25,2))+(pow($miny3-$y35,2))+(pow($miny4-$y45,2))+(pow($miny5-$y55,2));

        $output_string4 .= "<p>D1+   " . $d1max. "</p>";
        $output_string4 .= "<p>D2+   " . $d2max. "</p>";
        $output_string4 .= "<p>D3+   " . $d3max. "</p>";
        $output_string4 .= "<p>D4+   " . $d4max. "</p>";
        $output_string4 .= "<p>D5+   " . $d5max. "</p><br>";

        $output_string4 .= "<p>D1-   " . $d1min. "</p>";
        $output_string4 .= "<p>D2-   " . $d2min. "</p>";
        $output_string4 .= "<p>D3-   " . $d3min. "</p>";
        $output_string4 .= "<p>D4-   " . $d4min. "</p>";
        $output_string4 .= "<p>D5-   " . $d5min. "</p><br>";

        $output_string .= $output_string4;

        $output_string5 = "<h5><i><u>Perhitungan 5/Hasil Perhitungan Topsis</u></i></h5>";
        $v1=$v2=$v3=$v4=$v5=0;
        $v1=$d1min/($d1min+$d1max);
        $v2=$d2min/($d2min+$d2max);
        $v3=$d3min/($d3min+$d3max);
        $v4=$d4min/($d4min+$d4max);
        $v5=$d5min/($d5min+$d5max);

        $result3 = mysqli_query($koneksi, $sql);
        $r=0;

        $output_string5 .= '<table class="display" width="100%" id="MyTable3">';
        $output_string5 .= "<thead>\n";
        $output_string5 .= "<tr>\n";
        $output_string5 .= "<th>Nomor Induk</th>\n";
        $output_string5 .= "<th>Nama Guru</th>\n";
        $output_string5 .= "<th>Hasil Topsis</th>\n";
        $output_string5 .= "</tr>\n";
        $output_string5 .= "</thead>\n";
        $output_string5 .= "<tbody>";
        while ($row = mysqli_fetch_array($result3)) {
            $r++;
            $output_string5 .= "<tr>\n";
            $output_string5 .= "<td>" . $row['noInduk'] . "</td>\n";
            $output_string5 .= "<td>" . $row['namaGuru'] . "</td>\n";
            $output_string5 .= "<td>" . ${"v".$r} . "</td>\n";
            $output_string5 .= "</tr>\n";
        }
        $output_string5 .= "</tbody>\n";
        $output_string5 .= "</table><br><br>";

        $output_string .= $output_string5;

        // Free result set
        mysqli_free_result($result);
        echo json_encode($output_string);
    } else {
        $output_string = '<h3>Topsis tidak dapat diproses karena data kosong, silahkan input data terlebih dahulu</h3>';
        echo json_encode($output_string);
    }
} else {
    echo "Oops! Something went wrong. Please try again later.";
}
// Close connection
mysqli_close($koneksi);
