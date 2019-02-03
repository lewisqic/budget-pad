<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;

class ExpenseService extends BaseService
{

    /**
     * return array of expenses
     * @return array
     */
    public function dataTables($company_id, $data)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $expenses = Expense::where('company_id', $company_id)->when($trashed, function ($query) {
            return $query->withTrashed();
        })->when(!empty($data['start_date']) && !empty($data['end_date']), function ($query) use ($data) {
            return $query->whereBetween('date_at', [\Carbon::parse($data['start_date'])->startOfDay(), \Carbon::parse($data['end_date'])->endOfDay()]);
        })->when($data['category_id'], function($query) use($data) {
            return $query->where('category_id', $data['category_id']);
        })->get();
        $expenses->load('category', 'tags');

        $expense_arr = [];
        foreach ( $expenses as $expense ) {
            $expense_arr[] = [
                'id' => $expense->id,
                'class' => !is_null($expense->deleted_at) ? 'text-danger' : null,
                'amount' => \Format::currency($expense->amount),
                'category' => is_null($expense->category->deleted_at) ? '<a href="' . url('account/expenses/category/' . $expense->category_id) . '">' . $expense->category->name . '</a>' : '<em class="text-muted">category deleted</em>',
                'tags' => $expense->tags->implode('name', ', '),
                'notes' => $expense->notes,
                'date_at' => [
                    'display' => $expense->date_at->format('M j, Y'),
                    'sort' => $expense->date_at->timestamp
                ],
                'created_at' => [
                    'display' => $expense->created_at->format('M j, Y h:i A'),
                    'sort' => $expense->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('account/expenses/' . $expense->id . '/edit'),
                    'delete' => url('account/expenses/' . $expense->id),
                    'restore' => !is_null($expense->deleted_at) ? url('account/expenses/' . $expense->id) : null,
                ])
            ];
        }
        return $expense_arr;
    }

    /**
     * Return chart data for given date
     * @param $date
     */
    public function getChartData($dates, $category_id = null)
    {
        $start_date = \Carbon::parse($dates[0]);
        $end_date = \Carbon::parse($dates[1]);

        $expenses = Expense::where('company_id', app('company')->id)->whereBetween('date_at', [$start_date->startOfDay(), $end_date->endOfDay()])->when($category_id !== null, function($query) use($category_id) {
            return $query->where('category_id', $category_id);
        })->orderBy('date_at', 'ASC')->get();

        $max = $start_date->diffInDays($end_date) + 1;
        $days = [];
        $start = $start_date->copy()->subDay();
        for ( $i = 0; $i < $max; $i++ ) {
            $day = $start->addDays(1);
            $days[$day->format('Y-m-d')] = [
                'label' => $day->format('M j'),
                'day' => 0,
                'avg' => 0,
            ];
        }

        $total = 0;
        foreach ( $expenses as $row ) {
            $total += $row->amount;
            $days[$row->date_at->format('Y-m-d')]['day'] += $row->amount;
        }

        $avg = number_format($total / $max, 2);
        foreach ( $days as &$day ) {
            $day['avg'] = $avg;
        }

        return $days;
    }

    public function getTileData($dates, $category_id = null)
    {

        $start_date = \Carbon::parse($dates[0]);
        $end_date = \Carbon::parse($dates[1]);
        $max = $start_date->diffInDays($end_date) + 1;

        $expenses = Expense::where('company_id', app('company')->id)->whereBetween('date_at', [$start_date->startOfDay(), $end_date->endOfDay()])->when($category_id !== null, function($query) use($category_id) {
            return $query->where('category_id', $category_id);
        })->orderBy('date_at', 'ASC')->get();

        $expenses_sum = $expenses->sum('amount');

        return [
            'expenses' => $expenses_sum,
            'transactions' => $expenses->count(),
            'avg' => number_format($expenses_sum / $max, 2),
        ];

    }

    public function getDashboardTiles($dates)
    {

        $start_date = \Carbon::parse($dates[0]);
        $end_date = \Carbon::parse($dates[1]);
        $max = $start_date->diffInDays($end_date) + 1;

        $incomes = Income::where('company_id', app('company')->id)->whereBetween('date_at', [$start_date->startOfDay(), $end_date->endOfDay()])->orderBy('date_at', 'ASC')->get();

        $expenses = Expense::where('company_id', app('company')->id)->whereBetween('date_at', [$start_date->startOfDay(), $end_date->endOfDay()])->orderBy('date_at', 'ASC')->get();

        $incomes_sum = $incomes->sum('amount');
        $expenses_sum = $expenses->sum('amount');

        return [
            'incomes' => $incomes_sum,
            'expenses' => $expenses_sum,
            'net' => $incomes_sum - $expenses_sum,
            'savings' => $incomes_sum > 0 ? number_format(($incomes_sum - $expenses_sum) / $incomes_sum, 2) * 100 : 0,
            'transactions' => $expenses->count(),
            'avg' => number_format($expenses_sum / $max, 2),
        ];

    }

}