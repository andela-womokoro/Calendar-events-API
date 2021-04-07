<?php

namespace App\Repositories;

use Exception;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
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
				"created_by" => $data['created_by'],
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
	 * @param  string $fromDate
	 * @param  string $toDate
	 * @return json
	 */
	public function fetchMany($userId, $begin, $perPage, $sortBy, $sortDirection, $fromDate = null, $toDate = null)
	{
		try {
			$events = null;

			if (!is_null($fromDate) && !is_null($toDate)) {
				$events = Event::where('created_by', '=', $userId)
								->where('date', '>=', $fromDate)
								->where('date', '<=', $toDate)
								->orderBy($sortBy, $sortDirection)
								->offset($begin)
								->limit($perPage)
								->paginate($perPage)
								->withQueryString();
			} else {
				$events = Event::where('created_by', '=', $userId)
								->orderBy($sortBy, $sortDirection)
								->offset($begin)
								->limit($perPage)
								->paginate($perPage)
								->withQueryString();
			}

			$events = json_decode(json_encode($events));

			if(!$events->data){
				return formatResponse(404, 'No events found for this user.');
			}

			$eventsWithWeather = [];

			foreach($events->data as $event) {
				$event = (array) $event;
				array_push($eventsWithWeather, $this->appendWeatherData($event));
			}
			
			$payload = [
				"total" => $events->total,
				"per_page" => $events->per_page,
				"current_page" => $events->current_page,
				"last_page" => $events->last_page,
				"first_page_url" => $events->first_page_url,
				"last_page_url" => $events->last_page_url,
				"next_page_url" => $events->next_page_url,
				"prev_page_url" => $events->prev_page_url,
				"from" => $events->from,
				"to" => $events->to,
				"payload" => $eventsWithWeather,
			];

			return formatResponse(200, 'Ok', true, $payload);
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}

	/**
	 * Add weather data to an event
	 * 
	 * @param  array $event
	 * @return array
	 */
	private function appendWeatherData($event)
	{
		$weatherData = [
				"description" => "-",
				"temperature" => "-",
				"humidity" => "-",
			];
		
		try {
			$weatherApiResponse = Http::get("api.openweathermap.org/data/2.5/weather", [
			    "q" => $event["location"],
			    "appid" => config('openweather.api_key'),
			    "units" => config('openweather.temperature_unit'),
			]);

			if ($weatherApiResponse->ok()) {
				$jsonData = $weatherApiResponse->json();
				$weatherData["description"] = $jsonData["weather"][0]["description"];
				$weatherData["temperature"] = $jsonData["main"]["temp"];
				$weatherData["humidity"] = $jsonData["main"]["humidity"];
			}
		} catch (ConnectionException $ce) {}

		$event['weather'] = $weatherData;

		return $event;
	}

	/**
	 * Fetch a single event
	 * 
	 * @param  int $id
	 * @return json
	 */
	public function fetchOne($userId, $eventId)
	{
		try {
			$event = Event::where('id', '=', $eventId)
							->where('created_by', '=', $userId)
							->firstOrFail();
		
			return formatResponse(200, 'Ok', true, $this->appendWeatherData($event));
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
	public function update($data)
	{
		try {
			$event = Event::where('id', '=', $data['event_id'])
							->where('created_by', '=', $data['user_id'])
							->firstOrFail();

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
	public function delete($userId, $eventId)
	{
		try {
			$event = Event::where('id', '=', $eventId)
							->where('created_by', '=', $userId)
							->firstOrFail();
			
			$event->delete();

			return formatResponse(200, 'Event deleted', true, []);
		} catch (ModelNotFoundException $mnfe) {
			return formatResponse(404, 'Event not found');
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}

	/**
	 * fetch locations for events with weather for a time interval
	 * 
	 * @param  array $data
	 * @return json
	 */
	public function fetchLocations($data)
	{
		try 
		{
			$events = Event::where('date', '>=', $data["from_date"])
							->where('date', '<=', $data["to_date"])
							->distinct()
							->get(['location']);

			$events = json_decode(json_encode($events));
			$eventsWithWeather = [];

			foreach($events as $event) {
				$event = (array) $event;
				array_push($eventsWithWeather, $this->appendWeatherData($event));
			}

			return formatResponse(200, 'Success', true, $eventsWithWeather);
		} catch (Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}
}