<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'          => $this->name,
            'description'   => $this->description,
            'regular_price' => $this->regular_price,
            'sale_price'    => $this->sale_price,
            'discount'      => $this->discount,
            'quantity'      => $this->quantity,
            'photo'         => asset('storage').'/'.$this->photo,
            'category_id'   => $this->category_id,
            'color'         => $this->color,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }
}
