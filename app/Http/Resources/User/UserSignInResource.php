<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSignInResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'       => $this->name,
            'username'   => $this->username,
            'email'      => $this->when($this->email_verified_at, $this->email),
            'api_token'  => $this->createToken('full_stack_app')->accessToken,
        ];
    }
}
