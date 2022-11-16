<?php

namespace Tests\Feature\Course\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseAdminControllerTest extends TestCase
{
    public function test_index()
    {
        /**
         * @var User 
         */
        $user = User::factory()->create();

        /**
         * @var User 
         */
        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $this
            ->actingAs($user)
            ->get('/admin/courses')
            ->assertStatus(404);

        $this
            ->actingAs($adminUser)
            ->get('/admin/courses')
            ->assertStatus(200);
    }
}
