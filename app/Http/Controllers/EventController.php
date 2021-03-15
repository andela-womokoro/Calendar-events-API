<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
	protected $eventRepository;

	/**
	 * Constructor
	 */
	public function __construct(EventRepository $eventRepository)
	{
		$this->eventRepository = $eventRepository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$begin = ($request->filled('begin')) ? $request->query('begin') : 0;
        $perPage = ($request->filled('per_page')) ? $request->query('per_page') : 10;
        $sortBy = ($request->filled('sort_by')) ? $request->query('sort_by') : "date";
        $sortDirection = ($request->filled('sort_direction')) ? $request->query('sort_direction') : "asc";
        $fromDate = ($request->filled('from_date')) ? $request->query('from_date') : null;
        $toDate = ($request->filled('to_date')) ? $request->query('to_date') : null;

        return $this->eventRepository->fetchMany($begin, $perPage, $sortBy, $sortDirection, $fromDate, $toDate);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validator  =   Validator::make($request->all(), [
			"description"  =>  "required",
			"date"  =>  "required|date_format:Y-m-d",
			"time"  =>  "required||date_format:H:i:s",
			"location"  =>  "required",
		]);

		if($validator->fails()) {
			return formatResponse(400, $validator->errors());
		}

		$inputs = $request->all();

		$data = [
			"description" => $inputs["description"],
			"date" => $inputs["date"],
			"time" => $inputs["time"],
			"location" => $inputs["location"],
		];

		return $this->eventRepository->create($data);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if (is_numeric($id)) {
            return $this->eventRepository->fetchOne($id);
        }

        return formatResponse(400, 'Bad Request');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$validator  =   Validator::make($request->all(), [
			"description"  =>  "filled",
			"date"  =>  "filled|date_format:Y-m-d",
			"time"  =>  "filled||date_format:H:i:s",
			"location"  =>  "filled",
		]);

		if($validator->fails()) {
			return formatResponse(400, $validator->errors());
		}

		$data = [];

		if ($request->has('description')) {
            $data['description'] = $request->description;
        }

        if ($request->has('date')) {
            $data['date'] = $request->date;
        }

        if ($request->has('time')) {
            $data['time'] = $request->time;
        }

        if ($request->has('location')) {
            $data['location'] = $request->location;
        }

		if (is_numeric($id)) {
            return $this->eventRepository->update($data, $id);
        }

        return formatResponse(400, 'Bad Request');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if (is_numeric($id)) {
            return $this->eventRepository->delete($id);
        }

        return formatResponse(400, 'Bad Request');
	}
}
