<?php

declare(strict_types=1);

namespace App\Tests\Utils;

trait ProblemDetailsAssertionsTrait
{
    protected function assertPayloadHasViolation(array $payload, string $propertyPath): void
    {
        $this->assertArrayHasKey('violations', $payload);
        $this->assertIsArray($payload['violations']);

        foreach ($payload['violations'] as $violation) {
            if (($violation['propertyPath'] ?? null) === $propertyPath) {
                $this->addToAssertionCount(1);

                return;
            }
        }

        $this->fail(sprintf('Expected violation for "%s".', $propertyPath));
    }
}
