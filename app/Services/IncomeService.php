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
        $incomes = Income::getByCompany($company_id, $trashed);
        $incomes->load('category', 'tags');
        $income_arr = [];
        foreach ( $incomes as $income ) {
            $income_arr[] = [
                'id' => $income->id,
                'class' => !is_null($income->deleted_at) ? 'text-danger' : null,
                'amount' => \Format::currency($income->amount),
                'category' => $income->category->name,
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

}