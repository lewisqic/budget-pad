<?php

namespace App\Http\Controllers\Account;

use App\Models\Expense;
use App\Models\Tag;
use App\Models\Category;
use Facades\App\Services\ExpenseService;
use App\Http\Controllers\Controller;


class AccountExpenseController extends Controller
{

    /**
     * Show the expense list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.account.expenses.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = ExpenseService::dataTables(app('company')->id, \Request::all());
        return response()->json($data);
    }

    /**
     * Show the expense create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title'      => 'Add',
            'tags'       => Tag::getByCompany(app('company')->id),
            'categories' => Category::getByCompany(app('company')->id)->where('category_type', 'expense'),
        ];
        return view('content.account.expenses.create-edit', $data);
    }

    /**
     * Show the expense create page
     *
     * @return view
     */
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $data = [
            'title'      => 'Edit',
            'expense'     => $expense,
            'tags'       => Tag::getByCompany(app('company')->id),
            'categories' => Category::getByCompany(app('company')->id)->where('category_type', 'expense'),
        ];
        return view('content.account.expenses.create-edit', $data);
    }

    /**
     * Create new expense record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $expense = ExpenseService::create($data);
        if ( isset($data['tags']) ) {
            $expense->tags()->sync($data['tags']);
        }
        \Msg::success(($expense->notes ?: 'Expense') . ' has been <strong>added</strong>');
        return redir('account/expenses');
    }

    /**
     * Create new expense record
     *
     * @return redirect
     */
    public function update()
    {
        $data = \Request::all();
        $expense = ExpenseService::load(\Request::input('id'))->update($data);
        $expense->tags()->sync($data['tags'] ?? []);
        \Msg::success(($expense->notes ?: 'Expense') . ' has been <strong>updated</strong>');
        return redir('account/expenses');
    }

    /**
     * Delete an expense record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $expense = ExpenseService::delete($id);
        \Msg::success(($expense->notes ?: 'Expense') . ' has been <strong>deleted</strong> ' . \Html::undoLink('account/expenses/' . $expense->id));
        return redir('account/expenses');
    }

    /**
     * Restore an expense record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $expense = ExpenseService::restore($id);
        \Msg::success(($expense->notes ?: 'Expense') . ' has been <strong>restored</strong>');
        return redir('account/expenses');
    }


}
