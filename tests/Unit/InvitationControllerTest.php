<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class InvitationControllerTest extends TestCase
{
	use RefreshDatabase, WithoutMiddleware;

	public function testStore()
	{
		$organizer = User::factory()->create();
		$invitee = User::factory()->create();
    	$event = Event::factory()->create();
    	$invitation = Invitation::factory()->make();

		$payload = [
			'email_sent' => 'no',
			'event_id' => $event->id,
			'user_id' => $invitee->id,
			'created_by' => $organizer->id,
		];

		$this->json('post', "api/events/$event->id/invite/$invitation->id", $payload)
			->assertStatus(201)
			->assertJsonStructure([
				'data' => [
					'email_sent',
					'event_id',
					'user_id',
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
    	$invitation = Invitation::factory()->create();

    	$this->json('delete', "api/events/$event->id/invite/$invitation->id")
			->assertStatus(200)
			->assertJsonStructure([
				'data' => []
			]);
    }
}
