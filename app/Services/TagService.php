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

}