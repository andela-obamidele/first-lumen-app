<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Note;

class NoteController extends Controller
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

    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
              'title'=>'required',
              'content'=>'required'
            ]
        );

        $note = new Note();
        $note->user_id = JWTAuth::parseToken()->toUser()->id;
        $note->title = $request['title'];
        $note->content = $request['content'];

        $note->save();

        return response()->json(
            [
                'note'=>$note           
            ],
            201
        );

    }

    public function getAllNotes(Request $request)
    {
        $currentUserId = JWTAuth::parseToken()->toUser()->id;
        $notes = Note::all()->where('user_id',$currentUserId);
        if (count($notes) > 0) {
            return response()->json(
                ['notes'=>$notes],
                200
            );
        }

        return response()->json(
            [
             'error'=>'no notes in the database'
            ],
            404
        );
    }

    public function getNote(Request $request, $id)
    {
       $currentUserId =  JWTAuth::parseToken()->toUser()->id;
       $note = Note::find($id);
       
        if (!$note) {
            return response()->json(
                ['error' => 'note not found'],
                404
            );
        }
        if ( $currentUserId !== $note->user_id) {
            return response()->json(['error' => 'sorry, you don\'t have permission to view this file'], 403);
        }
     
        return response()->json(
            [
                'note'=>$note
            ],
            200
        );

    }

    public function update(Request $request, $id) 
    {
        $currentUserId = JWTAuth::parseToken()->toUser()->id;
        $note = Note::find($id);
        
        if ( $currentUserId !== $note->user_id) {
            return response()->json(['error' => 'sorry, you don\'t have permission to view this file'], 403);
        }

        if ($note) {
            $note->update($request->only(['title', 'content']));

            return response()->json(
                [
                    'note'=>$note
                ],
                200
            );
        }
        return response()->json(
            [
                'error'=> true,
                'message'=>'something went wrong'
            ],
            400
        );
    }

    public function delete(Request $request, $id)
    {
        $currentUserId =  JWTAuth::parseToken()->toUser()->id;
        $note = Note::find($id);

        if ( $currentUserId !== $note->user_id) {
            return response()->json(['error' => 'sorry, you don\'t have permission to view this file'], 403);
        }

        if ($note) {
            $note->delete();
            return response()->json(
                [
                'message'=>'note deleted successfully'
                ],
                204
            );
        }

        return response()->json(
            [
                'error'=>true, 
                'message'=> 'something went wrong'
            ],
            400
        );
    }
    //
}
