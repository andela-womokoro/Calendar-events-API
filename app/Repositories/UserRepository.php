<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository extends BaseRepository
{
	public function fetchMany($begin, $perPage, $sortBy, $sortDirection)
	{
		//
	}

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