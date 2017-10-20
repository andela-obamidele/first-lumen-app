<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Controller for POST /users. Creates an new user
     * 
     * @param Request $request Http Request object
     * 
     * @return void
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
        $user->password = $request['password'];

        $user->save();

        unset($user->password);

        return array('user'=>$user);
    }
    

    //
}
