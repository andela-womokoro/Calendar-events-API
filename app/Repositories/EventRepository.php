<?php

namespace App\Repositories;

use Exception;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventRepository extends BaseRepository
{
	/**
	 * Create an event
	 * 
	 * @param  array $data
	 * @return json
	 */
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

	/**
	 * Fetch a list of events
	 * 
	 * @param  int $begin
	 * @param  int $perPage
	 * @param  string $sortBy
	 * @param  string $sortDirection
	 * @return json
	 */
	public function fetchMany($begin, $perPage, $sortBy, $sortDirection, $fromDate = null, $toDate = null)
	{
		try {
			$event = null;

			if (!is_null($fromDate) && !is_null($toDate)) {
				$event = Event::where('date', '>=', $fromDate)
								->where('date', '<=', $toDate)
								->orderBy($sortBy, $sortDirection)
								->offset($begin)
								->limit($perPage)
								->paginate($perPage)
								->withQueryString();
			} else {
				$event = Event::orderBy($sortBy, $sortDirection)
								->offset($begin)
								->limit($perPage)
								->paginate($perPage)
								->withQueryString();
			}

			$event = json_decode(json_encode($event));
			
			$payload = [
				"total" => $event->total,
				"per_page" => $event->per_page,
				"current_page" => $event->current_page,
				"last_page" => $event->last_page,
				"first_page_url" => $event->first_page_url,
				"last_page_url" => $event->last_page_url,
				"next_page_url" => $event->next_page_url,
				"prev_page_url" => $event->prev_page_url,
				"from" => $event->from,
				"to" => $event->to,
				"data" => $event->data,
			];

			return formatResponse(200, 'Ok', true, $payload);
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}

	/**
	 * Fetch a single event
	 * 
	 * @param  int $id
	 * @return json
	 */
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

	/**
	 * Update an event's attributes
	 * 
	 * @param  array $data
	 * @param  int $id
	 * @return json
	 */
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

	/**
	 * Delete an event
	 * 
	 * @param  int $id
	 * @return json
	 */
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