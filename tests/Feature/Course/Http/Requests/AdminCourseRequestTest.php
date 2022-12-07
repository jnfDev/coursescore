<?php

namespace Tests\Feature\Course\Http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Course;
use App\Models\Source;

class AdminCourseRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_validation()
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
}
