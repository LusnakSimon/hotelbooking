<?php

namespace App\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\HTTPException;

class HotelController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        switch ($action) {
            case 'index':
            case 'detail':
            case 'filter':
                return true;
            case 'create':
                return $this->user->isLoggedIn() && $this->user->getRole() === 'manager';
            case 'edit':
            case 'delete':
                if (!($this->user->isLoggedIn() && $this->user->getRole() === 'manager')) return false;
                $hotel = Hotel::getOne((int)$request->value('id'));
                return $hotel->getManagerId() === $this->user->getId();
            case 'addRoom':
                if (!($this->user->isLoggedIn() && $this->user->getRole() === 'manager')) return false;
                $hotel = Hotel::getOne((int)$request->value('hotel_id'));
                return $hotel->getManagerId() === $this->user->getId();
            case 'deleteRoom':
                if (!($this->user->isLoggedIn() && $this->user->getRole() === 'manager')) return false;
                $room = Room::getOne((int)$request->value('id'));
                $hotel = Hotel::getOne($room->getHotelId());
                return $hotel->getManagerId() === $this->user->getId();
            default:
                return false;
        }
    }

    public function index(Request $request): Response
    {
        $hotels = Hotel::getAll();
        $locations = Hotel::executeRawSQL('SELECT DISTINCT `location` FROM `hotels`');
        return $this->html(compact('hotels', 'locations'));
    }

    public function filter(Request $request): Response
    {
        $where = [];
        $params = [];

        $location = $request->value('location');
        $min = $request->value('min_price');
        $max = $request->value('max_price');

        if ($location !== null && $location !== '') {
            $where[] = '`location` = ?';
            $params[] = $location;
        }
        if ($min !== null && $min !== '') {
            $where[] = '`price` >= ?';
            $params[] = (float)$min;
        }
        if ($max !== null && $max !== '') {
            $where[] = '`price` <= ?';
            $params[] = (float)$max;
        }

        if (count($where) > 0) {
            $hotels = Hotel::getAll(implode(' AND ', $where), $params);
        } else {
            $hotels = Hotel::getAll();
        }

        return $this->json($hotels);
    }

    public function detail(Request $request): Response
    {
        $id = (int)$request->value('id');
        $hotel = Hotel::getOne($id);
        if (is_null($hotel)) { 
            throw new HTTPException(404);
        }
        $rooms = Room::getAll('hotel_id = ?', [$id]);
        return $this->html(compact('hotel', 'rooms'));
    }

    public function create(Request $request): Response
    {
        $hotel = new Hotel();

        if ($request->isPost()) {
            $error = $this->validateHotelData($request);

            if ($error !== null) {
                $hotel->setFromRequest($request);
                $hotel->setManagerId($this->user->getId());
                return $this->html(compact('hotel', 'error'));
            }

            $hotel->setFromRequest($request);
            $hotel->setManagerId($this->user->getId());
            $imagePath = $this->handleImageUpload($request);
            $hotel->setImagePath($imagePath ?? '');
            $hotel->save();
            return $this->redirect($this->url('hotel.detail', ['id' => $hotel->getId()]));
        }

        return $this->html(compact('hotel'));
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id');
        $hotel = Hotel::getOne($id);
        if (is_null($hotel)) { 
            throw new HTTPException(404);
        }
        if ($request->isPost()) {
            $error = $this->validateHotelData($request);

            if ($error !== null) {
                $hotel->setFromRequest($request);
                $rooms = Room::getAll('hotel_id = ?', [$id]);
                return $this->html(compact('hotel', 'rooms', 'error'));
            }

            $hotel->setFromRequest($request);
            $imagePath = $this->handleImageUpload($request);
            if ($imagePath !== null) {
                $this->deleteImageFile($hotel->getImagePath());
                $hotel->setImagePath($imagePath);
            }
            $hotel->save();
            return $this->redirect($this->url('hotel.detail', ['id' => $hotel->getId()]));
        }

        $rooms = Room::getAll('hotel_id = ?', [$id]);
        return $this->html(compact('hotel', 'rooms'));
    }

    public function delete(Request $request): Response
    {
        $id = (int)$request->value('id');
        $hotel = Hotel::getOne($id);
        if (is_null($hotel)) { 
            throw new HTTPException(404);
        }
        $rooms = Room::getAll('hotel_id = ?', [$id]);
        foreach ($rooms as $r) {
            $bookings = Booking::getAll('room_id = ?', [$r->getId()]);
            foreach ($bookings as $b) {
                $b->delete();
            }
            $r->delete();
        }
        $this->deleteImageFile($hotel->getImagePath());
        $hotel->delete();
        return $this->redirect($this->url('admin.index'));
    }

    public function addRoom(Request $request): Response
    {
        $hotelId = (int)$request->value('hotel_id');
        $hotel = Hotel::getOne($hotelId);
        if (is_null($hotel)) { 
            throw new HTTPException(404);
        }
        $rooms = Room::getAll('hotel_id = ?', [$hotelId]);
        if ($request->isPost()) {
            $error = $this->validateRoomData($request);
            if ($error !== null) {
                $hotel = Hotel::getOne($hotelId);
                $rooms = Room::getAll('hotel_id = ?', [$hotelId]);
                return $this->html(compact('hotel', 'rooms', 'error'));
            }

            $room = new Room();
            $room->setFromRequest($request);
            $room->save();
        }
        return $this->html(compact('hotel', 'rooms'));
        
    }

    public function deleteRoom(Request $request): Response
    {
        $id = (int)$request->value('id');
        $room = Room::getOne($id);
        $hotelId = $room->getHotelId();
        $bookings = Booking::getAll('room_id = ?', [$id]);
        foreach ($bookings as $b) {
            $b->delete();
        }
        $room->delete();
        return $this->redirect($this->url('hotel.addRoom', ['id' => $hotelId]));
    }

    private function validateHotelData(Request $request): ?string
    {
        $name = $request->value('name');
        $location = $request->value('location');
        $address = $request->value('adress');
        $price = $request->value('price');
        $description = $request->value('description');

        if ($name === '') {
            return 'Name is required.';
        }
        if (mb_strlen($name) > 100) {
            return 'Name must be at most 100 characters.';
        }

        if ($location === '') {
            return 'Location is required.';
        }
        if (mb_strlen($location) > 100) {
            return 'Location must be at most 100 characters.';
        }

        if ($address === '') {
            return 'Address is required.';
        }
        if (mb_strlen($address) > 150) {
            return 'Address must be at most 150 characters.';
        }

        if ($description === '') {
            return 'Description is required.';
        }
        if (mb_strlen($description) > 2000) {
            return 'Description must be at most 2000 characters.';
        }

        if ($price === '' || !is_numeric($price)) {
            return 'Price must be a valid number.';
        }
        if ((float)$price <= 0) {
            return 'Price must be greater than zero.';
        }

        return null;
    }

    private function validateRoomData(Request $request): ?string
    {
        $beds = $request->value('beds');

        if ($beds === '' || !is_numeric($beds)) {
            return 'Beds must be a valid number.';
        }

        $beds = (int)$beds;
        if ($beds < 1) {
            return 'Beds must be at least 1.';
        }
        if ($beds > 6) {
            return 'Beds must be at most 6.';
        }

        return null;
    }

    private function deleteImageFile(string $imagePath): void
    {
        if (!empty($imagePath)) {
            @unlink(__DIR__ . '/../../public/' . $imagePath);
        }
    }

    private function handleImageUpload(Request $request): ?string
    {
        $file = $request->file('image');
        if ($file === null || !$file->isOk()) return null;
        $ext = strtolower(pathinfo($file->getName(), PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) return null;
        $filename = uniqid('hotel_', true) . '.' . $ext;
        $dest = __DIR__ . '/../../public/uploads/' . $filename;
        if ($file->store($dest)) {
            return 'uploads/' . $filename;
        }
        return null;
    }
}
