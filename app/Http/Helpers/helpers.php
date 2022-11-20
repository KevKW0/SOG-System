<?php

function money_format($number)
{
    return number_format($number, 0, ',', '.');
}

function counted($number)
{
    $number = abs($number);
    $read  = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
    $counted = '';

    if ($number < 12) { // 0 - 11
        $counted = ' ' . $read[$number];
    } elseif ($number < 20) { // 12 - 19
        $counted = counted($number - 10) . ' belas';
    } elseif ($number < 100) { // 20 - 99
        $counted = counted($number / 10) . ' puluh' . counted($number % 10);
    } elseif ($number < 200) { // 100 - 199
        $counted = ' seratus' . counted($number - 100);
    } elseif ($number < 1000) { // 200 - 999
        $counted = counted($number / 100) . ' ratus' . counted($number % 100);
    } elseif ($number < 2000) { // 1.000 - 1.999
        $counted = ' seribu' . counted($number - 1000);
    } elseif ($number < 1000000) { // 2.000 - 999.999
        $counted = counted($number / 1000) . ' ribu' . counted($number % 1000);
    } elseif ($number < 1000000000) { // 1000000 - 999.999.990
        $counted = counted($number / 1000000) . ' juta' . counted($number % 1000000);
    }

    return $counted;
}

function indonesia_date($tgl, $tampil_hari = true)
{
    $nama_hari  = [
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
    ];

    $nama_bulan = [
        1 =>
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $tahun   = substr($tgl, 0, 4);
    $bulan   = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $text    = '';

    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
        $hari        = $nama_hari[$urutan_hari];
        $text       .= "$hari, $tanggal $bulan $tahun";
    } else {
        $text       .= "$tanggal $bulan $tahun";
    }

    return $text;
}

function format_date($tgl, $tampil_hari = true)
{
    $nama_hari  = [
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
    ];

    $nama_bulan = [
        1 =>
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $tanggal = substr($tgl, 0, 2);
    $bulan   = $nama_bulan[(int) substr($tgl, 3, 2)];
    $tahun   = substr($tgl, 4, 4);
    $text    = '';

    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
        $hari        = $nama_hari[$urutan_hari];
        $text       .= "$hari, $tanggal $bulan $tahun";
    } else {
        $text       .= "$tanggal $bulan $tahun";
    }

    return $text;
}

function addZero($value, $threshold = null)
{
    return sprintf("%0" . $threshold . "s", $value);
}
