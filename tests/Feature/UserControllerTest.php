<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user for authentication
        $this->adminUser = User::factory()->create([
            'role' => 'Master'
        ]);
    }

    /** @test */
    public function it_can_display_user_index_page()
    {
        // Create some users
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('master.user.index');
        $response->assertViewHas('users');
    }

    /** @test */
    public function it_can_show_create_user_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('master.user.create');
    }

    /** @test */
    public function it_can_create_a_new_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Karyawan'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('users.store'), $userData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User created successfully.');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'Karyawan'
        ]);
    }

    /** @test */
    public function it_validates_user_creation_with_invalid_data()
    {
        $invalidUserData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
            'role' => 'InvalidRole'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('users.store'), $invalidUserData);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
            'role'
        ]);
    }

    /** @test */
    public function it_can_show_edit_user_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('master.user.edit');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_can_update_user_information()
    {
        $user = User::factory()->create();

        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'Karyawan'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('users.update', $user), $updatedData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User updated successfully.');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertEquals('Karyawan', $user->role);
    }

    /** @test */
    public function it_can_update_user_with_password_change()
    {
        $user = User::factory()->create();

        $updatedData = [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'Karyawan',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('users.update', $user), $updatedData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User updated successfully.');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }


    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'Users deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
