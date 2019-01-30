<?php

namespace App\Http\Controllers\Account;

use App\Models\Income;
use App\Models\Tag;
use App\Models\Category;
use Facades\App\Services\IncomeService;
use App\Http\Controllers\Controller;


class AccountIncomeController extends Controller
{

    /**
     * Show the income list page
     *
     * @return view
     */
    public function index($category_id = null)
    {
        if ( $category_id ) {
            $category = Category::findOrFail($category_id);
        }
        $initial_dates = [\Carbon::now()->startOfMonth()->format('Y-m-d'), \Carbon::now()->endOfMonth()->format('Y-m-d')];
        $data = [
            'initial_dates' => $initial_dates,
            'chart_data' => IncomeService::getChartData($initial_dates, $category_id),
            'tile_data' => IncomeService::getTileData($initial_dates, $category_id),
            'category' => $category ?? null,
        ];
        return view('content.account.incomes.index', $data);
    }
    
    public function overviewData()
    {
        if ( \Request::input('category_id') ) {
            $category = Category::findOrFail(\Request::input('category_id'));
        }
        $dates = [\Request::input('start_date'), \Request::input('end_date')];

        $chart_data = IncomeService::getChartData($dates, $category->id ?? null);
        $tile_data = IncomeService::getTileData($dates, $category->id ?? null);
        $data = [
            'chart' => $chart_data,
            'tiles' => $tile_data,
        ];
        return response()->json($data);
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = IncomeService::dataTables(app('company')->id, \Request::all());
        return response()->json($data);
    }

    /**
     * Show the income create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title'      => 'Add',
            'tags'       => Tag::getByCompany(app('company')->id),
            'categories' => Category::getByCompany(app('company')->id)->where('category_type', 'income'),
        ];
        return view('content.account.incomes.create-edit', $data);
    }

    /**
     * Show the income create page
     *
     * @return view
     */
    public function edit($id)
    {
        $income = Income::findOrFail($id);
        $data = [
            'title'      => 'Edit',
            'income'     => $income,
            'tags'       => Tag::getByCompany(app('company')->id),
            'categories' => Category::getByCompany(app('company')->id)->where('category_type', 'income'),
        ];
        return view('content.account.incomes.create-edit', $data);
    }

    /**
     * Create new income record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $income = IncomeService::create($data);
        if ( isset($data['tags']) ) {
            $income->tags()->sync($data['tags']);
        }
        \Msg::success(($income->notes ?: 'Income') . ' has been <strong>added</strong>');
        return redir('account/incomes');
    }

    /**
     * Create new income record
     *
     * @return redirect
     */
    public function update()
    {
        $data = \Request::all();
        $income = IncomeService::load(\Request::input('id'))->update($data);
        $income->tags()->sync($data['tags'] ?? []);
        \Msg::success(($income->notes ?: 'Income') . ' has been <strong>updated</strong>');
        return redir('account/incomes');
    }

    /**
     * Delete an income record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $income = IncomeService::delete($id);
        \Msg::success(($income->notes ?: 'Income') . ' has been <strong>deleted</strong> ' . \Html::undoLink('account/incomes/' . $income->id));
        return redir('account/incomes');
    }

    /**
     * Restore an income record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $income = IncomeService::restore($id);
        \Msg::success(($income->notes ?: 'Income') . ' has been <strong>restored</strong>');
        return redir('account/incomes');
    }


}
