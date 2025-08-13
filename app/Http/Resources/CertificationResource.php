<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificationResource extends JsonResource
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
            'organization_name'  => $this->organization_name,
            'type'  => $this->type,
            'for' => $this->for,
            'abbreviation_code' => $this->abbreviation_code,
            'duration'  => $this->duration,
            'duration_unit'  => $this->duration_unit,
            'amount'  => $this->amount,
            'benefits'  => $this->benefits,
            'requirements'  => $this->requirements,
            // targets
            'targets'  => $this->targets,
            'management_signature' => $this->whenLoaded('managementSignature', function () {
                return new ManagementSignatureResource($this->managementSignature);
            }),
            'secretary_signature' => $this->whenLoaded('secretarySignature', function () {
                return new SecretarySignatureResource($this->secretarySignature);
            }),
            'created_by' => $this->whenLoaded('createdBy', function () {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                    'email' => $this->createdBy->email,
                ];
            }),
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
