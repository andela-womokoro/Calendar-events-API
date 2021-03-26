<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class InvitationTest extends TestCase
{
	use RefreshDatabase, WithoutMiddleware;

	public function testStore()
	{
		$organizer = User::factory()->create();
		$invitee = User::factory()->create();
    	$event = Event::factory()->create();
		$payload = ['invitee_id' => $invitee->id];

		$this->json('post', "api/users/$organizer->id/events/$event->id/invitations", $payload)
			->assertStatus(201)
			->assertJsonStructure([
				'data' => [
					'email_sent',
					'event_id',
					'invitee_id',
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

    	$this->json('delete', "api/users/$user->id/events/$event->id/invitations/$invitation->id")
			->assertStatus(200)
			->assertJsonStructure([
				'data' => []
			]);
    }
}
