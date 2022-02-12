<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->user = User::factory()->create(['role_id' => Role::IS_USER]);
        $this->admin = User::factory()->create(['role_id' => Role::IS_ADMIN]);
    }

    /**
     * A test to check if admin can not change user role
     *
     * @return void
     */
    public function test_admin_can_not_change_user_role()
    {
        $this->withExceptionHandling();

        $this->assertTrue(User::all()->count() == 3);

        $response = $this->actingAs($this->admin)->put('/users/' . $this->user->id, [
            "role_id" => Role::IS_ADMIN
        ]);

        $response->assertStatus(403);
    }

    /**
     * A test to check if admin can create a config
     *
     * @return void
     */
    public function test_admin_can_save_a_config()
    {
        $response = $this->actingAs($this->admin)->post('/configs', [
            "name" => "transaction fees",
            "value" => '18/100'
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Config::all()->count() == 5);
    }

    /**
     * A test to check if admin can update a config
     *
     * @return void
     */
    public function test_admin_can_update_a_config()
    {
        $config = Config::create([
            "name" => "vat_percentage",
            "value" => 0.18
        ]);

        $this->assertDatabaseHas('configs', ['name' => 'vat_percentage']);

        $response = $this->actingAs($this->admin)->put('/configs/'. $config->id, [
            "name" => $config->name,
            "value" => 0.15
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('configs', ['value' => 0.15]);
    }

    /**
     * A test to check if admin can delete a config
     *
     * @return void
     */
    public function test_admin_can_delete_a_config()
    {
        $config = Config::factory()->create();

        $this->assertTrue(Config::all()->count() == 5);

        $response = $this->actingAs($this->admin)->delete('/configs/' . $config->id);

        $response->assertStatus(200);
        $this->assertTrue(Config::all()->count() == 4);
    }
}
