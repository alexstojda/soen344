<?php

namespace App\Http\Resources\Traits;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait HasRoom
 *
 * @property      int  $room_id
 * @property-read Room $room
 * @property-read Collection|Room[] $rooms
 */
trait HasRoom
{

    /**
     * @param Room|null $room
     *
     * @return array
     */
    private function roomToArray(Room $room = null): array
    {
        $room = $room ?? $this->room;

        return [
            'id' => $room->id,
            'path' => route('room.show', ['id' => $room->id]),
            'filter_by_room' => request()->fullUrlWithQuery(['room_id' => $room->id]),
        ];
    }

    /**
     * @return array
     */
    private function roomsToArray(): array
    {
        return $this->rooms->map(function (Room $room) {
            return $this->roomToArray($room);
        })->toArray();
    }
}