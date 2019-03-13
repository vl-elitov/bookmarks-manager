<?php

namespace App\Http\Controllers;

use App\Bookmarks;
use Illuminate\Http\Request;

class BookmarksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return $this
     */
    public function index()
    {
        $bookmarks = Bookmarks::where('user_id', auth()->user()->id)->get();
        return view('home')->with('bookmarks', $bookmarks);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255'
        ]);

        // Create Bookmark
        $bookmark = new Bookmarks;
        $bookmark->name = $request->input('name');
        $bookmark->url = $request->input('url');
        $bookmark->description = $request->input('description');
        $bookmark->user_id = auth()->user()->id;

        $bookmark->save();

        return redirect('/home')->with('success', 'Bookmark Added');
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id, Request $request)
    {
        $bookmark = Bookmarks::find($id);
        $bookmark->delete();
        $request->session()->flash('success', 'Bookmark removed!');
        return 'success';
    }
}
