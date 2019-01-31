<?php

namespace App\Services;

use App\Models\Tag;

class TagService extends BaseService
{

    /**
     * return array of tags
     * @return array
     */
    public function dataTables($company_id, $data)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $tags = Tag::getByCompany($company_id, $trashed);
        $tags_arr = [];
        foreach ( $tags as $tag ) {
            $tags_arr[] = [
                'id' => $tag->id,
                'class' => !is_null($tag->deleted_at) ? 'text-danger' : null,
                'name' => $tag->name,
                'created_at' => [
                    'display' => $tag->created_at->format('M j, Y h:i A'),
                    'sort' => $tag->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'edit' => url('account/tags/' . $tag->id . '/edit'),
                    'delete' => url('account/tags/' . $tag->id),
                    'restore' => !is_null($tag->deleted_at) ? url('account/tags/' . $tag->id) : null,
                ])
            ];
        }
        return $tags_arr;
    }

    public function getDashboardStats($dates)
    {
        $start_date = \Carbon::parse($dates[0]);
        $end_date = \Carbon::parse($dates[1]);

        $tags = Tag::with('incomes', 'expenses')->where('company_id', app('company')->id)->orderBy('name', 'ASC')->get();

        $stats = [];
        foreach ( $tags as $tag ) {
            if ( $tag->incomes->isEmpty() && $tag->expenses->isEmpty() ) {
                continue;
            }
            $incomes = 0;
            $expenses = 0;
            foreach ( $tag->incomes as $income ) {
                if ( $income->date_at >= $start_date->startOfDay()->format('Y-m-d H:i:s') && $income->date_at <= $end_date->endOfDay()->format('Y-m-d H:i:s') ) {
                    $incomes += $income->amount;
                }
            }
            foreach ( $tag->expenses as $expense ) {
                if ( $expense->date_at >= $start_date->startOfDay()->format('Y-m-d H:i:s') && $expense->date_at <= $end_date->endOfDay()->format('Y-m-d H:i:s') ) {
                    $expenses += $expense->amount;
                }
            }
            $stats[] = [
                'id' => $tag->id,
                'tag' => $tag->name,
                'incomes' => $incomes,
                'expenses' => $expenses,
            ];
        }
        
        return $stats;
        
    }

}