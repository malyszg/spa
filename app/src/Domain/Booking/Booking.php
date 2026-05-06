<?php

namespace App\Domain\Booking;

final class Booking
{
    private function __construct(
        private string $name,
        private string $email,
        private \DateTimeImmutable $date,
        private int $guests,
        private \DateTimeImmutable $createdAt
    ) {
    }

    public static function reserve(
        string $name,
        string $email,
        \DateTimeImmutable $date,
        int $guests
    ): self {
        $today = new \DateTimeImmutable('today');
        if ($date < $today) {
            throw new \InvalidArgumentException('Booking date cannot be in the past.');
        }

        if ($guests < 1 || $guests > 12) {
            throw new \InvalidArgumentException('Guests count must be between 1 and 12.');
        }

        return new self(
            trim($name),
            strtolower(trim($email)),
            $date,
            $guests,
            new \DateTimeImmutable()
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function guests(): int
    {
        return $this->guests;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
