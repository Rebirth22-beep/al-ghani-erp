<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base class for every backend test.
 *
 * New Feature and Unit tests should extend this class so Laravel boots the app
 * the same way in every test file.
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
