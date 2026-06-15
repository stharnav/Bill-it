<?php

use App\Models\User;

test('login page loads', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('admin can login with valid credentials', function () {
    $user = User::factory()->create([
        'username' => 'admin',
        'password' => bcrypt('password'),
        'user_type' => 0,
        'status' => 1,
    ]);

    $response = $this->post('/login-check', [
        'username' => 'admin',
        'password' => 'password',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();
});

test('inactive user is rejected at login', function () {
    $user = User::factory()->create([
        'username' => 'inactive_user',
        'password' => bcrypt('password'),
        'status' => 0,
    ]);

    $response = $this->post('/login-check', [
        'username' => 'inactive_user',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('invalid credentials return error', function () {
    $response = $this->post('/login-check', [
        'username' => 'nonexistent',
        'password' => 'wrong',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('authenticated user can logout', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'password' => bcrypt('password'),
        'status' => 1,
    ]);

    $this->actingAs($user);
    $response = $this->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});
