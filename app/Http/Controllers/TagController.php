<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Note;
use App\User;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TagController extends Controller
{

    private $tag;
    private $note;
    private $userId;
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Tag $tag, Note $note, User $user)
    {
        $this->tag = $tag;
        $this->note = $note;
        $this->user = $user;
        $this->userId = JWTAuth::parseToken()->toUser()->id;
    }

    /**
     * Creates a tags
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $tag = $this->tag->create($request->only('name'));

        return response()->json(['tag' => $tag], 201);
    }

    /**
     * Get all tags
     */
    public function getAll()
    {
        $tags = $this->tag->all();

        if (!$tags) {
            return response()->json([
                'error' => true,
                'message' => 'no tag found',
            ], 404);
        }

        return response()->json(['tags' => $tags], 200);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'query' => 'required|max:150',
        ]);

        $query = $request->query()['query'];
        $tags = $this->tag
            ->where('name', 'LIKE', '%'.$query.'%')->get();

        if (!$tags) {
            return response()->json([
                'error' => true,
                'message' => 'no tag found',
            ], 404);
        }

        return response()->json(['tags' => $tags], 200);
    }
    //
}
