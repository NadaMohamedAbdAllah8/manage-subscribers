<?php

namespace App\Services;

use App\Http\Requests\Admin\Subscriber\StoreRequest;
use App\Http\Requests\Admin\Subscriber\UpdateRequest;

interface Subscriber
{
    /**used array format is:
     * [
     * 'success'=>true/false,
     * 'data=>[],
     * 'error_message'=>""
     * ]
     */
    public function store(StoreRequest $request): array;
    public function validateAPIKey(): bool;
    public function listSubscribers(): array;
    public function show($id): array;
    public function update($id, UpdateRequest $request): array;
    public function delete($id): array;
}
