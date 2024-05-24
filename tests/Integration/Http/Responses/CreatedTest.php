<?php

declare(strict_types=1);

namespace Tests\Tempest\Integration\Http\Responses;

use Tempest\Http\Responses\Created;
use Tempest\Http\Status;
use Tests\Tempest\Integration\FrameworkIntegrationTest;

/**
 * @internal
 * @small
 */
class CreatedTest extends FrameworkIntegrationTest
{
    public function test_create(): void
    {
        $response = new Created('test');

        $this->assertEquals(Status::CREATED, $response->getStatus());
        $this->assertEquals('test', $response->getBody());
    }
}
