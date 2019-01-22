<?php

namespace App\Services;

use App\Models\Expense;

class ExpenseService extends BaseService
{

    /**
     * return array of expenses
     * @return array
     */
    public function dataTables($company_id, $data)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $expense = Expense::getByCompany($company_id, $trashed);
        $expense->load('category', 'tags');
        $expense_arr = [];
        foreach ( $expense as $expense ) {
            $expense_arr[] = [
                'id' => $expense->id,
                'class' => !is_null($expense->deleted_at) ? 'text-danger' : null,
                'amount' => \Format::currency($expense->amount),
                'category' => $expense->category->name,
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

}