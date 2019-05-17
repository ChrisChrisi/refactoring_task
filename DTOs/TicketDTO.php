<?php

namespace Mailjet\DTOs;

use Mailjet\Entity\Language;

class TicketDTO
{
    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $phoneNumber;

    /** @var string */
    private $email;

    /** @var string */
    private $message;

    /** @var Language $language */
    private $language;

    public function __construct(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        Language $language)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->message = $message;
        $this->language = $language;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}