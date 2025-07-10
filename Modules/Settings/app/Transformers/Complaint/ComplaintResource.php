<?php

namespace Modules\Settings\Transformers\Complaint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'booking' => $this->booking ? [
                'id' => $this->booking->id,
                'reference' => $this->booking->reference,
            ] : null,
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => $this->status,
            'resolution' => $this->resolution,
            'resolved_by' => $this->resolver ? [
                'id' => $this->resolver->id,
                'name' => $this->resolver->name,
            ] : null,
            'resolved_at' => $this->resolved_at?->format('Y-m-d H:i:s'),
        ];
    }
}