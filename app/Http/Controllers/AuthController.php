<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\InventoryProductDetail;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->get();
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
        $user->mobile = str_replace(' ', '', $request->mobile);
        $user->roles = $request->roles;
        $user->branch_id = $request->branch_id;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($user->exists()) {
            return response()->json(['success' => true], 200);
        }
    }

    public function updateUser(Request $request, User $user)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'branch_id' => 'required',
            'password' => 'nullable|min:3|confirmed'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = str_replace(' ', '', $request->input('mobile'));
        $user->branch_id = $request->input('branch_id');

        if ($request->input('password') != "") {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        if ($user->exists()) {
            return response()->json(['success' => true], 200);
        }
    }

    /**
     * Login user and return a token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        // Check Email Exist and get user
        $user = User::where('email', $request->email)->whereIn('roles', ['admin', 'account', 'stockuser'])->first();
        if (is_null($user)) {
            return response()->json(['success' => false, 'message' => 'unAuthorized'], 401);
        }

        $credentials = $request->only('email', 'password');
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

    public function destroy(User $user)
    {
        $userEntryExists = InventoryProductDetail::where('sale_by', $user->id)->get()->count();

        if ($userEntryExists == 0) {
            $user->forceDelete();
            return response()->json(['status' => 'success', 'message' => 'user deleted successfully'], 200);
        }
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'user soft-deleted successfully'], 200);
    }

    public function un_destroy($user)
    {
        $restored = User::withTrashed()->find($user)->restore();
        if ($restored) {
            return response()->json(['status' => 'success', 'message' => 'User Restored successfully'], 200);
        }
    }
}
