<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InvitationRepository;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    protected $invitationRepository;

    /**
     * Constructor
     */
    public function __construct(InvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $eventId)
    {
        $validator  =   Validator::make($request->all(), [
            "user_id"  =>  "required",
        ]);

        if($validator->fails()) {
            return formatResponse(400, $validator->errors());
        }

        $inputs = $request->all();

        $data = [
            "event_id" => $eventId,
            "user_id" => $inputs["user_id"],
        ];

        return $this->invitationRepository->create($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($eventId, $invitationId)
    {
        if (is_numeric($invitationId)) {
            return $this->invitationRepository->delete($invitationId);
        }

        return formatResponse(400, 'Bad Request');
    }
}
