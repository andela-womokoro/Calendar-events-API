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
    public function store(Request $request, $userId, $eventId)
    {
        $validator  =   Validator::make($request->all(), [
            "invitee_id"  =>  "required",
        ]);

        if($validator->fails()) {
            return formatResponse(400, $validator->errors());
        }

        $inputs = $request->all();

        $data = [
            "event_id" => $eventId,
            "invitee_id" => $inputs["invitee_id"],
            "created_by" => $userId,
        ];

        return $this->invitationRepository->create($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId, $eventId, $invitationId)
    {
        return $this->invitationRepository->delete($userId, $invitationId);
    }
}
