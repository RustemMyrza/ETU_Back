<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $data = Block::where('block_type', $type)->first();
        $block = collect();
        if ($data) {
            $data->content = json_decode($data->content);
            $block->title = $data->title;
            $block->sub_title = $data->content->sub_title;
            $block->content = $data->content->content;
            $block->block_type = $data->block_type;
        }
        return view('block.index', compact('block', 'type'));
    }

    public function store(Request $request)
    {

        $requestData = $request->all();
        $block = Block::where('block_type', $requestData['block_type'])->first();
        $requestData['content'] = json_encode($requestData['content']);
        if ($block) {
            $block->update($requestData);
        } else {
            Block::create($requestData);
        }
        return redirect('admin/block?type=' . $requestData['block_type'])->with('flash_message', 'Блок изменен');
    }
}
