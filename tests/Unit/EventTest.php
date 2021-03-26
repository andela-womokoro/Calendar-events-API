<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EventTest extends TestCase
{
	use RefreshDatabase, WithoutMiddleware;

    public function testIndex()
    {
    	$users = User::factory()->count(3)->create();
    	$events = Event::factory()->count(30)->create();
    	$firstUser = $users->first();

    	$this->json('get', "api/users/$firstUser->id/events")
			->assertStatus(200)
			->assertJsonStructure([
				'data' => [
					'total',
			        'per_page',
			        'current_page',
			        'last_page',
			        'first_page_url',
			        'last_page_url',
			        'next_page_url',
			        'prev_page_url',
			        'from',
			        'to',
					'payload' => [
						'*' => [
							'id',
							'description',
							'date',
							'time',
							'location',
							'created_by',
							'created_at',
							'updated_at',
						]
					]
				]
			]);
    }

    public function testStore()
    {
    	$user = User::factory()->create();
    	$event = Event::factory()->make();

		$payload = [
			'description' => $event->description,
			'date' => $event->date,
			'time' => $event->time,
			'location' => $event->location,
			'created_by' => $user->id,
		];

		$this->json('post', "api/users/$user->id/events", $payload)
			->assertStatus(201)
			->assertJsonStructure([
				'data' => [
					'id',
					'description',
					'date',
					'time',
					'location',
					'created_by',
					'created_at',
					'updated_at',
				]
			]);
    }

    public function testShow()
    {
    	$user = User::factory()->create();
    	$event = Event::factory()->create();

    	$this->json('get', "api/users/$user->id/events/$event->id")
			->assertStatus(200)
			->assertJsonStructure([
				'data' => [
					'id',
					'description',
					'date',
					'time',
					'location',
					'created_by',
					'created_at',
					'updated_at',
				]
			]);
    }

    public function testUpdate()
    {
    	$user = User::factory()->create();
    	$event = Event::factory()->create();

		$payload = [
			'date' => '2021-03-16',
			'location' => 'Ghent',
		];

		$this->json('put', "api/users/$user->id/events/$event->id", $payload)
			->assertStatus(200)
			->assertJsonStructure([
				'data' => [
					'id',
					'description',
					'date',
					'time',
					'location',
					'created_by',
					'created_at',
					'updated_at',
				]
			]);
    }

    public function testDestroy()
    {
    	$user = User::factory()->create();
    	$event = Event::factory()->create();

    	$this->json('delete', "api/users/$user->id/events/$event->id")
			->assertStatus(200)
			->assertJsonStructure([
				'data' => []
			]);
    }

    public function testLocations()
    {
    	$user = User::factory()->count(5)->create();
    	$event = Event::factory()->count(20)->create();

    	$payload = [
			'from_date' => '1970-01-01',
			'to_date' => '2021-01-01',
		];

    	$this->json('get', 'api/event-locations', $payload)
			->assertStatus(200)
			->assertJsonStructure([
				'data' => [
					'*' => [
						'location',
					]
				]
			]);
    }
}
