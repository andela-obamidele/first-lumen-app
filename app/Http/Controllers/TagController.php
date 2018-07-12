<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{

    private $tag;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function create(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $tag = $this->tag->create($request->only('name'));

        return $tag;
    }

    //
}
