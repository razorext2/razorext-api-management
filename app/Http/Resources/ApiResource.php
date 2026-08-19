<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    /**
     * Status dari response
     *
     * @var bool
     */
    public $status;

    /**
     * Pesan dari response
     *
     * @var string
     */
    public $message;

    /**
     * Create a new resource instance.
     *
     * @param  bool  $status
     * @param  string  $message
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($status, $message, $resource = null)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
