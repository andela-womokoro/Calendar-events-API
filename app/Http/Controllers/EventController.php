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
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
