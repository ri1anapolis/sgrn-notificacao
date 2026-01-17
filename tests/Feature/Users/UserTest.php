<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

it('allows admin to view users index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    User::factory()->count(3)->create();

    actingAs($admin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Users/Index')
                ->where('users', function ($users) {
                    return count($users) >= 4;
                })
                ->has('roles')
        );
});

it('allows employee to view users index', function () {

    $employee = User::factory()->create(['role' => UserRole::Employee]);

    actingAs($employee)
        ->get(route('users.index'))
        ->assertOk();
});

it('allows admin to create a new user', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    actingAs($admin);

    $userData = [
        'name' => 'Novo Usuário',
        'email' => 'novo@empresa.com',
        'role' => UserRole::Employee->value,
    ];

    $response = post(route('users.store'), $userData);

    $response->assertRedirectToRoute('users.index');

    $response->assertSessionHas('temporary_code');

    $this->assertDatabaseHas('users', [
        'email' => 'novo@empresa.com',
        'must_change_password' => true,
    ]);
});

it('forbids employee from creating a user', function () {
    $employee = User::factory()->create(['role' => UserRole::Employee]);

    actingAs($employee);

    post(route('users.store'), [
        'name' => 'Hacker',
        'email' => 'hacker@empresa.com',
        'role' => 'admin',
    ])->assertForbidden();
});

it('validates user creation input', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    actingAs($admin);

    post(route('users.store'), [
        'name' => '',
        'email' => 'not-an-email',
    ])->assertSessionHasErrors(['name', 'email', 'role']);
});

it('allows admin to update a user', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $targetUser = User::factory()->create(['name' => 'Old Name']);

    actingAs($admin);

    put(route('users.update', $targetUser), [
        'name' => 'Updated Name',
        'email' => $targetUser->email,
        'role' => UserRole::Employee->value,
    ])->assertRedirectToRoute('users.index')
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $targetUser->id,
        'name' => 'Updated Name',
    ]);
});

it('forbids employee from updating a user', function () {
    $employee = User::factory()->create(['role' => UserRole::Employee]);
    $targetUser = User::factory()->create();

    actingAs($employee)
        ->put(route('users.update', $targetUser), [
            'name' => 'Hacked Name',
            'email' => $targetUser->email,
            'role' => 'admin',
        ])
        ->assertForbidden();
});

it('allows admin to delete a user', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $userToDelete = User::factory()->create();

    actingAs($admin)
        ->delete(route('users.destroy', $userToDelete))
        ->assertRedirectToRoute('users.index');

    $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
});

it('forbids employee from deleting a user', function () {
    $employee = User::factory()->create(['role' => UserRole::Employee]);
    $targetUser = User::factory()->create();

    actingAs($employee)
        ->delete(route('users.destroy', $targetUser))
        ->assertForbidden();

    $this->assertDatabaseHas('users', ['id' => $targetUser->id]);
});

it('allows user to view their own change password page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('users.change-password', $user))
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Users/ChangePassword')
                ->where('user.id', $user->id)
        );
});

it('forbids user from viewing other users change password page', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    actingAs($userA)
        ->get(route('users.change-password', $userB))
        ->assertForbidden();
});

it('updates password successfully and clears temporary flags', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
        'must_change_password' => true,
        'temporary_password' => 'some-hash',
    ]);

    actingAs($user);

    $newPassword = 'NewPassword123!';

    put(route('users.update-password', $user), [
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ])->assertRedirectToRoute('users.index')
        ->assertSessionHas('success');

    $user->refresh();

    expect(Hash::check('old-password', $user->password))->toBeFalse();
    expect(Hash::check($newPassword, $user->password))->toBeTrue();

    expect($user->must_change_password)->toBeFalse();
    expect($user->temporary_password)->toBeNull();
});

it('forbids user from updating other users password', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    actingAs($userA)
        ->put(route('users.update-password', $userB), [
            'password' => 'NewPass123',
            'password_confirmation' => 'NewPass123',
        ])
        ->assertForbidden();
});

it('validates password complexity', function () {
    $user = User::factory()->create();
    actingAs($user);

    put(route('users.update-password', $user), [
        'password' => '123',
        'password_confirmation' => '123',
    ])->assertSessionHasErrors('password');
});
