<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'password' => 'required|min:3|confirmed',
            'roles' => 'required',
            'branch_id' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->roles = $request->roles;
        $user->branch_id = $request->branch_id;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['success' => true], 200);
    }

    public function updateUser(Request $request, User $user)
    {
        if ($request->input('name') != "") $user->name = $request->input('name');
        if ($request->input('email') != "") $user->email = $request->input('email');
        if ($request->input('mobile') != "") $user->mobile = $request->input('mobile');
        if ($request->input('branch_id') != "") $user->branch_id = $request->input('branch_id');
        if ($request->input('password') != "") $user->password = bcrypt($request->input('password'));
        $user->save();

        if($user->exists()) {
            return response()->json(['success' => true], 200);
        }
    }

    /**
     * Login user and return a token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(['success' => true], 200)->header('Authorization', $token);
        }
        return response()->json(['success' => false, 'message' => 'unAuthorized'], 401);
    }

    public function user_login(Request $request)
    {
        $request->request->add(['roles' => 'user']);
        $credentials = $request->only('email', 'password', 'roles');

        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(['success' => true], 200)->header('Authorization', $token);
        }
        return response()->json(['success' => false, 'message' => 'unAuthorized'], 401);
    }

    /**
     * Return auth guard
     */
    private function guard()
    {
        return Auth::guard();
    }

    /**
     * Logout User
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }
}
