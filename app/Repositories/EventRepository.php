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
		//
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