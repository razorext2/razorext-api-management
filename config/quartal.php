<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Quartal (Quarter) Configuration
    |--------------------------------------------------------------------------
    |
    | Definisi periode quartal custom untuk perhitungan poin teknisi.
    | - start_month / start_day : tanggal mulai quartal
    | - end_month / end_day     : tanggal akhir quartal
    | - cross_year              : true jika start_month berada di tahun sebelumnya
    |
    | Contoh: Q1 dimulai 26 Desember (tahun-1) s/d 25 Maret (tahun ini)
    |
    */

    'quarters' => [
        1 => ['start_month' => 12, 'start_day' => 26, 'end_month' => 3, 'end_day' => 25, 'cross_year' => true],
        2 => ['start_month' => 3, 'start_day' => 26, 'end_month' => 6, 'end_day' => 25, 'cross_year' => false],
        3 => ['start_month' => 6, 'start_day' => 26, 'end_month' => 9, 'end_day' => 25, 'cross_year' => false],
        4 => ['start_month' => 9, 'start_day' => 26, 'end_month' => 12, 'end_day' => 25, 'cross_year' => false],
    ],

];
