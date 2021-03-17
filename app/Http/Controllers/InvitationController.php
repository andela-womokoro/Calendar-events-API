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
    public function store(Request $request)
    {
        $validator  =   Validator::make($request->all(), [
            "event_id"  =>  "required",
            "user_id"  =>  "required",
        ]);

        if($validator->fails()) {
            return formatResponse(400, $validator->errors());
        }

        $inputs = $request->all();

        $data = [
            "event_id" => $inputs["event_id"],
            "user_id" => $inputs["user_id"],
        ];

        return $this->invitationRepository->create($data);
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
        if (is_numeric($id)) {
            return $this->invitationRepository->delete($id);
        }

        return formatResponse(400, 'Bad Request');
    }
}
