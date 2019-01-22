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
                'name' => $category->name,
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

}