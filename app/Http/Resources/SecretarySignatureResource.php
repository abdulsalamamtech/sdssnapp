<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecretarySignatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'full_name' => $this->full_name,
            'position' => $this->position,
            // 'signature_id' => $this->signature_id,
            'signature_url' => $this->signature ? $this->signature->url : null, // Assuming signature is an Asset with a url attribute
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            // 'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            // 'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];        
    }
}
