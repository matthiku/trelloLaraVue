<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {

  }



  /**
   * Register a new user
   *
   * @return \Illuminate\Http\Response
   */
  public function register(Request $request)
  {
    $this->validate($request, [
      'username' => 'required',
      'password' => 'required',
      'email' => 'required|email|unique:users',
    ]);

    $user = User::create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => app('hash')->make($request->password),
      'api_token' => str_random(50),
    ]);

    return response()->json(['status'=>'success', 'user' => $user], 200);
  }



  /**
   * Login a user and return the user object incl token
   *
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request)
  {
    $this->validate($request, [
      'password' => 'required',
      'email' => 'required|email|unique:users',
    ]);

    // check if the user's email is correct
    $user = User::where('email', $request->email)->first();
    if (!$user)
      return response()->json(['status'=>'error', 'message' => 'User not found!'], 401);

    // check if the password is correct
    if (! app('hash')->check($request->password, $user->password))
      return response()->json(['status'=>'error', 'message' => 'Invalid credentials'], 401);

    // generate a new API token
    $user->api_token = str_random(50);
    $user->save();
    return response()->json(['status'=>'success', 'user' => $user], 200);
  }


  public function logout(Request $request)
  {
    $this->validate($request, [
      'api_token' => 'required',
    ]);

    if ($request->get('api_token'))
      $api_token = $request->api_token;
    else
      return response()->json(['status'=>'error', 'message' => 'invalid request!'], 403);

    $user = User::where('api_token', $request->api_token)->first();

    if (!$user)
      return response()->json(['status'=>'error', 'message' => 'Not logged in'], 401);

    $user->update(['api_token' => null]);
    return response()->json(['status'=>'success', 'message' => 'logged off'], 200);
  }
}
