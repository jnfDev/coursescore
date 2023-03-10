<?php

namespace Tests\Feature\Pages;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Enums\UserRole;

class AdminPages extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_admin_users_see_dashboard()
    {
        /**
         * @var User 
         */
        $user = User::factory()->create([
            'role' => UserRole::Admin
        ]);

        $response = $this->actingAs($user)->get("/admin/dashboard");

        $response->assertStatus(200);
    }

    public function test_canot_regular_users_see_dashboard()
    {
        /**
         * @var User 
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/admin/dashboard");

        $response->assertStatus(404);
    }
}
