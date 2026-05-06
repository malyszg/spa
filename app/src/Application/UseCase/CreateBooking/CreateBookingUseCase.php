<?php

namespace App\Application\UseCase\CreateBooking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;

final class CreateBookingUseCase
{
    public function __construct(private BookingRepository $bookingRepository)
    {
    }

    public function execute(CreateBookingCommand $command): Booking
    {
        $booking = Booking::reserve(
            $command->name,
            $command->email,
            $command->date,
            $command->guests
        );

        $this->bookingRepository->add($booking);

        return $booking;
    }
}
