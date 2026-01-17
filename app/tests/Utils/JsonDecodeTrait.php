<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use JsonException;

trait JsonDecodeTrait
{
    /**
     * @throws JsonException
     */
    protected function decodeJson(?string $content): array
    {
        self::assertNotFalse($content);
        self::assertNotNull($content);

        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        return $decoded;
    }
}
