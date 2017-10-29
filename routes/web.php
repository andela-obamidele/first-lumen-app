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
        $router->get(
            '/', function () {
                return 'NOTE TAKER API v1.0';
            }
        );
        $router->post(
            '/users', 'UserController@store'
        );
        $router->get(
            '/users', 'UserController@getAllUsers'
        );
        $router->get(
            '/users/{id}', 'UserController@getUser'
        );
        $router->put('/users/{id}', 'UserController@update');
        $router->delete('/users/{id}', 'UserController@delete');

        $router->post('/notes', 'NoteController@store');
        $router->get('/notes', 'NoteController@getAllNotes');
        $router->get('/notes/{id}', 'NoteController@getNote');
        $router->put('/notes/{id}', 'NoteController@update');
        $router->delete('/notes/{id}', 'NoteController@delete');
    } 
);
