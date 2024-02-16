<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent' => $this->parent->name ?? null,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image_url,
            'status' => $this->status,
        ];
    }
}
