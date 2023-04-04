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
    private $email;
    private $name;
    private $country;
    private $id;
    private $subscription_date;
    private $subscription_time;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->getAdmin();
        $timestamp = time();
        $this->email = 'test_email1680636331@test.com';
        $this->name = 'Test name';
        $this->country = 'Test county';
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
        $response = $this->actingAs($this->admin, 'admin')->post('/subscribers',
            $this->getTestSubscriberData());

        // redirecting to subscribers
        $response->assertStatus(302);
        $response->assertRedirect('/subscribers');
    }

    public function test_created_subscriber_is_in_the_index()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/subscribers/data');

        $response->assertOk();

        $data = $response->content();

        $subscriber_data = json_decode($data, true)['data'][0];
        // if (isset($data['error'])) {
        //     dd($data);
        // }

        // echo $this->email . '+++++++' . $this->name . '+++++++' . $this->country;
        // dd($subscriber_data);
        $this->assertEquals($this->email, $subscriber_data['email']);
        $this->assertEquals($this->name, $subscriber_data['name']);
        $this->assertEquals($this->country, $subscriber_data['country']);

        // to be used with edit, delete
        $this->setId($subscriber_data['id']);
        $this->setSubscriptionDate($subscriber_data['subscription_date']);
        $this->setSubscriptionTime($subscriber_data['subscription_time']);
    }

    private function getAdmin(): Admin
    {
        $this->seed(AdminSeeder::class);
        return Admin::first();
    }

    private function getTestSubscriberData(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'country' => $this->country,
        ];
    }

    private function setId($id): void
    {
        $this->id = $id;
    }

    private function setSubscriptionDate($subscription_date): void
    {
        $this->subscription_date = $subscription_date;
    }

    private function setSubscriptionTime($subscription_time): void
    {
        $this->subscription_time = $subscription_time;
    }
}
