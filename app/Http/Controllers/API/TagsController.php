<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagsController extends Controller
{
    public function getTags()
    {
        $query = Request::get('search');

        if ($query == null || $query == '') {
            $tags = Tag::all();
        } else {
            $tags = Tag::where('tag', 'LIKE', $query . '%')->get();
        }

        return response()->json($tags);
    }
}
