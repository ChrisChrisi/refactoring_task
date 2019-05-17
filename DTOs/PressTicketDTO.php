<?php

namespace Mailjet\DTOs;

use Mailjet\Entity\Language;

class PressTicketDTO extends TicketDTO
{
    /** @var string */
    private $city;

    /** @var string */
    private $media;

    public function __construct(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        Language $language,
        string $city,
        string $media)
    {
        parent::__construct(
            $firstName,
            $lastName,
            $phoneNumber,
            $email,
            $message,
            $language);

        $this->city = $city;
        $this->media = $media;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getMedia(): string
    {
        return $this->media;
    }

}