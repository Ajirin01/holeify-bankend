<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Cookie;

use App\Models\Worker;
use App\Models\Requester;


class AuthController extends Controller
{
    public function user(){
        return Auth::user();
    }

    public function all(){
        return User::all();
    }

    public function register(Request $request){
        $user =  User::create(['name'=> $request->input(key: 'name'),
                            'email'=> $request->input(key: 'email'),
                            // 'phone'=> $request->input(key: 'phone'),
                            'role'=> $request->input(key: 'role'),
                            'password'=> Hash::make($request->input(key: 'password'))
                            ]);

        Worker::create(['user_id'=> $user->id, 'name'=> $user->name]);
        Requester::create(['user_id'=> $user->id, 'name'=> $user->name]);

        $this->login($request);

        
    }

    public function login(Request $request){
        // return $request->header('Api-Key');
        // return response()->json(Hash::make($request->input(key: 'password')));
        if(!Auth::attempt(['email'=> $request->input(key: 'email') , 'password'=> $request->input(key: 'password')])){
            return response()->json(['message'=> 'Invalid credentials'], status:Response::HTTP_UNAUTHORIZED);
        }else{
            $user = Auth::user();

            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('jwt', $token, 60 * 24); // 1 day

            return response()->json(['message'=> 'Success', 'user'=> User::where('id', $user->id)->with('worker', 'requester')->first(), 'token'=> $cookie], status:Response::HTTP_OK)->withCookie($cookie);
        }
    }

    public function logout(Request $request){
        $cookie = Cookie::forget('jwt');
        return response()->json(['message'=> 'Success'], status:Response::HTTP_OK)->withCookie($cookie);

    }

    public function updateAccount(Request $request){
        User::find(Auth::user()->id)->update($request->except('password'));
        User::find(Auth::user()->id)->update(["password"=> Hash::make($request->password)]);
        return response()->json(User::with("worker")->where("id", Auth::user()->id)->first());

    }
}
