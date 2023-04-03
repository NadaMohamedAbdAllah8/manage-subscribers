<?php

namespace App\Services;

use Illuminate\Http\Request;

interface Subscriber
{
    /**used array format is:
     * [
     * 'success'=>true/false,
     * 'data=>[],
     * 'error_message'=>""
     * ]
     */
    public function store($request): array;
    public function validateAPIKey(): bool;
    public function listSubscribers(): array;
    public function show($id): array;
    public function update($id, Request $request): array;
    public function delete($id): array;
}
