<?php

namespace App\Services;

use Illuminate\Http\Request;

interface Subscriber
{
    public function store($request): array;
    public function validateAPIKey(): bool;
    public function listSubscribers(): array;
    public function update($id, Request $request): array;
    public function delete($id): array;
}
