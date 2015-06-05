<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data){
		return Validator::make($data, [
			'open_id' => 'required|max:255',
			'create_time' => 'required',
			'status' => 'required',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data){
		return User::create([
			'open_id' => $data['name'],
			'create_time' => $data['date'],
			'status' => $data['status'],
		]);
	}
}
