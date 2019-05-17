<?php

namespace Mailjet\Service;

use Zendesk\API\HttpClient as ZendeskAPI;
use Mailjet\DTOs\{TicketDTO, CustomerTicketDTO, HotelTicketDTO, PressTicketDTO};
use Mailjet\Helpers\TicketFieldsBuilder;

class ZendeskService extends AbstractService
{
    /** @var ZendeskAPI */
    private $client;

    public function __construct(ZendeskAPI $client)
    {
        $this->client = $client;
        $this->client->setAuth(
            'basic',
            [
                'username' => $this->getServiceManager()->get('Config')['zendesk']['username'],
                'token' => $this->getServiceManager()->get('Config')['zendesk']['token']
            ]
        );
    }

    public function createCustomerTicket(CustomerTicketDTO $customerTicketDTO): bool
    {
        $reservation = $hotelEmail = null;
        $reservationNumber = $customerTicketDTO->getReservationNumber();
        $hotel = $customerTicketDTO->getHotel();

        if (!empty($reservationNumber)) {
            $reservation = $this->getEntityRepository('Reservation')->getByRef($reservationNumber);

            if ($reservation != null && $hotel == null) {
                $hotel = $reservation->getHotel();
                $customerTicketDTO->setHotel($hotel);
            }
        }

        if ($hotel != null) {
            $hotelContact = $this->getServiceManager()->get('service.hotel_contacts')->getMainHotelContact($hotel);
            $hotelEmail = ($hotelContact != null) ? $hotelContact->getEmail() : null;
        }

        if (empty($phoneNumber) && $reservation != null) {
            $customerTicketDTO->setPhoneNumber($reservation->getCustomer()->getSimplePhoneNumber());
        }

        $customFields = TicketFieldsBuilder::buildCustomerCustomFields($customerTicketDTO, $reservation, $hotel, $hotelEmail);

        $this->createTicket($customerTicketDTO, $customFields);

        return true;
    }

    public function createHotelTicket(HotelTicketDTO $hotelTicketDTO): bool
    {
        $customFields = TicketFieldsBuilder::buildHotelCustomFields($hotelTicketDTO);
        $userFields = ['website' => $hotelTicketDTO->getWebsite()];

        $this->createTicket($hotelTicketDTO, $customFields, $userFields);

        return true;
    }

    public function createPressTicket(PressTicketDTO $pressTicketDTO): bool
    {
        $customFields = TicketFieldsBuilder::buildPressCustomFields($pressTicketDTO);
        $userFields = ['press_media' => $pressTicketDTO->getMedia()];

        $this->createTicket($pressTicketDTO, $customFields, $userFields);

        return true;
    }

    public function createPartnersTicket(TicketDTO $ticketDTO): bool
    {
        $customFields = TicketFieldsBuilder::buildPartnerCustomFields($ticketDTO);

        $this->createTicket($ticketDTO, $customFields);

        return true;
    }

    private function createTicket(TicketDTO $ticketDTO, array $customFields, array $userFields = null): void
    {
        $requesterFields = [
            'email' => $ticketDTO->getEmail(),
            'name' => $this->makeFullName($ticketDTO->getFirstName(), $ticketDTO->getLastName()),
            'phone' => $ticketDTO->getPhoneNumber(),
            'role' => 'end-user'
        ];

        if (isset($userFields)) {
            $requesterFields['user_fields'] = $userFields;
        }

        $requester = $this->client->users()->createOrUpdate($requesterFields);
        $ticketFields = TicketFieldsBuilder::buildTicketFields($ticketDTO, $requester->user->id, $customFields);
        try {
            $this->client->tickets()->create($ticketFields);
        } catch (\Exception $e) {
            $this->getLogger()->addError(var_export($requester->user->id, true));
        }
    }
    private function makeFullName($firstName, $lastName)
    {
        return $firstName . ' ' . strtoupper($lastName);
    }
}
