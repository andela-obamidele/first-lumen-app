<?php

/*
--------------------------------------------------------------------------
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

        $router->post('/users', 'UserController@store');

        $router->get(
            '/users', [
                'middleware' => 'auth',
                'uses' => 'UserController@getAllUsers',
            ]
        );

        $router->get(
            '/users/{id}',
            ['middleware' => 'auth',
            'uses' => 'UserController@getUser']
        );

        $router->put(
            '/users/{id}',
            [
                'middleware' => 'auth',
                'uses' => 'UserController@update'
            ]
        );

        $router
            ->delete(
                '/users/{id}', 
                [
                    'middleware' => 'auth',
                    'uses' => 'UserController@delete'
                ]
            );

        $router->post(
            '/notes',
            [
                    'middleware' => 'auth',
                    'uses' => 'NoteController@store'
            ]
        );

        $router->get(
            '/notes',
            [
                'middleware' => 'auth',
                'uses' => 'NoteController@getAllNotes'
            ]
        );
    
        $router->get(
            '/notes/{id}',
            [
                'middleware' => 'auth',
                'uses' => 'NoteController@getNote'
            ]
        );

        $router->put(
            '/notes/{id}',
            [
                'middleware' => 'auth',
                'uses' => 'NoteController@update'
            ]
        );

        $router->delete(
            '/notes/{id}',
            [
                'middleware' => 'auth',
                'uses' => 'NoteController@delete'
            ]
        );
    }
);
