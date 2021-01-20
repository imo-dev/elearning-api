<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'only' => [
                'me',
            ]
        ]);
    }

    /**
     * Register
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        // make validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string|unique:users|min:5|max:13|alpha_dash',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:5|max:16|confirmed',
            'phone' => 'required|numeric|digits_between:8,15|unique:users',
            'role' => 'required|string|in:Admin,User'
        ]);

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );
        
        // 
        $store = null;
        DB::transaction(function () use ($request, &$store) {
            // store new user
            $store = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => $request->role
            ]);
        });

        // response token
        return apiResponse(
            $store,
            "register account success.",
            true,
            null, [],
            201
        );
    }

    /**
     * Login
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        // make validator
        $validator = Validator::make($request->all(), ([
            'email' => 'required|string|email|min:3',
            'password' => 'required|string|min:3'
        ]));

        // validate fails
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // set credentials
        $credentials = $request->only(['email', 'password']);

        // attempt
        if (!Auth::attempt($credentials)) {
            return apiResponse(
                $request->all(),
                "These credentials do not match our records.",
                false,
                'auth.login.attempt',
                [],
                401
            );
        }

        // get user
        $user = Auth::user();

        // make history
        $this->createLoginHistory($user, $request);

        // response token
        return apiResponse(
            $user,
            "Get auth token success.",
            true,
            null, [],
            200,
            [
                'credentials' => $this->respondWithToken($user)
            ]
        );
    }

    /**
     * Get auth profile
     *
     * @return void
     */
    public function me()
    {
        $user = Auth::user();

        return apiResponse(
            ($user),
            "Get user profile success.",
            true,
            null, [],
            200
        );
    }

    /**
     * Get Location Detail From IP Client
     *
     * @param Request $request
     * @return void
     */
    protected function getLocationFromIP(Request $request)
    {
        $api = 'http://ip-api.com/json/' . $request->ip();
        try {
            $response = Http::get($api);
            return $response->json();
        } catch (\Throwable $th) {
            return ['message' => 'failed get data', 'error' => $th->getMessage()];
        }
    }

    /**
     * Create login history
     *
     * @param User $user
     * @param Request $request
     * @return void
     */
    protected function createLoginHistory(User $user, Request $request) : void
    {
        DB::transaction(function () use ($user, $request) {
            $user_agent = $request->header('User-Agent');
            $ip_address = $request->ip();
            $location = json_encode($this->getLocationFromIP($request));

            // store new history
            $user->login_histories()->create([
                'user_agent' => $user_agent,
                'ip_address' => $ip_address,
                'location' => $location
            ]);

            // update last login without updated_at
            $user->timestamps = false;
            $user->last_login = Carbon::now();
            $user->save();
            $user->timestamps = true;
        });
    }

    /**
     * Response credentials
     *
     * @param string $token
     * @return array
     */
    protected function respondWithToken(User $user) : array
    {
        $token_name = env('APP_NAME', 'elearning') . '_TOKEN_USER_ACCESS';
        $token = $user->createToken($token_name)->accessToken;
        return [
            'token' => $token,
            'token_type' => 'bearer'
        ];
    }
}
