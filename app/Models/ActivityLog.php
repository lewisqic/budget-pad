<?php

namespace App\Models;

class ActivityLog extends \Spatie\Activitylog\Models\Activity
{

    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * ATTRIBUTE ACCESSORS
     ******************************************************************/


    /******************************************************************
     * ATTRIBUTE MUTATORS
     ******************************************************************/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /******************************************************************
     * CUSTOM ORM METHODS
     ******************************************************************/

    /**
     * List all available logs for a given resource type
     *
     * @return collection
     */
    public static function getBySubject($subject)
    {
        $parts = explode('|', $subject);
        $subject = $parts[0];
        $type = $parts[1] ?? '';
        $activity_logs = ActivityLog::with('subject', 'causer')->where('subject_type', $subject)->get();
        $logs = [];
        foreach ( $activity_logs as $log ) {
            if ( empty($log->subject) ) {
                continue;
            }
            if (
                ($subject == 'App\Models\User' && !empty($type) && $type != $log->subject->type) ||
                ($subject == 'App\Models\Role' && !empty($type) && $type != $log->subject->guard_name)
            )
            {
                continue;
            }
            $logs[] = $log;
        }
        return collect($logs);
    }

}
