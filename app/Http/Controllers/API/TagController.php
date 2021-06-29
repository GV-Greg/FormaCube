<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Model\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'DESC')->paginate(10);

        return TagResource::collection($tags);
    }

    /**
     * @param $field
     * @param $search
     * @return TagCollection
     */
    public function search($field, $search)
    {
        return new TagCollection(Tag::where($field,'LIKE',"%$search%")->paginate(10));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $tags = Tag::all();

        return response()->json([
            'tags' => $tags
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TagResource
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tag' => 'required|unique:tags',
        ]);

        $tag = new Tag();

        $tag->tag = $request->tag;
        $tag->save();

        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return TagResource
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $this->validate($request, [
            'tag' => 'required|string|min:2|max:191|unique:tags,tag,'.$tag->id,
        ]);

        $tag->tag = $request->tag;
        $tag->save();

        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return TagResource
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        $tag->delete();

        return new TagResource($tag);
    }
}
