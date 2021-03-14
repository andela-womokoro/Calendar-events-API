<?php

namespace App\Repositories;

use Exception;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventRepository extends BaseRepository
{
	public function create($data)
	{
		try {
			$event = Event::create([
				"description" => $data["description"],
				"date" => $data["date"],
				"time" => $data["time"],
				"location" => $data["location"],
				"created_by" => auth()->user()->id,
			]);

			if (is_null($event)) {
				return formatResponse(200, 'Event not created');
			}

			return formatResponse(201, 'Event created', true, $event);
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}     
	}

	public function fetchMany($begin, $perPage, $sortBy, $sortDirection)
	{
		//
	}

	public function fetchOne($id)
	{
		try {
			$event = Event::findOrFail($id);

			return formatResponse(200, 'Ok', true, $event);
		} catch (ModelNotFoundException $mnfe) {
			return formatResponse(404, 'Event not found');
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}

	public function update($data, $id)
	{
		try {
			$event = Event::findOrFail($id);

			if (isset($data['description'])) {
				$event->description = $data['description'];
			}

			if (isset($data['date'])) {
				$event->date = $data['date'];
			}

			if (isset($data['time'])) {
				$event->time = $data['time'];
			}

			if (isset($data['location'])) {
				$event->location = $data['location'];
			}

			if ($event->isDirty()) {
				$event->save();

				return formatResponse(200, 'Event updated', true, $event);
			}

			return formatResponse(200, 'No changes made. No update required.', true, $event);
		} catch (ModelNotFoundException $mnfe) {
			return formatResponse(404, 'Event not found');
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}

	public function delete($id)
	{
		try {
			$event = Event::findOrFail($id);
			$event->delete();

			return formatResponse(200, 'Event deleted', true, []);
		} catch (ModelNotFoundException $mnfe) {
			return formatResponse(404, 'Event not found');
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}
}