<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
              'user_id'=>'required',
              'title'=>'required',
              'content'=>'required'
            ]
        );

        $note = new Note();
        $note->user_id = $request['user_id'];
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
        $notes = Note::all();
        if ($notes) {
            return response()->json([
                    'notes' => $notes
                ],
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
        $note = Note::find($id);
        if ($note) {
            return response()->json(
                [
                    'note'=>$note
                ],
                200
            );
        }

        return response()->json(
            ['error' => 'note not found'],
            404
        );
    }

    public function update(Request $request, $id) 
    {
        $note = Note::find($id);
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
        $note = Note::find($id);
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
