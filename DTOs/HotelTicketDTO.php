<?php

namespace Mailjet\DTOs;

use Mailjet\Entity\Language;

class HotelTicketDTO extends TicketDTO
{
    /** @var string */
    private $city;

    /** @var string */
    private $website;

    /** @var string */
    private $hotelName;

    public function __construct(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        Language $language,
        string $city,
        string $website,
        string $hotelName)
    {
        parent::__construct(
            $firstName,
            $lastName,
            $phoneNumber,
            $email,
            $message,
            $language);

        $this->city = $city;
        $this->website = $website;
        $this->hotelName = $hotelName;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function getHotelName(): string
    {
        return $this->hotelName;
    }

}