<?php

/** Goal: Menguji bahwa event TableRefreshed di-dispatch secara otomatis saat model-model yang di-map mengalami event saved atau deleted, Caller: pest, Deps: TableRefreshed, Event, Sales */

use App\Events\TableRefreshed;
use App\Models\Pegawai;
use App\Models\Sales;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Pegawai::firstOrCreate([
        'kode_pegawai' => '394',
        'nik_pegawai' => '394-NIK',
        'full_name' => 'KEVIN FRANSETIO',
    ]);
});

it('dispatches TableRefreshed event when a mapped model is saved', function () {
    Event::fake([TableRefreshed::class]);

    Sales::create([
        'kode_pegawai' => '394',
        'title' => 'Test Sales Report Event',
        'customer_name' => 'Jane Doe',
        'customer_telp' => '08123456789',
        'lokasi' => 'Test Location Event',
        'keterangan' => 'Test Description Event',
        'longitude' => '100.0',
        'latitude' => '10.0',
        'status' => 0,
        'customer_make_order' => 1,
    ]);

    Event::assertDispatched(TableRefreshed::class, function ($event) {
        return $event->tableName === 'SalesTable';
    });
});

it('dispatches TableRefreshed event when a mapped model is deleted', function () {
    $sales = Sales::create([
        'kode_pegawai' => '394',
        'title' => 'Test Sales Report Event Delete',
        'customer_name' => 'Jane Doe',
        'customer_telp' => '08123456789',
        'lokasi' => 'Test Location Event',
        'keterangan' => 'Test Description Event',
        'longitude' => '100.0',
        'latitude' => '10.0',
        'status' => 0,
        'customer_make_order' => 1,
    ]);

    Event::fake([TableRefreshed::class]);

    $sales->delete();

    Event::assertDispatched(TableRefreshed::class, function ($event) {
        return $event->tableName === 'SalesTable';
    });
});
