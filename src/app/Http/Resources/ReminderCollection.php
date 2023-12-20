<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReminderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ret = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'remind_at' => $this->remind_at,
            'event_at' => $this->event_at
        ];
        return $ret;
        // return parent::toArray($request);
    }
}
