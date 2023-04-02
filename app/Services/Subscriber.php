<?php

namespace App\Services;

interface Subscriber
{
    public function store($request): array;
    public function validateAPIKey(): bool;
    public function listSubscribers(): array;
    public function delete($id): array;
}
