<?php

namespace Tests\Feature\Source\Http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Source;

class AdminSourceRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_request_validation()
    {
        /**
         * @var User
         */
        $adminUser = User::factory()->create([ 'role' => UserRole::Admin ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create();

        $badRequestCases = [
            'user_id' => [
                'user_id' => false, // BAD
                'channel' => Source::CHANNELS[0], // OK
                'name'    => 'Course 1', // OK
            ],
            'name' => [
                'user_id' => $adminUser->id, // OK
                'channel' => Source::CHANNELS[0], // OK
                'name'    => '', // BAD empty
            ],
            'name' => [
                'user_id' => $adminUser->id, // OK
                'channel' => Source::CHANNELS[0], // OK
                'name'    => fake()->text(100) // Bad too long
            ],
            'description' => [
                'user_id'     => $adminUser->id, // OK
                'channel'     => Source::CHANNELS[0], // OK
                'name'        => 'Course 1', // OK
                'description' => fake()->text(2500), // Bad too long
            ],
            'channel' => [
                'user_id'     => $adminUser->id, // OK
                'name'        => fake()->name(), // OK
                'description' => fake()->text(1500), // OK
                'channel'     => null // BAD empty
            ],
        ];

        foreach ($badRequestCases as $errorKey => $badRequestCase ) {
            session()->flush();

            $this
                ->actingAs($adminUser)
                ->post('/admin/sources', $badRequestCase)
                ->assertSessionHasErrors($errorKey)
            ;

            if ('user_id' === $errorKey) {
                continue;
            }
            
            $this
                ->actingAs($adminUser)
                ->patch("/admin/sources/{$source->id}", $badRequestCase)
                ->assertSessionHasErrors($errorKey)
            ;
        }
    }
}
