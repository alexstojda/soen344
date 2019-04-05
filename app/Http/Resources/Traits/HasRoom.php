<?php

namespace App\Http\Resources\Traits;

use App\Models\Room;

/**
 * Trait HasRoom
 *
 * @property      int  $room_id
 * @property-read Room $room
 */
trait HasRoom
{

    /**
     * @return array
     */
    private function roomToArray(): array
    {
        return [
            'id' => $this->room_id,
            'path' => route('room.show', ['id' => $this->room_id]),
            'filter_by_room' => request()->fullUrlWithQuery(['room_id' => $this->room_id]),
        ];
    }
}