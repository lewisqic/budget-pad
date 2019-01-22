<?php

namespace App\Http\Controllers\Account;

use App\Models\Tag;
use Facades\App\Services\TagService;
use App\Http\Controllers\Controller;


class AccountTagController extends Controller
{

    /**
     * Show the tags list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.account.tags.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = TagService::dataTables(app('company')->id, \Request::all());
        return response()->json($data);
    }

    /**
     * Show the tags create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Add',
        ];
        return view('content.account.tags.create-edit', $data);
    }

    /**
     * Show the tags create page
     *
     * @return view
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'tag' => $tag,
        ];
        return view('content.account.tags.create-edit', $data);
    }

    /**
     * Create new tag record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $tag = TagService::create($data);
        \Msg::success($tag->name . ' has been <strong>added</strong>');
        return redir('account/tags');
    }

    /**
     * Create new tag record
     *
     * @return redirect
     */
    public function update()
    {
        $tag = TagService::load(\Request::input('id'))->update(\Request::all());
        \Msg::success($tag->name . ' has been <strong>updated</strong>');
        return redir('account/tags');
    }

    /**
     * Delete an tag record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $tag = TagService::delete($id);
        \Msg::success($tag->name . ' has been <strong>deleted</strong> ' . \Html::undoLink('account/tags/' . $tag->id));
        return redir('account/tags');
    }

    /**
     * Restore an tag record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $tag = TagService::restore($id);
        \Msg::success($tag->name . ' has been <strong>restored</strong>');
        return redir('account/tags');
    }


}
