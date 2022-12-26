<?php

namespace Tests\Feature\Source\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Source;
use App\Enums\ModelStatus;

class AdminSourceControllerTest extends TestCase
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
        $contributorUser = User::factory()->create([
            'role' => UserRole::Contributor
        ]);

        /**
         * @var User
         */
        $adminUser = User::factory()->create([
            'role' => UserRole::Admin
        ]);

        $this
            ->actingAs($user)
            ->get('/admin/sources')
            ->assertStatus(404)
        ;

        $this
            ->actingAs($contributorUser)
            ->get('/admin/sources')
            ->assertStatus(200)
        ;

        $this
            ->actingAs($adminUser)
            ->get('admin/sources')
            ->assertStatus(200)
        ;        
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
        $contributorUser = User::factory()->create([
            'role' => UserRole::Contributor
        ]);

        /**
         * @var User 
         */
        $adminUser = User::factory()->create([
            'role' => UserRole::Admin
        ]);

        $this
            ->actingAs($user)
            ->get('/admin/sources/create')
            ->assertStatus(404)    
        ;

        $this
            ->actingAs($contributorUser)
            ->get('/admin/sources/create')
            ->assertStatus(200)
        ;

        $this
            ->actingAs($adminUser)
            ->get('/admin/sources/create')
            ->assertStatus(200)
        ;
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
        $contributorUser = User::factory()->create([
            'role' => UserRole::Contributor
        ]);

        /**
         * @var User 
         */
        $adminUser = User::factory()->create([
            'role' => UserRole::Admin
        ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create([]);

        $this
            ->actingAs($user)
            ->get("/admin/sources/{$source->id}/edit")
            ->assertStatus(404)
        ;

        $this
            ->actingAs($contributorUser)
            ->get("/admin/sources/{$source->id}/edit")
            ->assertStatus(200)
            ->assertSee($source->name)
        ;

        $this
            ->actingAs($adminUser)
            ->get("/admin/sources/{$source->id}/edit")
            ->assertStatus(200)
            ->assertSee($source->name)
        ;
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
        $contributorUser = User::factory()->create([ 'role' => UserRole::Contributor ]);

        /**
         * @var User
         */
        $adminUser = User::factory()->create([ 'role' => UserRole::Admin ]);

        // Regular user
        $this
            ->actingAs($user)
            ->post('/admin/sources', [
                'user_id' => $user->id,
                'name' => 'Source 1',
                'description' => fake()->text(),
                'channel' => fake()->randomElement(Source::CHANNELS)
            ])
            ->assertStatus(404)
        ;

        // Contributor User
        $source1 = 'Source 1';
        $this
            ->actingAs($contributorUser)
            ->post('/admin/sources', [
                'user_id' => $contributorUser->id,
                'name' => $source1,
                'description' => fake()->text(),
                'channel' => fake()->randomElement(Source::CHANNELS)
            ])
            ->assertRedirect('admin/sources')
        ;

        $this->assertDatabaseHas('sources', [
            'name' => $source1,
            'user_id' => $contributorUser->id,
            'status' => ModelStatus::WaitingForCreate
        ]);

        // Admin User
        $source2 = 'Source 2';
        $this
            ->actingAs($adminUser)
            ->post('/admin/sources', [
                'user_id' => $adminUser->id,
                'name' => $source2,
                'description' => fake()->text(),
                'channel' => fake()->randomElement(Source::CHANNELS)
            ])
            ->assertRedirect('admin/sources')
        ;

        $this->assertDatabaseHas('sources', [
            'name' => $source2,
            'user_id' => $adminUser->id,
            'status' => ModelStatus::Publish
        ]);
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
        $adminUser = User::factory()->create([ 'role' => UserRole::Admin ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create();

        $newSourceName = 'Source 1 UPDATED';

        $this
            ->actingAs($user)
            ->patch("/admin/sources/{$source->id}", [
                'name' => $newSourceName
            ])
            ->assertStatus(404)
        ;

        $this
            ->actingAs($adminUser)
            ->patch("/admin/sources/{$source->id}", [
                'name' => $newSourceName,
                'channel' => fake()->randomElement(Source::CHANNELS)
            ])
            ->assertRedirect('admin/sources')    
        ;

        $this->assertDatabaseHas('sources', [
            'name' => $newSourceName
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
        $adminUser = User::factory()->create([ 'role' => UserRole::Admin ]);

        /**
         * @var Source
         */
        $source = Source::factory()->for($adminUser)->create();

        $this
            ->actingAs($user)
            ->delete("/admin/sources/{$source->id}")
            ->assertStatus(404);
        ;

        $this
            ->actingAs($adminUser)
            ->delete("/admin/sources/{$source->id}")
            ->assertRedirect('admin/sources');
        ;

        $this->assertDatabaseMissing('sources', [
            'id' => $source->id
        ]);
    }

    public function test_search()
    {
        /**
         * @var User
         */
        $user = User::factory()->create();

        /**
         * @var User
         */
        $adminUser = User::factory()->create([ 'role' => UserRole::Admin ]);

        Source::factory()->for($adminUser)->create(['name' => 'My Source 1']);
        Source::factory()->for($adminUser)->create(['name' => 'My Source 2']);
        Source::factory()->for($adminUser)->create(['name' => 'Regular Source 1']);
        Source::factory()->for($adminUser)->create(['name' => 'Regular Source 2', 'description' => 'Just Another Source']);
        Source::factory()->for($adminUser)->create(['name' => 'Just Another Source']);

        // Search by name
        $search = 'My Source';
        $response = $this
                        ->actingAs($adminUser)
                        ->get("/admin/sources/search/{$search}");
                 
        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'name' => 'My Source 1'
                ],
                [
                    'name' => 'My Source 2'
                ],
            ])
        ;

        // Search by name and description
        $search = 'Just another source';
        $response = $this
            ->actingAs($adminUser)
            ->get("/admin/sources/search/{$search}");

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'name' => 'Regular Source 2'
                ],
                [
                    'name' => 'Just Another Source'
                ],
            ])
        ;
        
        return;
    }
}
