<?php

namespace Tests\Feature;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->getAdmin();
    }

    public function test_index_page_opens_for_admin()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/subscribers');

        $response->assertStatus(200);
    }

    public function test_create_page_opens_for_admin()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/subscribers/create');

        $response->assertStatus(200);
    }

    public function test_create_a_subscriber_successfully()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/subscribers/create');

        $response->assertStatus(200);
    }

    private function getAdmin(): Admin
    {
        $this->seed(AdminSeeder::class);
        return Admin::first();
    }
}
