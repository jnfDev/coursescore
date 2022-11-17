<?php

namespace Tests\Feature\Course\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Course;
use App\Models\Source;

class CourseAdminControllerTest extends TestCase
{
    use RefreshDatabase;

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

        /**
         * @var Source
         */
        $source = Source::factory()->for($user)->create();

        /**
         * @var Course
         */
        $course = Course::factory()->for($adminUser)->for($source)->create();

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

    public function test_store()
    {
        /**
         * @var User
         */
        $adminUser = User::factory()->create([ 'is_admin' => true ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create();

        $courseName = 'Course 1';

        $this
            ->actingAs($adminUser)
            ->post('/admin/courses/', [
                'name' => $courseName,
                'description' => fake()->text(),
                'url' => fake()->url(),
                'user_id' => $adminUser->id,
                'source_id' => $source->id,
            ])
            ->assertRedirect('admin/courses')
        ;

        $this->assertDatabaseHas('courses', [
            'name' => $courseName,
            'user_id' => $adminUser->id,
            'source_id' => $source->id,
        ]);
    }

}
