<?php

namespace App\Http\Controllers;

use App\Models\Tuner;
// request path
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ResetRequest;
//facades path
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Password;
//events path
use Illuminate\Auth\Events\PasswordReset;
//validation path
use Illuminate\Validation\ValidationException;
//helper function path
use Illuminate\Support\Str;

// facades path
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as RulesPassword;
class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|exists:tuners',
            'password' => 'required|string|between:6,20',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {

            $user = Tuner::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Bad request'
                ], 401);
            }

            $token = $user->createToken('auth_token', ['*'], now()->addDay())->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Bad request: ' . $e->getMessage()
            ], 401);
        }
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,255',
            'nickname' => 'required|string|between:2,255',
            'slug' => 'required|string|between:2,255',
            'language' => 'required|string|max:2',
            'email' => 'required|string|email|max:255|unique:tuners',
            'password' => 'required|string|confirmed|between:6,20',
            'portale' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {

            $user = Tuner::create($validator->validated());

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Bad request: ' . $e->getMessage()
            ], 401);
        }
    }
    /**
     * User forget password API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:tuners,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => __('A password reset link has been sent to your email.')
                ], 200);
            }

            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Bad request: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * User reset password API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(), [
                'token' => ['required'],
                'email' => ['required', 'email', 'exists:tuners,email'],
                'password' => ['required', 'confirmed', 'min:8', 'max:10', RulesPassword::defaults()],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

           
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    // Revoke all access tokens (for Laravel Sanctum or Passport)
                    $user->tokens()->delete();

                    event(new PasswordReset($user));
                }
            );

            
            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'message' => __('Password reset successfully.')
                ], 200);
            }

           
            $message = match ($status) {
                Password::INVALID_TOKEN => 'The password reset token is invalid.',
                Password::INVALID_USER => 'We can\'t find a user with that email address.',
                Password::RESET_THROTTLED => 'Too many attempts. Please try again later.',
                default => trans($status),
            };

            throw ValidationException::withMessages([
                'email' => [$message],
            ]);

        } catch (Exception $e) {
         
            return response()->json([
                'message' => 'Bad request: ' . $e->getMessage()
            ], 400);
        }
    }
}
