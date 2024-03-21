<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $slug = $request->get('slug');
        $page = Page::where('slug', $slug)->first();

        return view('pages.index', compact('page', 'slug'));
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
        $page = Page::where('slug', $requestData['slug'])->first();
        if ($page) {
            $page->update($requestData);
        } else {
            Page::create($requestData);
        }

        return redirect('admin/pages?slug=' . $requestData['slug'])->with('success', 'Изменения сохранены');
    }
}
