<?php

declare(strict_types=1);

namespace Tests\Feature;

use Elph\LaravelTestingTools\Test\TestCase\FeatureTestCase;

class ExampleFeatureTest extends FeatureTestCase
{
    /**
     * A basic test example.
     */
    public function testApplicationReturnsSuccessfulResponse(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertContent('ok');
    }
}
