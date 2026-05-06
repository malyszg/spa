<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Infrastructure\Persistence\Doctrine\Entity\BookingRecord;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineBookingRepository implements BookingRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(Booking $booking): void
    {
        $record = new BookingRecord(
            $booking->name(),
            $booking->email(),
            $booking->date(),
            $booking->guests(),
            $booking->createdAt()
        );

        $this->entityManager->persist($record);
        $this->entityManager->flush();
    }
}
