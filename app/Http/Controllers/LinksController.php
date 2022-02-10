<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLinkRequest;

class LinksController extends Controller
{
    public function index()
    {
        $links = Link::whereBelongsTo(User::find(auth()->user()->id))->orderBy('id' , 'DESC')->get();
        return response()->json([
            'links' => $links
        ]);
    }
    public function storeLink(StoreLinkRequest $request)
    {
        $link = Link::create([
            'original_url' => $request->original_url,
            'short_url' => url('/') . '/' . Str::random(6),
            'user_id' => auth()->user()->id
        ]);
        return response()->json(['message' => 'short link was created successfully', 'link' => $link]);
    }
    public function destroy($id)
    {
        $link = Link::find($id);
        if ($link) {
            $link->delete();
            return response()->json([
                'message' => 'link deleted successfully',
                'link' => $link
            ]);
        } else {
            return response()->json([
                'message' => 'could not process request , please try again'
            ]);
        }
    }
    public function redirectToOriginal($url)
    {
        $original_url = Link::where('short_url', url('/') . '/' . $url)->first()->original_url;
        return redirect($original_url);
    }
}
