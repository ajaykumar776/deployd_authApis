<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'avatar' => $this->first_name[0] . '' . $this->last_name[0],
        ];
        return $data;
    }
}
