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
        $this->artisan('db:seed --class=LaratrustSeeder');
        $this->superAdmin = User::factory()->create()->attachRole(Role::IS_SUPER_ADMIN);
        $this->user = User::factory()->create()->attachRole(Role::IS_USER);
    }

    /**
     * A test to check if super admin can change user role
     *
     * @return void
     */
    public function test_super_admin_can_change_user_role(){
        $this->withExceptionHandling();

        $this->assertTrue(User::all()->count() == 2);

        $response = $this->actingAs($this->superAdmin)->put('/users/' . $this->user->id, [
            "is_member" => $this->user->is_member,
            "loyalty_points"=>10
        ]);

        $response->assertStatus(200);
    }
}
