<?php

namespace App\Http\Controllers\MobileApi;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\MobileApi\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Traits\UploadTrait;

class ProfileController extends BaseController
{
	use UploadTrait;

	/**
	 * Display the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getClientProfile()
	{
		return $this->sendResponse(Auth::user(), 'Profile Details Retrieved Successfully.');
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function updateClientProfile(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'first_name' => 'required',
			'last_name' => 'sometimes|required',
			'gender' => 'sometimes|required',
			'dob' => 'sometimes|required|date_format:Y-m-d',
			'email' => 'required|email|unique:clients,email,' . Auth::user()->id,
		]);

		if ($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}

		$validatedData = $validator->validated();

		$validatedData['name'] = $request->input('first_name') . ' ' . $request->input('last_name');

		$updateUser = Client::where('id', Auth::user()->id)->update($validatedData);

		if ($updateUser) {
			return $this->sendResponse([], 'Profile updated successfully.');
		}
	}

	public function updateClientProfilePicture(Request $request)
	{
		// $file = $request->profileImage->store('uploads', 'public');

		$validator = Validator::make($request->all(), [
			'profileImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);

		if ($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}

		$client = Auth::user();
		$old_image_path = $client->profile_picture;  // Value is not URL but directory file path
		$filePath = '';
		if ($request->has('profileImage')) {
			// Get image file
			$image = $request->file('profileImage');

			// Make a image name based on user name and current timestamp
			$name = $client->id . '' . time();

			// Define folder path
			$folder = '/uploads/images';

			// Make a file path where image will be stored [ folder path + file name + file extension]
			// $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();

			// Upload image
			$this->filePath = $this->uploadOne($image, $folder, 'public', $name);

			// Set user profile image path in database to filePath
			$client->profile_picture = $this->filePath;
		}
		// Persist user record to database
		$client->save();

		//		$image = $request->file('profileImage');
		//		$imageName = time().'.'.$image->getClientOriginalExtension();
		//		$destinationPath = public_path('/images');
		//		$image->move($destinationPath, $imageName);

		//		$client->profile_picture = $imageName;
		//		$client->save();

		if ($client->exists) {
			// Remove old Image
			$this->unlinkImage($old_image_path);
			return $this->sendResponse([], 'Profile Picture uploaded successfully.');
		}

		return $this->sendError([], 'Error In Uploading Profile Picture.');
	}
}
