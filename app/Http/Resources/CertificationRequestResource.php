<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificationRequestResource extends JsonResource
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
            'user_id' => $this->user_id,
            'certification_id' => $this->certification_id,
            'full_name' => $this->full_name,
            'user_signature_id' => $this->user_signature_id,
            'reason_for_certification' => $this->reason_for_certification,
            'management_note' => $this->management_note,
            'credential_id' => $this->credential_id,
            'status' => $this->status,
            'membership' => $this->whenLoaded('membership', function (){
                return new MembershipResource($this->membership);
            }),
            'certification' => $this->whenLoaded('certification', function (){
                return new CertificationResource($this->certification);
            }),
            'credential' => $this->whenLoaded('credential', function (){
                return $this->credential;
            }),
            'user' => $this->whenLoaded('user', function (){
                return $this->user;
            }),
        ];
    }
}
