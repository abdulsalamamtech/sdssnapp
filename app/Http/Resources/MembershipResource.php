<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource
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
            'full_name' => $this->full_name,
            'certification_request_id' => $this->certification_request_id,
            'issued_on' => $this->issued_on,
            'expires_on' => $this->expires_on,
            'serial_no' => $this->serial_no,
            'qr_code' => $this->qr_code,
            'created_at' => $this->created_at,
            'certification_request' => $this->whenLoaded('certificationRequest.certification.managementSignature', function () {
                // return new ManagementSignatureResource($this->certificationRequest->certification->managementSignature);
                return new CertificationRequestResource($this->certificationRequest);
            }),
            'user' => $this->whenLoaded('user', function () {
                return $this->user;
            }),
        ];
    }
}
