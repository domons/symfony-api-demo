<?php

declare(strict_types=1);

namespace App\Tests\Utils;

trait ValidatorAssertionsTrait
{
    protected function assertViolationFor(
        iterable $violations,
        string $property,
        ?string $expectedCode = null,
    ): void {
        foreach ($violations as $violation) {
            if ($violation->getPropertyPath() !== $property) {
                continue;
            }

            if (null !== $expectedCode && $violation->getCode() !== $expectedCode) {
                continue;
            }

            $this->addToAssertionCount(1);

            return;
        }

        $this->fail(sprintf(
            'Expected violation for property "%s"%s',
            $property,
            $expectedCode ? ' with code '.$expectedCode : ''
        ));
    }
}
