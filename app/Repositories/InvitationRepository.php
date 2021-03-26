<?php

namespace App\Repositories;

use Exception;
use App\Models\Event;
use App\Models\User;
use App\Models\Invitation;
use App\Mail\InvitationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvitationRepository extends BaseRepository
{
	/**
	 * create an invitation to an event
	 * 
	 * @param  array $data
	 * @return json
	 */
	public function create($data)
	{
		try {
			$invitation = Invitation::create([
				"email_sent" => "no",
				"event_id" => $data["event_id"],
				"invitee_id" => $data["invitee_id"],
				"created_by" => $data["created_by"],
			]);

			if (is_null($invitation)) {
				return formatResponse(200, 'Invitation not created');
			}

			$event = Event::findOrFail($invitation->event_id);
			$sender = User::findOrFail($invitation->created_by);
			$recepient = User::findOrFail($data["invitee_id"]);

			$invitationEmailWasSent = $this->sendInvitationEmail($event, $sender, $recepient);

			if($invitationEmailWasSent) {
				$invitation->email_sent = "yes";
				$invitation->save();
			}

			return formatResponse(201, 'Invitation created', true, $invitation);
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}     
	}

	/**
	 * send invitaion email for an event
	 * 
	 * @param  Illuminate\Database\Eloquent\Model $event
	 * @param  Illuminate\Database\Eloquent\Model $sender
	 * @param  Illuminate\Database\Eloquent\Model $recepient
	 * @return boolean
	 */
	private function sendInvitationEmail($event, $sender, $recepient)
	{
		try{
			$details = [
		        "event_description" => $event->description,
		        "event_date" => $event->date,
		        "event_time" => $event->time,
		        "event_location" => $event->location,
		        "organizer_name" => $sender->first_name ." ". $sender->last_name,
		        "organizer_email" => $sender->email,
		        "invitee_name" => $recepient->first_name ." ". $recepient->last_name,
		        "invitee_email" => $recepient->email,
		    ];

		    $subject = "Invitation: ". $details["invitee_name"] ." and ". $details["organizer_name"];
			$sendEmail = Mail::to($recepient->email)->send(new InvitationEmail($subject, $details));

			if(empty($sendMail)) {
				return true;
			}
		} catch (Exception $e) {}

		return false;
	}

	/**
	 * delete an event invitation
	 * 
	 * @param  int $id
	 * @return json
	 */
	public function delete($userId, $invitationId)
	{
		try {
			$invitation = Invitation::where('id', '=', $invitationId)
							->where('created_by', '=', $userId)
							->firstOrFail();

			$invitation->delete();

			return formatResponse(200, 'Invitation deleted', true, []);
		} catch (ModelNotFoundException $mnfe) {
			return formatResponse(404, 'Invitation not found');
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}

	public function fetchMany($userId, $begin, $perPage, $sortBy, $sortDirection)
	{
		//
	}

    public function fetchOne($userId, $id)
    {
    	//
    }

	public function update($data)
	{
		//
	}
}