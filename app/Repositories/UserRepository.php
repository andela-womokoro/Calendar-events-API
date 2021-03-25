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
	 * Authenticate a user and generate an access token
	 * 
	 * @param  array $data
	 * @return JSON
	 */
	public function login($data)
	{
		try {
			$user = User::where("email", $data["email"])->first();

			if(is_null($user)) {
				throw new ModelNotFoundException('User with email '. $data["email"] .' does not exist.');
			}

			if(Auth::attempt(['email' => $data["email"], 'password' => $data["password"]])){
				$user = Auth::user();
				$token = $user->createToken('token')->plainTextToken;

				return formatResponse(200, 'Login successful', true, ['token' => $token]);
			} else {
				return formatResponse(200, 'Invalid password');
			}
		} catch (ModelNotFoundException $mnfe) {
            return formatResponse(404, $mnfe->getMessage());
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }
	}

	/**
	 * Revovke a user's access token(s)
	 * 
	 * @return JSON
	 */
	public function logout()
	{
		try {
			auth()->user()->tokens()->delete();

			return formatResponse(200, 'Token(s) invalidated', true);
		} catch(Exception $e) {
			return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
		}
	}

	public function fetchMany($userId, $begin, $perPage, $sortBy, $sortDirection)
	{
		//
	}

	public function fetchOne($userId, $resourceId)
	{
		//
	}

	public function update($data)
	{
		//
	}

	public function delete($userId, $resourceId)
	{
		//
	}
}