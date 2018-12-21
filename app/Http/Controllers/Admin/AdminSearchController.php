<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use Facades\App\Services\SearchService;
use App\Http\Controllers\Controller;

class AdminSearchController extends Controller
{

    /**
     * Show our search result page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResults()
    {
        $keywords = explode(' ', \Request::input('keywords'));
        $results = !empty(array_filter($keywords)) ? SearchService::getResults($keywords) : [];
        $data = [
            'results' => $results  
        ];
        return view('content.admin.search.results', $data);
    }


}