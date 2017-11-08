<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group(
    ['prefix' => 'api/v1'], function () use ($router) {
        $router->post('/auth/login', 'AuthController@postLogin');
        
        $router->get(
            '/', function () {
                return 'NOTE TAKER API v1.0';
            }
        );
        $router->post(
            '/users', 'UserController@store'
        );
        $router->get(
            '/users',['middleware'=>'auth'], 'UserController@getAllUsers'
        );
        $router->get(
            '/users/{id}', ['middleware'=>'auth'], 'UserController@getUser'
        );
        $router->put('/users/{id}', ['middleware'=>'auth'], 'UserController@update');
        $router->delete('/users/{id}', ['middleware'=>'auth'], 'UserController@delete');

        $router->post('/notes',['middleware'=>'auth'], 'NoteController@store');
        $router->get('/notes', ['middleware'=>'auth'],'NoteController@getAllNotes');
        $router->get('/notes/{id}', ['middleware'=>'auth'],'NoteController@getNote');
        $router->put('/notes/{id}', ['middleware'=>'auth'],'NoteController@update');
        $router->delete('/notes/{id}', ['middleware'=>'auth'],'NoteController@delete');
    }
);
