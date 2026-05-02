<?php

namespace App\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

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
                return $hotel !== null && $hotel->getManagerId() === $this->user->getId();
            case 'addRoom':
                if (!($this->user->isLoggedIn() && $this->user->getRole() === 'manager')) return false;
                $hotel = Hotel::getOne((int)$request->value('hotel_id'));
                return $hotel !== null && $hotel->getManagerId() === $this->user->getId();
            case 'deleteRoom':
                if (!($this->user->isLoggedIn() && $this->user->getRole() === 'manager')) return false;
                $room = Room::getOne((int)$request->value('id'));
                if ($room === null) return false;
                $hotel = Hotel::getOne($room->getHotelId());
                return $hotel !== null && $hotel->getManagerId() === $this->user->getId();
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
        if ($hotel === null) {
            return $this->redirect($this->url('hotel.index'));
        }
        $rooms = Room::getAll('hotel_id = ?', [$id]);
        return $this->html(compact('hotel', 'rooms'));
    }

    public function create(Request $request): Response
    {
        if ($request->isPost()) {
            $hotel = new Hotel();
            $hotel->setFromRequest($request);
            $hotel->setManagerId($this->user->getId());
            $imagePath = $this->handleImageUpload($request);
            $hotel->setImagePath($imagePath ?? '');
            $hotel->save();
            return $this->redirect($this->url('hotel.detail') . '&id=' . urlencode($hotel->getId()));
        }

        $hotel = new Hotel();
        return $this->html(compact('hotel'));
    }

    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id');
        $hotel = Hotel::getOne($id);

        if ($request->isPost()) {
            $hotel->setFromRequest($request);
            $imagePath = $this->handleImageUpload($request);
            if ($imagePath !== null) {
                $this->deleteImageFile($hotel->getImagePath());
                $hotel->setImagePath($imagePath);
            }
            $hotel->save();
            return $this->redirect($this->url('hotel.detail') . '&id=' . urlencode($hotel->getId()));
        }

        $rooms = Room::getAll('hotel_id = ?', [$id]);
        return $this->html(compact('hotel', 'rooms'));
    }

    public function delete(Request $request): Response
    {
        $id = (int)$request->value('id');
        $hotel = Hotel::getOne($id);
        $rooms = Room::getAll('hotel_id = ?', [$id]);
        foreach ($rooms as $r) {
            $r->delete();
        }
        $this->deleteImageFile($hotel->getImagePath());
        $hotel->delete();
        return $this->redirect($this->url('hotel.index'));
    }

    public function addRoom(Request $request): Response
    {
        $hotelId = (int)$request->value('hotel_id');
        $beds = (int)$request->value('beds');
        $room = new Room();
        $room->setHotelId($hotelId);
        $room->setBeds($beds);
        $room->save();
        return $this->redirect($this->url('hotel.edit') . '&id=' . urlencode($hotelId));
    }

    public function deleteRoom(Request $request): Response
    {
        $id = (int)$request->value('id');
        $room = Room::getOne($id);
        $hotelId = $room->getHotelId();
        $room->delete();
        return $this->redirect($this->url('hotel.edit') . '&id=' . urlencode($hotelId));
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
