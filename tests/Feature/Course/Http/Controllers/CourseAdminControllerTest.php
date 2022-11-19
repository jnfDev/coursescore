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
        $source = Source::factory()->for($adminUser)->create();

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
        $user = User::factory()->create();

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
            ->actingAs($user)
            ->post('/admin/courses', [
                'name' => $courseName,
                'description' => fake()->text(),
                'url' => fake()->url(),
                'user_id' => $adminUser->id,
                'source_id' => $source->id,
            ])
            ->assertStatus(404)
        ;

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

    public function test_validation()
    {
        /**
         * @var User
         */
        $adminUser = User::factory()->create([ 'is_admin' => true ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create();

        /**
         * @var Course 
         */
        $course = Course::factory()->for($adminUser)->for($source)->create();
        
        $badRequestCases = [
            'user_id' => [
                'user_id' => false, // BAD
                'source_id' => $source->id, // OK
                'name' => 'Course 1', // OK
            ],
            'source_id' => [
                'user_id' => $adminUser->id, // OK
                'source_id' => 'bad id', // Bad
                'name' => 'Course 1', // OK
            ],
            'name' => [
                'user_id' => $adminUser->id, // OK
                'source_id' => $source->id, // OK
                'name' => '', // BAD empty
            ],
            'name' => [
                'user_id' => $adminUser->id, // OK
                'source_id' => $source->id, // OK
                'name' => // BAD too long
                    'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam consectetur iste quia tenetur ducimus porro repellendus',
            ],
            'description' => [
                'user_id' => $adminUser->id, // OK
                'source_id' => $source->id, // OK
                'name' => 'Course 1', // OK
                'description' => fake()->text(2500),
            ],
            'url' => [
                'user_id' => $adminUser->id, // OK
                'source_id' => $source->id, // OK
                'name' => fake()->name(), // OK
                'description' => // OK
                    'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam consectetur iste quia tenetur ducimus porro repellendus',
                'url' => 'http//xdad¢#∞∞#@@|' // BAD wrong URL
            ],

        ];

        foreach ($badRequestCases as $errorKey => $badRequestCase ) {
            session()->flush();

            $this
                ->actingAs($adminUser)
                ->post('/admin/courses', $badRequestCase)
                ->assertSessionHasErrors($errorKey)
            ;

            if ( 'user_id' === $errorKey || 'source_id' === $errorKey ) {
                continue;
            }
            
            $this
                ->actingAs($adminUser)
                ->patch("/admin/courses/{$course->id}", $badRequestCase)
                ->assertSessionHasErrors($errorKey)
            ;
        }
    }

    public function test_update()
    {
        /**
         * @var User
         */
        $user = User::factory()->create();

        /**
         * @var User
         */
        $adminUser = User::factory()->create([ 'is_admin' => true ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create();

        /**
         * @var Course 
         */
        $course = Course::factory()->for($adminUser)->for($source)->create();

        $this
            ->actingAs($user)
            ->patch("/admin/courses/{$course->id}", [
                'name' => 'Course 1 UPDATED'
            ])
            ->assertStatus(404)
        ;

        $this
            ->actingAs($adminUser)
            ->patch("/admin/courses/{$course->id}", [
                'name' => 'Course 1 UPDATED'
            ])
            ->assertRedirect('admin/courses')    
        ;

        $this->assertDatabaseHas('courses', [
            'name' => 'Course 1 UPDATED'
        ]);
    }

    public function test_destroy()
    {
        /**
         * @var User
         */
        $user = User::factory()->create();

        /**
         * @var User
         */
        $adminUser = User::factory()->create([ 'is_admin' => true ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create();

        /**
         * @var Course 
         */
        $course = Course::factory()->for($adminUser)->for($source)->create();

        $this
            ->actingAs($user)
            ->delete("/admin/courses/{$course->id}")
            ->assertStatus(404);
        ;

        $this
            ->actingAs($adminUser)
            ->delete("/admin/courses/{$course->id}")
            ->assertRedirect('admin/courses');
        ;

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id
        ]);
    }
}
