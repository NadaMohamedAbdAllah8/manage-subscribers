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
        $this->email = 'test_email' . $timestamp . '@test.com';
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
        // save subscriber
        $response = $this->actingAs($this->admin, 'admin')->post('/subscribers',
            $this->getTestSubscriberData());

        // redirecting to subscribers index
        $response->assertStatus(302);
        $response->assertRedirect('/subscribers');

        // subscriber is returned in the data
        $this->actingAs($this->admin, 'admin')->post('/subscribers',
            $this->getTestSubscriberData());

        $subscriber_data = $this->getLatestSubscriberData();

        $this->assertEquals($this->email, $subscriber_data['email']);
        $this->assertEquals($this->name, $subscriber_data['name']);
        $this->assertEquals($this->country, $subscriber_data['country']);
    }

    public function test_edit_page_contains_subscriber()
    {
        // create a subscriber
        $this->actingAs($this->admin, 'admin')->post('/subscribers',
            $this->getTestSubscriberData());
        $subscriber_data = $this->getLatestSubscriberData();
        $this->setId($subscriber_data['id']);
        $this->setSubscriptionDate($subscriber_data['subscription_date']);
        $this->setSubscriptionTime($subscriber_data['subscription_time']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get('/subscribers/' . $this->id . '/edit/');

        $response->assertStatus(200);

        // values are correct
        $response->assertSee('value="' . $this->name . '"', false);
        $response->assertSee('value="' . $this->country . '"', false);
        $response->assertSee('value="' . $this->subscription_date . '"', false);
        $response->assertSee('value="' . $this->subscription_time . '"', false);
    }

    private function getAdmin(): Admin
    {
        $this->seed(AdminSeeder::class);
        return Admin::first();
    }

    private function getLatestSubscriberData(): array
    {
        // get all the subscriber data
        $response = $this->actingAs($this->admin, 'admin')->get('/subscribers/data');
        // the subscriber exists
        $data = $response->content();
        return json_decode($data, true)['data'][0];
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