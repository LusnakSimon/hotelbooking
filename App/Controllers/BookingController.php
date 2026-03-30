<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Hotel;

class BookingController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        switch ($action) {
            case 'index':
                return $this->user->isLoggedIn();
            case 'create':
                return $this->user->isLoggedIn() && $this->user->getRole() === 'guest';
            case 'edit':
            case 'delete':
                if (!$this->user->isLoggedIn()) return false;
                $booking = Booking::getOne((int)$request->value('id'));
                if ($booking === null) return false;
                $room = Room::getOne($booking->getRoomId());
                $hotel = $room ? Hotel::getOne($room->getHotelId()) : null;
                $isOwner = $booking->getUserId() === $this->user->getId();
                $isManager = $this->user->getRole() === 'manager'
                    && $hotel !== null
                    && $hotel->getManagerId() === $this->user->getId();
                return $isOwner || $isManager;
            default:
                return false;
        }
    }

    public function index(Request $request): Response
    {
        if ($this->user->getRole() === 'manager') {
            $sql = "SELECT b.*, r.id AS room_id, r.beds, r.hotel_id, h.name AS hotel_name, u.email AS user_email FROM `bookings` b JOIN `rooms` r ON r.id = b.room_id JOIN `hotels` h ON h.id = r.hotel_id JOIN `users` u ON u.id = b.user_id WHERE h.manager_id = ? ORDER BY b.`from` DESC";
            $bookings = Booking::executeRawSQL($sql, [$this->user->getId()]);
        } else {
            $sql = "SELECT b.*, r.id AS room_id, r.beds, r.hotel_id, h.name AS hotel_name FROM `bookings` b JOIN `rooms` r ON r.id = b.room_id JOIN `hotels` h ON h.id = r.hotel_id WHERE b.user_id = ? ORDER BY b.`from` DESC";
            $bookings = Booking::executeRawSQL($sql, [$this->user->getId()]);
        }
        return $this->html(compact('bookings'));
    }

    public function create(Request $request): Response
    {
        $roomId = (int)$request->value('room_id');
        $room = Room::getOne($roomId);
        if ($room === null) {
            return $this->redirect($this->url('hotel.index'));
        }
        $hotel = Hotel::getOne($room->getHotelId());

        if ($request->isPost()) {
            $from = $request->value('from');
            $until = $request->value('until');
            $error = $this->validateBookingDates($from, $until, $roomId);

            if ($error === null) {
                $booking = new Booking();
                $booking->setRoomId($roomId);
                $booking->setUserId($this->user->getId());
                $booking->setFrom($from);
                $booking->setUntil($until);
                $booking->save();
                return $this->redirect($this->url('booking.index'));
            }

            return $this->html(compact('room', 'hotel', 'error'));
        }

        return $this->html(compact('room', 'hotel'));
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id');
        $booking = Booking::getOne($id);
        if ($booking === null) {
            return $this->redirect($this->url('booking.index'));
        }

        if ($request->isPost()) {
            $from = $request->value('from');
            $until = $request->value('until');
            $error = $this->validateBookingDates($from, $until, $booking->getRoomId(), $booking->getId());

            if ($error === null) {
                $booking->setFrom($from);
                $booking->setUntil($until);
                $booking->save();
                return $this->redirect($this->url('booking.index'));
            }

            return $this->html(compact('booking', 'error'));
        }

        return $this->html(compact('booking'));
    }

    public function delete(Request $request): Response
    {
        $id = (int)$request->value('id');
        $booking = Booking::getOne($id);
        if ($booking === null) {
            return $this->redirect($this->url('booking.index'));
        }
        $booking->delete();
        return $this->redirect($this->url('booking.index'));
    }

    private function validateBookingDates(
        string $from,
        string $until,
        int $roomId,
        ?int $excludeBookingId = null
    ): ?string {
        if (empty($from) || empty($until)) {
            return 'Please provide both check-in and check-out dates.';
        }
        if (strtotime($from) < strtotime(date('Y-m-d'))) {
            return 'Check-in date cannot be in the past.';
        }
        if (strtotime($until) <= strtotime($from)) {
            return 'Check-out must be after check-in.';
        }
        $where = '`room_id` = ? AND `from` < ? AND `until` > ?';
        $params = [$roomId, $until, $from];
        if ($excludeBookingId !== null) {
            $where .= ' AND `id` != ?';
            $params[] = $excludeBookingId;
        }
        if (Booking::getCount($where, $params) > 0) {
            return 'Room is not available for the selected dates.';
        }
        return null;
    }
}
