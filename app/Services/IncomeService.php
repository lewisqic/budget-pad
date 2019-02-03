<?php

namespace App\Services;

use App\Models\Income;

class IncomeService extends BaseService
{

    /**
     * return array of incomes
     * @return array
     */
    public function dataTables($company_id, $data)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $incomes = Income::where('company_id', $company_id)->when($trashed, function ($query) {
            return $query->withTrashed();
        })->when(!empty($data['start_date']) && !empty($data['end_date']), function ($query) use ($data) {
            return $query->whereBetween('date_at', [\Carbon::parse($data['start_date'])->startOfDay(), \Carbon::parse($data['end_date'])->endOfDay()]);
        })->when($data['category_id'], function($query) use($data) {
            return $query->where('category_id', $data['category_id']);
        })->get();
        $incomes->load('category', 'tags');
        
        $income_arr = [];
        foreach ( $incomes as $income ) {
            $income_arr[] = [
                'id' => $income->id,
                'class' => !is_null($income->deleted_at) ? 'text-danger' : null,
                'amount' => \Format::currency($income->amount),
                'category' => is_null($income->category->deleted_at) ? '<a href="' . url('account/incomes/category/' . $income->category_id) . '">' . $income->category->name . '</a>' : '<em class="text-muted">category deleted</em>',
                'tags' => $income->tags->implode('name', ', '),
                'notes' => $income->notes,
                'date_at' => [
                    'display' => $income->date_at->format('M j, Y'),
                    'sort' => $income->date_at->timestamp
                ],
                'created_at' => [
                    'display' => $income->created_at->format('M j, Y h:i A'),
                    'sort' => $income->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('account/incomes/' . $income->id . '/edit'),
                    'delete' => url('account/incomes/' . $income->id),
                    'restore' => !is_null($income->deleted_at) ? url('account/incomes/' . $income->id) : null,
                ])
            ];
        }
        return $income_arr;
    }

    /**
     * Return chart data for given date
     * @param $date
     */
    public function getChartData($dates, $category_id = null)
    {
        $start_date = \Carbon::parse($dates[0]);
        $end_date = \Carbon::parse($dates[1]);
        
        $income = Income::where('company_id', app('company')->id)->whereBetween('date_at', [$start_date->startOfDay(), $end_date->endOfDay()])->when($category_id !== null, function($query) use($category_id) {
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
        foreach ( $income as $row ) {
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

        $incomes = Income::where('company_id', app('company')->id)->whereBetween('date_at', [$start_date->startOfDay(), $end_date->endOfDay()])->when($category_id !== null, function($query) use($category_id) {
            return $query->where('category_id', $category_id);
        })->orderBy('date_at', 'ASC')->get();

        $incomes_sum = $incomes->sum('amount');

        return [
            'incomes' => $incomes_sum,
            'transactions' => $incomes->count(),
            'avg' => number_format($incomes_sum / $max, 2),
        ];

    }

}