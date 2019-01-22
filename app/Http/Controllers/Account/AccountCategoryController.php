<?php

namespace App\Http\Controllers\Account;

use App\Models\Category;
use Facades\App\Services\CategoryService;
use App\Http\Controllers\Controller;


class AccountCategoryController extends Controller
{

    /**
     * Show the categories list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.account.categories.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = CategoryService::dataTables(app('company')->id, \Request::all());
        return response()->json($data);
    }

    /**
     * Show the categories create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Add',
        ];
        return view('content.account.categories.create-edit', $data);
    }

    /**
     * Show the categories create page
     *
     * @return view
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'category' => $category,
        ];
        return view('content.account.categories.create-edit', $data);
    }

    /**
     * Create new category record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $category = CategoryService::create($data);
        \Msg::success($category->name . ' has been <strong>added</strong>');
        return redir('account/categories');
    }

    /**
     * Create new category record
     *
     * @return redirect
     */
    public function update()
    {
        $category = CategoryService::load(\Request::input('id'))->update(\Request::all());
        \Msg::success($category->name . ' has been <strong>updated</strong>');
        return redir('account/categories');
    }

    /**
     * Delete an category record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $category = CategoryService::delete($id);
        \Msg::success($category->name . ' has been <strong>deleted</strong> ' . \Html::undoLink('account/categories/' . $category->id));
        return redir('account/categories');
    }

    /**
     * Restore an category record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $category = CategoryService::restore($id);
        \Msg::success($category->name . ' has been <strong>restored</strong>');
        return redir('account/categories');
    }


}
