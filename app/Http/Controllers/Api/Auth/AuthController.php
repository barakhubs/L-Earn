<?php

namespace App\Http\Controllers\Api\Auth;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{

	/*
	 * Register new user
	*/
	public function signup(Request $request) {
		$validatedData = $request->validate([
			'username' => 'required|string|max:255|unique:users,username',
			'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
			'password' => 'required|min:6|confirmed',
		]);

		$validatedData['password'] = Hash::make($validatedData['password']);

		if(User::create(
            [
                'username' => $validatedData['username'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'verified' => '0',
                'phone_verify_code' => rand(1000, 9999)
            ]
        )) {
            // send sms to user with verification code
            $username = env('SMS_USER_NAME'); // use 'sandbox' for development in the test environment
            $apiKey   = env('SMS_API'); // use your sandbox app API key for development in the test environment
            $AT       = new AfricasTalking($username, $apiKey);

            // Get one of the services
            $sms      = $AT->sms();

            // Use the service
            $result   = $sms->send([
                'to'      => '+256773034311',
                'message' => 'Hello World!'
            ]);

			return response()->json(array("message" => "Registered success"), 201);
		}

		return response()->json(null, 404);
	}

	/*
	 * Generate sanctum token on successful login
	*/
	public function login(Request $request) {
		$request->validate([
			'username' => 'required',
			'password' => 'required',
		]);

		$user = User::where('username', $request->username)->first();

		if (! $user || ! Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'username' => ['The provided credentials are incorrect.'],
			]);
		}

		return response()->json([
			'user' => $user,
			'access_token' => $user->createToken($request->username)->plainTextToken
		], 200);
	}


	/*
	 * Revoke token; only remove token that is used to perform logout (i.e. will not revoke all tokens)
	*/
	public function logout(Request $request) {

		// Revoke the token that was used to authenticate the current request
		$request->user()->currentAccessToken()->delete();
		//$request->user->tokens()->delete(); // use this to revoke all tokens (logout from all devices)
		return response()->json(null, 200);
	}


	/*
	 * Get authenticated user details
	*/
	public function getAuthenticatedUser(Request $request) {
		return $request->user();
	}


	public function sendPasswordResetLinkEmail(Request $request) {
		$request->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		if($status === Password::RESET_LINK_SENT) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}

	public function resetPassword(Request $request) {
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:8|confirmed',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) use ($request) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		if($status == Password::PASSWORD_RESET) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}
}
