<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
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
     * Controller for POST /users. Creates an new user
     *
     * @param Request $request Http Request object
     *
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate(
            $request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'username' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required'
            ]
        );

        $user = new User();
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);

        $user->save();

        unset($user->password);

        return array('user'=>$user);
    }
    
    public function getAllUsers() 
    {
        return User::all();
    }

    public function getUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(
                array(
                    'error'=>true,
                    'message'=>'user not found',
                    'id'=>$id
                ),
                404
            );
        }  
        return response()->json(
            array(
                'error'=>false,
                'user'=>$user
            ),
            200
        );
    }

    public function update(Request $request, $userId)
    {
        $loggedInUserId =  JWTAuth::parseToken()->toUser()->id;

        $user = User::find($userId);
        if ($user && $loggedInUserId == $userId) {
            $password = $request->input('password');
            if ($password) {
                $request->password = Hash::make($password);
            }
            $user->update($request->only('firstname', 'lastname', 'password'));
            return response()->json(
                [
                    'error'=>false,
                    "user"=> $user
                ],
                200
            );
        }
    }
        
    public function delete(Request $request, $userId)
    {
        $loggedInUserId = JWTAuth::parseToken()->toUser()->id;

        $user = User::find($userId);
        if ($user && $userId == $loggedInUserId) {
            $user->delete();
            return response()->json(
                [
                        'error'=>false,
                        'message'=>'user delete successful'
                ],
                204
            );
        }
        return response()->json(
            [
                    'error'=> true,
                    'message'=> 'You can only delete your own account'
            ],
            400
        );
    }
    //
}
