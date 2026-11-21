<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Pengaman: tes dihentikan jika koneksi database bukan SQLite.
     * Dicek sebelum RefreshDatabase sempat menghapus tabel.
     */
    protected function setUpTraits()
    {
        if (config('database.default') !== 'sqlite') {
            throw new RuntimeException(
                'Tes dihentikan: koneksi database "' . config('database.default') . '" bukan sqlite. ' .
                    'Periksa phpunit.xml dan variabel lingkungan DB_*.'
            );
        }

        return parent::setUpTraits();
    }
}
