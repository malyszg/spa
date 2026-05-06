<?php

namespace App\Form\Model;

class BookingFormData
{
    public ?string $name = null;

    public ?string $email = null;

    public ?\DateTimeInterface $date = null;

    public ?int $guests = null;
}
