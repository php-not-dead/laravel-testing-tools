<?php

declare(strict_types=1);

namespace PhpNotDead\LaravelTestingTools\Test\TestHelper;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

// phpcs:ignore

trait MocksCache
{
    protected bool $mockCache = true;

    private function mockCache(): void
    {
        if ($this->mockCache !== true) {
            Cache::clear();

            return;
        }

        $this->app->instance(Cache::class, $this->createMock(Cache::class));
        $this->app->instance(Redis::class, $this->createMock(Redis::class));
    }
}
