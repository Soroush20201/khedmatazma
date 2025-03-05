<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'edition_id' => $this->edition_id,
            'reserved_at' => Carbon::parse($this->reserved_at)->format('Y-m-d H:i:s'),
            'due_date' => Carbon::parse($this->due_date)->format('Y-m-d H:i:s'),
            'status' => $this->status,
        ];
    }
}
