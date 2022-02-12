<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->super_admin = User::first();
        $this->user = User::factory()->create(['role_id' => Role::IS_USER]);
    }

    /**
     * A test to check if super admin can change user role
     *
     * @return void
     */
    public function test_super_admin_can_change_user_role(){
        $this->withExceptionHandling();

        $this->assertTrue(User::all()->count() == 2);

        $response = $this->actingAs($this->super_admin)->put('/users/' . $this->user->id, [
            "role_id" => Role::IS_ADMIN,
            "is_member" => $this->user->is_member,
            "royalty_points"=>10
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['role_id' => Role::IS_ADMIN]);
    }
}
