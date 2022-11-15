<?php

namespace Tests\Feature\Pages;

use App\Models\User;
use Tests\TestCase;

class AdminPages extends TestCase
{
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
            'is_admin' => true
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
