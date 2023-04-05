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

    public function test_create_a_subscriber_validation()
    {
        $response = $this->actingAs($this->admin, 'admin')->post('/subscribers',
            [
                'email' => 'email',
                'name' => '',
                'country' => '1',
            ]
        );

        $response->assertStatus(302);
        $response->assertInvalid(['email', 'name', 'country']);
    }

    public function test_create_a_subscriber_successfully()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/subscribers/create');
        $response->assertStatus(200);

        // save subscriber
        $response = $this->actingAs($this->admin, 'admin')->post('/subscribers',
            $this->getTestSubscriberData());

        // redirecting to subscribers index
        $response->assertStatus(302);
        $response->assertRedirect('/subscribers');

        // subscriber is returned in the data
        $subscriber_data = $this->getLatestSubscriberData();
        $this->setId($subscriber_data['id']);

        $this->assertEquals($this->email, $subscriber_data['email']);
        $this->assertEquals($this->name, $subscriber_data['name']);
        $this->assertEquals($this->country, $subscriber_data['country']);

        $this->deleteTestSubscriber($this->id);
    }

    public function test_edit_page_contains_subscriber_values()
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
        $response->assertSee('value="' . $this->email . '"', false);
        $response->assertSee('value="' . $this->name . '"', false);
        $response->assertSee('value="' . $this->country . '"', false);
        $response->assertSee('value="' . $this->subscription_date . '"', false);
        $response->assertSee('value="' . $this->subscription_time . '"', false);

        $this->deleteTestSubscriber($this->id);
    }

    public function test_update_a_subscriber_validation()
    {
        $response = $this->actingAs($this->admin, 'admin')->post(
            '/subscribers',
            [
                'email' => 'email',
                'name' => '',
                'country' => '1',
            ]
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHasErrors(['name']);
        $response->assertSessionHasErrors(['country']);
    }

    public function test_update_a_subscriber_successfully()
    {
        // create a subscriber
        $this->actingAs($this->admin, 'admin')->post('/subscribers',
            $this->getTestSubscriberData());
        $subscriber_data = $this->getLatestSubscriberData();
        $this->setId($subscriber_data['id']);
        $this->setSubscriptionDate($subscriber_data['subscription_date']);
        $this->setSubscriptionTime($subscriber_data['subscription_time']);

        $name_updated = 'name test update';
        $country_updated = 'country test update';

        $response = $this->actingAs($this->admin, 'admin')->put('/subscribers/' . $this->id,
            [
                'name' => $name_updated,
                'country' => $country_updated,
            ]
        );

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $updated_subscriber_data = $this->getLatestSubscriberData();
        $this->assertEquals($name_updated, $updated_subscriber_data['name']);
        $this->assertEquals($country_updated, $updated_subscriber_data['country']);

        $this->deleteTestSubscriber($this->id);
    }

    public function test_delete_a_subscriber_successfully()
    {
        // create a subscriber
        $this->actingAs($this->admin, 'admin')->post('/subscribers',
            $this->getTestSubscriberData());
        $subscriber_data = $this->getLatestSubscriberData();
        $this->setId($subscriber_data['id']);
        $this->setSubscriptionDate($subscriber_data['subscription_date']);
        $this->setSubscriptionTime($subscriber_data['subscription_time']);

        $response = $this->actingAs($this->admin, 'admin')->delete('/subscribers/' . $this->id);

        $response->assertStatus(204);
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

    private function deleteTestSubscriber($id)
    {
        $this->actingAs($this->admin, 'admin')->delete('/subscribers/' . $id);
    }
}
