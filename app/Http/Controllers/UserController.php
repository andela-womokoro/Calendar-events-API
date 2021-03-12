<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UserController extends Controller
{
	protected $userRepository;

	/**
	 * Constructor
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * Register a user
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$validator  =   Validator::make($request->all(), [
			"username"  =>  "required|unique:users,username",
			"password"  =>  "required",
			"first_name"  =>  "required",
			"last_name"  =>  "required",
			"email"  =>  "required|email|unique:users,email",
		]);

		if($validator->fails()) {
			return formatResponse(400, $validator->errors());
		}

		$inputs = $request->all();
		$inputs["password"] = Hash::make($request->password);

		$data = [
			"username" => $inputs["username"],
			"password" => $inputs["password"],
			"first_name" => $inputs["first_name"],
			"last_name" => $inputs["last_name"],
			"email" => $inputs["email"],
		];

		return $this->userRepository->create($data);
	}

	/**
	 * Login a user
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"email" =>  "required|email",
			"password" =>  "required",
		]);

		if($validator->fails()) {
			return formatResponse(400, $validator->errors());
		}

		$data = [
			"email" => $request->email,
			"password" => $request->password,
		];

		return $this->userRepository->login($data);
	}

	/**
	 * Logout a user
	 * 
	 * @return [type]
	 */
	public function logout()
	{
		return $this->userRepository->logout();
	}
}
