<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RepositoryControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    public function test_guest(): void
    {
        $this->get('repositories')->assertRedirect('login');        // Index
        $this->get('repositories/create')->assertRedirect('login'); // Create
        $this->post('repositories', [])->assertRedirect('login');   // Guardar
        $this->get('repositories/1/edit')->assertRedirect('login'); // Editar
        $this->put('repositories/1')->assertRedirect('login');      // Actualizar
        $this->delete('repositories/1')->assertRedirect('login');   // Eliminar
    }

    public function test_index_empty(): void
    {
        Repository::factory()->create();
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee('No hay repositorios creados');
    }

    public function test_index_with_data(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee($repository->id)
            ->assertSee($repository->url);
    }

    public function test_create(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get("repositories/create")
            ->assertStatus(200);
    }

    public function test_store(): void
    {
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('repositories', $data)
            ->assertRedirect('repositories');

        $this->assertDatabaseHas('repositories', $data);
    }

    public function test_update_policy(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];

        $this
            ->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertStatus(403);
    }

    public function test_update(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];


        $this
            ->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertRedirect("repositories/$repository->id/edit");

        $this->assertDatabaseHas('repositories', $data);
    }

    public function test_destroy_policy(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this
            ->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertStatus(403);
    }

    public function test_destroy(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertRedirect('repositories');

        $this->assertDatabaseMissing('repositories', [
            'id' => $repository->id,
            'url' => $repository->url,
            'description' => $repository->description,
        ]);
    }

    //

    public function test_validate_store(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('repositories', [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);
    }

    public function test_validate_update(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this
            ->actingAs($user)
            ->put("repositories/$repository->id", [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);
    }

    //

    public function test_show_policy(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this
            ->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(403);
    }

    public function test_show(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertSee($repository->url)
            ->assertStatus(200);
    }

    public function test_edit_policy(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this
            ->actingAs($user)
            ->get("repositories/$repository->id/edit")
            ->assertStatus(403);
    }

    public function test_edit(): void
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->get("repositories/$repository->id/edit")
            ->assertStatus(200)
            ->assertSee($repository->url)
            ->assertSee($repository->description);
    }
}