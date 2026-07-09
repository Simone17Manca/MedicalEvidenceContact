<?php

namespace App\Services\Moodle;

use RuntimeException;
use Throwable;

class MoodleApiException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly string $function,
        public readonly ?string $moodleException = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, previous: $previous);
    }

    public static function connection(string $function, Throwable $previous): self
    {
        return new self($previous->getMessage(), $function, previous: $previous);
    }

    public static function moodle(string $function, string $message, ?string $moodleException = null): self
    {
        return new self($message, $function, $moodleException);
    }
}
