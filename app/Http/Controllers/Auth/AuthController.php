<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\RegisterNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        //$users = User::first();

        $user = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required' 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

       // $when = Carbon::now()->addSeconds(10);

        //Notification::sendNow($users, new RegisterNotification($request->first_name));

        //$user->verify((new RegisterNotification($user))->delay($when));

        //event(new Registered($user));

        $token  = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token,
        ];

        return response($response, 201);
    }

        public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

      $user = User::where('email', $data['email'])->first();

      if(!$user || !Hash::check($data['password'], $user->password))
      {
          return response(['message'=>'invalid credentials'], 401);
      } else {
        $token  = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token,
        ];

        //Cache::put('user');

        return response($response, 200);
      }
    }

           public function logout(Request $request): jsonResponse
    {
        if (!$request->user()) {
            return $this->sendError('Unauthenticated', null, 401);
        }
        
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
        }

        return response()->json([
            'user' => $request->user(),
        ], "Authentcated User Retrieved Successfully");
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ], "Authentcated User Retrieved Successfully");
    }
}
