<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

/**
 * Boots the Laravel application for tests.
 * Used by TestCase — keeps the bootstrap logic in one place (DRY).
 */
trait CreatesApplication
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }
}
