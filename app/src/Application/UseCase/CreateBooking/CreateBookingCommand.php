<?php

namespace App\Application\UseCase\CreateBooking;

final class CreateBookingCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly \DateTimeImmutable $date,
        public readonly int $guests
    ) {
    }
}
