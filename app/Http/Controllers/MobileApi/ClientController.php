<?php
/**
 * Created by PhpStorm.
 * User: Sivaraj
 * Date: 22-04-2019 022
 * Time: 15:32
 */

namespace App\Http\Controllers\API;

use App\Client;
use App\Http\Resources\Client as ClientResource;
use App\Http\Resources\ClientCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;


class ClientController extends BaseController
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
//		$this->middleware(['verified']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * // * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//		$user = Auth::user();
		//		return $user;
		//		return new ClientCollection(Client::all());
		//		return $this->sendResponse($user, 'User Details Retrieved Successfully.');

		$users = Client::find(9);
		return new ClientResource($users);
		//		return new ClientCollection($users);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param Client $user
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'first_name' => 'required',
			'last_name' => 'nullable',
			'gender' => 'nullable',
			'dob' => 'sometimes|required|date_format:Y-m-d',
			'email' => 'required|email|unique:clients',
		]);


		if ($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}

		$userDetails = VehicleType::create($request->all());

		return $this->sendResponse($userDetails->toArray(), 'User created successfully.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		return $this->sendResponse(Auth::user(), 'Profile Details Retrieved Successfully.');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param Client $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Client $user)
	{
		$input = $request->all();

		$validator = Validator::make($request->all(), [
			'first_name' => 'required',
			'last_name' => 'nullable',
			'gender' => 'nullable',
			'dob' => 'sometimes|required|date_format:Y-m-d',
			'email' => 'required|email|unique:clients',
			'email' => 'required | email | unique:clients,email,' . $user->id,
		]);

		if ($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}

		$user->name = $input['name'];
		$user->description = $input['description'];
		$user->save();

		return $this->sendResponse($user->toArray(), 'VehicleType updated successfully.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(VehicleType $vehicleType)
	{
		$vehicleType->delete();

		return $this->sendResponse($vehicleType->toArray(), 'VehicleType deleted successfully.');
	}
}