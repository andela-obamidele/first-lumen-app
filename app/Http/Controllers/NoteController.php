<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * It contains methods for carrying out operation on Note model
 *
 * @category Controller
 *
 * @package None
 *
 * @author Olufisayo Bamidele <fisiwizy@gmail.com>
 *
 * @license /license.md MIT
 *
 * @link None
 */
class NoteController extends Controller
{

    public $note;

    /**
     * Inject an instance of Note model into NoteController
     *
     * @param Note $note Note model
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    /**
     * It saves a note
     *
     * @param Request $request HTTP request
     *
     * @return Illuminate\Http\Response JSON response
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
                'title' => 'max:80',
                'content' => 'required',
            ]
        );

        $requestBody = $request->all();
        $note = new Note();
        $note->user_id = JWTAuth::parseToken()->toUser()->id;
        $note->title = $requestBody['title'] ?? '';
        $note->content = $requestBody['content'];

        $note->save();

        return response()->json(
            [
                'note' => $note,
            ],
            201
        );

    }

    /**
     * It responds with all notes belonging to currently authenticated user
     *
     * @return Illuminate\Http\Response JSON response
     */
    public function getAllNotes()
    {
        $currentUserId = JWTAuth::parseToken()->toUser()->id;
        $notes = $this->note
            ->orderBy('updated_at', 'desc')
            ->where('user_id', $currentUserId)
            ->get();

        if (count($notes) > 0) {
            return response()->json(
                ['notes' => $notes],
                200
            );
        }

        return response()->json(
            [
                'error' => 'no notes in the database',
            ],
            404
        );
    }

    /**
     * It responds with all a note
     *
     * @param int $noteId id of the note to be returned
     *
     * @return Illuminate\Http\Response JSON response
     */
    public function getNote($noteId)
    {
        $currentUserId = JWTAuth::parseToken()->toUser()->id;
        $note = Note::find($noteId);

        if (!$note) {
            return response()->json(
                ['error' => 'note not found'],
                404
            );
        }
        if ($currentUserId !== $note->user_id) {
            return response()
                ->json(
                    [
                        'error' =>
                        'sorry, you don\'t have permission to view this file'],
                    403
                );
        }

        return response()->json(
            [
                'note' => $note,
            ],
            200
        );

    }

    /**
     * It responds with all a note
     *
     * @param Request $request Laravel Http Request
     * @param int     $noteId  id of the note to be returned
     *
     * @return Illuminate\Http\Response JSON response
     */
    public function update(Request $request, $noteId)
    {
        $currentUserId = JWTAuth::parseToken()->toUser()->id;
        $note = Note::find($noteId);

        if ($currentUserId !== $note->user_id) {
            return response()->json(
                [
                    'error' => 'sorry, you don\'t have permission to view this file',
                ],
                403
            );
        }

        if ($note) {
            $note->update($request->only(['title', 'content']));

            return response()->json(
                [
                    'note' => $note,
                ],
                200
            );
        }
        return response()->json(
            [
                'error' => true,
                'message' => 'something went wrong',
            ],
            400
        );
    }

    /**
     * It deletes a note that belongs to the currently logged in user
     *
     * @param int $noteId Id of the note to be deleted
     *
     * @return Illuminate\Http\Response Http response of success message or error
     */
    public function delete($noteId)
    {
        $currentUserId = JWTAuth::parseToken()->toUser()->id;
        $note = $this->note->find($noteId);

        if ($currentUserId !== $note->user_id) {
            return response()
                ->json(
                    [
                        'error' =>
                        'sorry, you don\'t have permission to view this file'],
                    403
                );
        }

        if ($note) {
            $note->delete();
            return response()->json(
                [
                    'message' => 'note deleted successfully',
                ],
                204
            );
        }

        return response()->json(
            [
                'error' => true,
                'message' => 'something went wrong',
            ],
            400
        );
    }

    /**
     * This deletes multiple notes
     *
     * @param string $noteIds Ids of notes
     *
     * @return Illuminate\Http\Response Http response of success message or error
     */
    public function bulkDelete($noteIds)
    {
        $currentUserId = JWTAuth::parseToken()->toUser()->id;
        $ids = explode(',', $noteIds);
        $foundNotes = $this->note->whereIn('id', $ids)
            ->where('user_id', $currentUserId)->get()->count();

        if ($foundNotes > 0) {
            $this->note->destroy($ids);

            return response()->json(
                [
                    'message' => 'delete successful',
                ]
            );
        }

        return response()->json(
            [
                'error' => true,
                'message' => 'could not delete the notes',
            ],
            404
        );
    }
}
