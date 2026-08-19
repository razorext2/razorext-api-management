<?php

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\RateLimiterServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

return [
    AppServiceProvider::class,
    HorizonServiceProvider::class,
    RateLimiterServiceProvider::class,
    DataTablesServiceProvider::class,
];
