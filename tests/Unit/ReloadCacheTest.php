<?php

namespace Tests\Unit;

use App\Console\Commands\ReloadCacheCommand;
use Tests\TestCase;

class ReloadCacheTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(ReloadCacheCommand::class));
    }
}
