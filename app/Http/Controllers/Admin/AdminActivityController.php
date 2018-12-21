<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use Facades\App\Services\ActivityLogService;
use App\Http\Controllers\Controller;


class AdminActivityController extends Controller
{

    /**
     * Show the activity list page
     *
     * @return view
     */
    public function index()
    {
        $data = [
            'resource_types' => ActivityLogService::listResourceTypes()
        ];
        return view('content.admin.activity.index', $data);
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = ActivityLogService::dataTables(\Request::all());
        return response()->json($data);
    }

    /**
     * Delete an activity record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $activity = ActivityLogService::delete($id);
        \Msg::success('Activity log has been <strong>deleted</strong>.');
        return redir('admin/activity');
    }


}