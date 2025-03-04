<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'user'        => $this->user->name,
            'edition_id'  => $this->edition_id,
            'reserved_at' => $this->reserved_at->format('Y-m-d H:i:s'),
            'due_date'    => $this->due_date->format('Y-m-d H:i:s'),
            'status'      => $this->status,
        ];
    }
}
