<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserControllerTest extends TestCase
{
	use RefreshDatabase, WithoutMiddleware;

	public function testRegister()
	{
		$user = User::factory()->make();

		$payload = [
			'username' => $user->username,
			'password' => $user->password,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'email' => $user->email,
		];

		$this->json('post', 'api/register', $payload)
			->assertStatus(201)
			->assertJsonStructure([
				'data' => [
					'username',
					'first_name',
					'last_name',
					'email',
					'updated_at',
					'created_at',
					'id',
				]
			]);
	}

	public function testLogin()
	{
		$user = User::factory()->create(['password' => bcrypt('foobar')]);

		$payload = [
			'email' => $user->email,
			'password' => 'foobar',
		];

		$this->json('post', 'api/login', $payload)
			->assertStatus(200)
			->assertJsonStructure([
				'data' => [
					'token',
				]
			]);
	}
}
