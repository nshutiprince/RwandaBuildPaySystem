<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->user = User::factory()->create(['role_id' => Role::IS_USER]);
    }

    /**
     * A test to check if user can not change another user role
     *
     * @return void
     */
    public function test_user_can_not_change_another_user_role()
    {
        $this->withExceptionHandling();

        $user = User::factory()->create(['role_id' => Role::IS_USER]);
        $this->assertTrue(User::all()->count() == 3);

        $response = $this->actingAs($this->user)->put('/users/' . $user->id, [
            "role_id" => Role::IS_ADMIN
        ]);

        $response->assertStatus(403);
    }

    /**
     * A test to check if user can not create a config
     *
     * @return void
     */
    public function test_user_can_not_save_a_config()
    {
        $response = $this->actingAs($this->user)->post('/configs', [
            "name" => "transaction fees",
            "value" => 0.18
        ]);

        $response->assertStatus(403);
        $this->assertTrue(Config::all()->count() == 4);
    }

    /**
     * A test to check if user can not update a config
     *
     * @return void
     */
    public function test_user_can_not_update_a_config()
    {
        $config = Config::create([
            "name" => "vat_percentage",
            "value" => 0.18
        ]);

        $this->assertDatabaseHas('configs', ['name' => 'vat_percentage']);

        $response = $this->actingAs($this->user)->put('/configs/' . $config->id, [
            "name" => $config->name,
            "value" => 0.15
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('configs', ['value' => 0.18]);
    }

    /**
     * A test to check if user can not delete a config
     *
     * @return void
     */
    public function test_user_can_not_delete_a_config()
    {
        $config = Config::factory()->create();

        $this->assertTrue(Config::all()->count() == 5);

        $response = $this->actingAs($this->user)->delete('/configs/' . $config->id);

        $response->assertStatus(403);
        $this->assertTrue(Config::all()->count() == 5);
    }
}
