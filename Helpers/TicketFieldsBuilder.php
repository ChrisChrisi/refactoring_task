<?php


namespace Mailjet\Helpers;

use Mailjet\Entity\{Reservation, Hotel};
use Mailjet\DTOs\{CustomerTicketDTO, HotelTicketDTO, PressTicketDTO, TicketDTO};

class TicketFieldsBuilder
{
    private static $ticketFieldsCodes = [
        'ticketType' => '80924888',
        'reservationNumber' => '80531327',
        'hotelEmail' => '80531267',
        'hotelName' => '80918668',
        'location' => '80918648',
        'roomName' => '80531287',
        'bookedDate' => '80531307',
        'roomPrice' => '80924568',
        'bookingTimePeriod' => '80918728',
        'language' => '80918708',
    ];

    public static function buildCustomerCustomFields(
        CustomerTicketDTO $customerTicketDTO,
        Reservation $reservation = null,
        Hotel $hotel = null,
        string $hotelMail = null): array
    {
        $customFields = self::getBaseCustomFields($customerTicketDTO, 'customer');

        $customFields[self::$ticketFieldsCodes['reservationNumber']] = $customerTicketDTO->getReservationNumber();

        if ($hotel != null) {
            $customFields[self::$ticketFieldsCodes['hotelEmail']] = $hotelMail;
            $customFields[self::$ticketFieldsCodes['hotelName']] = $hotel->getName();
            $customFields[self::$ticketFieldsCodes['location']] = $hotel->getAddress();
        }

        if ($reservation != null) {
            $customFields[self::$ticketFieldsCodes['roomName']] = $reservation->getRoom()->getName() . ' (' . $reservation->getRoom()->getType() . ')';
            $customFields[self::$ticketFieldsCodes['bookedDate']] = $reservation->getBookedDate()->format('Y-m-d');
            $customFields[self::$ticketFieldsCodes['roomPrice']] = $reservation->getRoomPrice() . ' ' . $reservation->getHotel()->getCurrency()->getCode();
            $customFields[self::$ticketFieldsCodes['bookingTimePeriod']] = $reservation->getBookedStartTime()->format('H:i') . ' - ' . $reservation->getBookedEndTime()->format('H:i');
        }

        return $customFields;

    }

    public static function buildHotelCustomFields(HotelTicketDTO $hotelTicketDTO): array
    {
        $customFields = self::getBaseCustomFields($hotelTicketDTO, 'hotel');

        $customFields[self::$ticketFieldsCodes['hotelName']] = $hotelTicketDTO->getHotelName();
        $customFields[self::$ticketFieldsCodes['location']] = $hotelTicketDTO->getCity();

        return $customFields;
    }

    public static function buildPressCustomFields(PressTicketDTO $pressTicketDTO): array
    {
        $customFields = self::getBaseCustomFields($pressTicketDTO, 'hotel');
        $customFields[self::$ticketFieldsCodes['location']] = $pressTicketDTO->getCity();

        return $customFields;
    }

    public static function buildPartnerCustomFields(TicketDTO $ticketDTO): array
    {
        return self::getBaseCustomFields($ticketDTO, 'partner');
    }

    public static function buildTicketFields(TicketDTO $ticketDTO, string $requesterId, array $customFields): array
    {
        $message = $ticketDTO->getMessage();
        return [
            'requester_id' => $requesterId,
            'subject' => self::makeTicketSubject($message),
            'comment' =>
                [
                    'body' => $message
                ],
            'priority' => 'normal',
            'type' => 'question',
            'status' => 'new',
            'custom_fields' => $customFields
        ];
    }

    private static function getBaseCustomFields(TicketDTO $ticketDTO, string $type): array
    {
        return [
            self::$ticketFieldsCodes['language'] => $ticketDTO->getLanguage()->getName(),
            self::$ticketFieldsCodes['ticketType'] => $type
        ];
    }

    private static function makeTicketSubject($message): string
    {
        return strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message;
    }

}