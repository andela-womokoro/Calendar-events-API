<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository extends BaseRepository
{
	/**
     * Create a user
     *
     * @param  array $data
     * @return json
     */
	public function create($data)
	{
		try {
			$user = User::create([
				"username" => $data["username"],
	            "password" => $data["password"],
	            "first_name" => $data["first_name"],
	            "last_name" => $data["last_name"],
	            "email" => $data["email"],
			]);

			if (is_null($user)) {
				return formatResponse(200, 'User not created');
			}

			return formatResponse(201, 'User created', true, $user);
		} catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }     
	}

	/**
	 * Login a user
	 * 
	 * @param  array $data
	 * @return JSON
	 */
	public function login($data)
	{
		$user = User::where("email", $data["email"])->first();

		if(is_null($user)) {
			return formatResponse(404, 'User with email '. $data["email"] .' does not exist.');
		}

		if(Auth::attempt(['email' => $data["email"], 'password' => $data["password"]])){
			$user = Auth::user();
			$token = $user->createToken('foo')->plainTextToken;

			return formatResponse(200, 'Login successful', true, ['token' => $token]);
		} else {
			return formatResponse(200, 'Invalid password');
		}
	}

	public function fetchMany($begin, $perPage, $sortBy, $sortDirection)
	{
		//
	}

	public function fetchOne($id)
	{
		//
	}

	public function update($data, $id)
	{
		//
	}

	public function delete($id)
	{
		//
	}
}