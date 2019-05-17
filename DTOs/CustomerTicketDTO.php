<?php

namespace Mailjet\DTOs;

use Mailjet\Entity\{Language, Hotel};

class CustomerTicketDTO extends TicketDTO
{
    /** @var string */
    private $reservationNumber;

    /** @var \Mailjet\Entity\Hotel $hotel */
    private $hotel;

    public function __construct(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        Language $language,
        string $reservationNumber,
        Hotel $hotel)
    {
        parent::__construct(
            $firstName,
            $lastName,
            $phoneNumber,
            $email,
            $message,
            $language);

        $this->reservationNumber = $reservationNumber;
        $this->hotel = $hotel;
    }

    public function getReservationNumber(): string
    {
        return $this->reservationNumber;
    }

    public function getHotel(): Hotel
    {
        return $this->hotel;
    }

public function setHotel(Hotel $hotel): void
{
    $this->hotel = $hotel;
}

}