<?php

namespace App\Services;

use App\Models\Category;

class CategoryService extends BaseService
{

    /**
     * return array of categories
     * @return array
     */
    public function dataTables($company_id, $data)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $categories = Category::getByCompany($company_id, $trashed);
        $categories_arr = [];
        foreach ( $categories as $category ) {
            $categories_arr[] = [
                'id' => $category->id,
                'class' => !is_null($category->deleted_at) ? 'text-danger' : null,
                'category_type' => ucwords($category->category_type),
                'amount_type' => ucwords($category->amount_type),
                'name' => '<a href="' . url('account/' . $category->category_type . 's/category/' . $category->id) . '">' . $category->name . '</a>',
                'budget' => \Format::currency($category->budget),
                'created_at' => [
                    'display' => $category->created_at->format('M j, Y h:i A'),
                    'sort' => $category->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('account/categories/' . $category->id . '/edit'),
                    'delete' => url('account/categories/' . $category->id),
                    'restore' => !is_null($category->deleted_at) ? url('account/categories/' . $category->id) : null,
                ])
            ];
        }
        return $categories_arr;
    }

    public function getDashboardStats($dates)
    {
        $start_date = \Carbon::parse($dates[0]);
        $end_date = \Carbon::parse($dates[1]);

        $categories = Category::with('incomes', 'expenses')->where('company_id', app('company')->id)->orderBy('name', 'ASC')->get();


        $cats = [
            'fixed' => [],
            'discretionary' => [],
            'income' => [],
        ];
        foreach ( $categories as $category ) {

            if ( $category->category_type == 'expense' ) {
                $cats[$category->amount_type][] = $category;
            } else {
                $cats[$category->category_type][] = $category;
            }
        }

        $stats = [
            'fixed' => [],
            'discretionary' => [],
            'income' => [],
        ];
        foreach ( $cats as $type => $categories ) {

            foreach ( $categories as $category ) {

                $incomes = 0;
                $expenses = 0;
                foreach ( $category->incomes as $income ) {
                    if ( $income->date_at >= $start_date->startOfDay()->format('Y-m-d H:i:s') && $income->date_at <= $end_date->endOfDay()->format('Y-m-d H:i:s') ) {
                        $incomes += $income->amount;
                    }
                }
                foreach ( $category->expenses as $expense ) {
                    if ( $expense->date_at >= $start_date->startOfDay()->format('Y-m-d H:i:s') && $expense->date_at <= $end_date->endOfDay()->format('Y-m-d H:i:s') ) {
                        $expenses += $expense->amount;
                    }
                }

                $stats[$type][] = [
                    'category' => $category,
                    'expenses' => $expenses,
                    'incomes' => $incomes,
                    'budgeted' => $category->budget,
                    'percent' => $category->budget > 0 ? number_format($expenses / $category->budget, 2) * 100 : 0
                ];
            }

        }

        $totals = [
            'expense' => [],
            'fixed' => ['expenses' => 0, 'budgeted' => 0],
            'discretionary' => ['expenses' => 0, 'budgeted' => 0],
            'income' => ['incomes' => 0],
        ];
        foreach ( $stats as $type => $categories ) {
            foreach ( $categories as $category_data ) {
                foreach ( $category_data as $key => $value ) {
                    if ( $key != 'category' ) {
                        $totals[$type][$key] = isset($totals[$type][$key]) ? $totals[$type][$key] + $value : $value;
                    }
                }
            }
            $totals[$type]['percent'] = count($categories) > 0 ? round($totals[$type]['percent'] / count($categories)) : 0;
        }
        $totals['expense'] = [
            'spent' => $totals['fixed']['expenses'] + $totals['discretionary']['expenses'],
            'budgeted' => $totals['fixed']['budgeted'] + $totals['discretionary']['budgeted'],
        ];

        $data = [
            'data' => $stats,
            'totals' => $totals
        ];

        return $data;

    }

}