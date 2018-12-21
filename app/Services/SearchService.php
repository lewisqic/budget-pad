<?php

namespace App\Services;

use Illuminate\Http\Request;

class SearchService extends BaseService
{

    /**
     * Build search results based on provided keyword
     *
     * @param $keywords
     *
     * @return array
     */
    public function getResults($keywords)
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(app_path('Models')));
        $results = [];
        foreach ( $rii as $file ) {
            if ( $file->isDir() ) {
                continue;
            }
            $class = preg_replace('/\.\w+/', '', preg_replace('/.*app\/Models\//i', '\App\Models\\', $file->getPathname()));
            $model = resolve($class);
            if ( method_exists($model, 'globalSearchResults') ) {
                $model_results = $model->globalSearchResults($keywords);
                $results = array_merge($results, isset($model_results[0]) && is_array($model_results[0]) ? $model_results : [$model_results]);
            }
        }
        usort($results, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        return $results;
    }


}