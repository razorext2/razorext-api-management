<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUpTraits()
    {
        $db = config('database.connections.mysql.database');
        if (in_array($db, ['faceid_dev', 'faceid_staging_v2', 'faceid'])) {
            exit("Batal menjalankan test! Database yang aktif di config adalah database development/staging ({$db}) untuk mencegah reset.\n");
        }

        parent::setUpTraits();
    }
}
