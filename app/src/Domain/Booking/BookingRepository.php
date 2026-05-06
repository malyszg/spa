<?php

namespace App\Domain\Booking;

interface BookingRepository
{
    public function add(Booking $booking): void;
}
