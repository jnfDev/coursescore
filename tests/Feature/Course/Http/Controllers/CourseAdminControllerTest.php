<?php

namespace Tests\Feature\Course\Http\Controllers;

use App\Models\User;
use App\Models\Course;
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

    public function test_create()
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
            ->get('/admin/courses/create')
            ->assertStatus(404);

        $this
            ->actingAs($adminUser)
            ->get('/admin/courses/create')
            ->assertStatus(200);
    }

    public function test_edit()
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

        $course = Course::factory()->for($adminUser)->create();

        $this
            ->actingAs($user)
            ->get("/admin/courses/{$course->id}/edit")
            ->assertStatus(404);

        $this
            ->actingAs($adminUser)
            ->get("/admin/courses/{$course->id}/edit")
            ->assertStatus(200)
            ->assertSee($course->name);
    }
}
