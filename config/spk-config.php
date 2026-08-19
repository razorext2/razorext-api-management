<?php

return [
    'spk_tipe_tagihan' => [
        'idcnon' => [
            'label' => 'IDC Non PPN',
            'value' => 'idcnon',
            'api' => 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=fetchSR',
            'api_sisa' => 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=fetchSisa',
        ],
        'idcppn' => [
            'label' => 'IDC PPN',
            'value' => 'idcppn',
            'api' => 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=fetchSR3',
            'api_sisa' => 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=fetchSisa1',
        ],
        'idyppn' => [
            'label' => 'IDY PPN',
            'value' => 'idyppn',
            'api' => 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=fetchSR2',
            'api_sisa' => 'https://indodacin.nusa.net.id/web/finger/secureapi.php?tipe=fetchSisa2',
        ],
    ],
    'satuan' => [
        'blok' => 'Blok',
        'buah' => 'Buah',
        'cd' => 'CD',
        'gulung' => 'Gulung',
        'koli' => 'Koli',
        'lot' => 'Lot',
        'meter' => 'Meter',
        'pcs' => 'Pcs',
        'peti' => 'Peti',
        'potong' => 'Potong',
        'section' => 'Section',
        'set' => 'Set',
        'unit' => 'Unit',
        'package' => 'Package',
    ],
    'tipe_timbangan' => [
        'non timbangan jembatan' => 'Timbangan Lainnya',
        'timbangan jembatan' => 'Timbangan Jembatan',
    ],
    'tipe_dokumen' => [
        'penawaran' => 'Dokumen Penawaran',
        'draft_kontrak' => 'Draft Kontrak',
        'kontrak' => 'Kontrak',
        'po_customer' => 'PO Customer',
        'request_fondasi' => 'Request Fondasi / Ukuran',
    ],
];
