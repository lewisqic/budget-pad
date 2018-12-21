<?php

namespace App\Services;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogService extends BaseService
{

    /**
     * return array of user data for datatables
     * @return array
     */
    public function dataTables($data)
    {
        // require resource type
        if ( empty($data['resource_type']) ) {
            return [];
        }
        $logs = ActivityLog::getBySubject($data['resource_type']);
        $logs_arr = [];
        foreach ( $logs as $log ) {
            $logs_arr[] = [
                'id' => $log->id,
                'class' => !is_null($log->deleted_at) ? 'text-danger' : null,
                'affected_resource' => $this->getSubjectName($log),
                'description' => $this->formatDescription($log),
                'changes_made' => $this->buildChangesString($log),
                'caused_by' => !empty($log->causer) ? $log->causer->name : '<em>System</em>',
                'created_at' => [
                    'display' => $log->created_at->format('M j, Y h:i A'),
                    'sort' => $log->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'delete' => ['url' => 'admin/activity/' . $log->id, 'confirm' => true],
                ])
            ];
        }
        return $logs_arr;
    }

    /**
     * Determine the name to return for each resource type
     *
     * @param $log
     *
     * @return string
     */
    public function getSubjectName($log)
    {
        $name = '';
        $subject = $log->subject;
        $company_empty = '<em>(company name empty)</em>';
        switch ( $log->subject_type ) {
            case 'App\Models\Company':
                $name = $subject->name ?: $company_empty;
                break;
            case 'App\Models\CompanySubscription':
                $name = $subject->company->name ?: $company_empty;
                break;
            case 'App\Models\CompanyPaymentMethod':
                $company = $subject->company->name ?: $company_empty;
                $name = $company . ', ' . $subject->cc_type . ' ' . $subject->cc_last4;
                break;
            case 'App\Models\CompanyPayment':
                $company = $subject->company->name ?: $company_empty;
                $name = $company . ', #' . $subject->transaction_id;
                break;
            case 'App\Models\User':
            case 'App\Models\Role':
                $name = $subject->name;
                break;
            case 'App\Models\AdminSetting':
                $name = $subject->label;
                break;
        }
        return $name;
    }

    /**
     * List all available resource types
     *
     * @return array
     */
    public function listResourceTypes()
    {
        return [
            'Member' => 'App\Models\User|' . User::MEMBER_ID,
            'Company' => 'App\Models\Company',
            'Subscription' => 'App\Models\CompanySubscription',
            'Payment Method' => 'App\Models\CompanyPaymentMethod',
            'Payment History' => 'App\Models\CompanyPayment',
            'Administrator' => 'App\Models\User|' . User::ADMINISTRATOR_ID,
            'Administrator Role' => 'App\Models\Role|' . User::$types[User::ADMINISTRATOR_ID]['route'],
            'Admin Setting' => 'App\Models\AdminSetting',
        ];
    }

    /**
     * Build the changes string
     *
     * @param $log
     *
     * @return string
     */
    public function buildChangesString($log)
    {
        // TODO: check for relationship changes, such as user assigned roles?
        $changes = '';
        if ( $log->description == 'updated' ) {
            $changes_arr = [];
            if ( !empty($log->properties['attributes']) ) {
                foreach ( $log->properties['attributes'] as $column => $new_value ) {
                    $old_value = $log->properties['old'][$column];
                    [$old_value, $new_value] = $this->prepareValues($old_value, $new_value);
                    // check for password change
                    if ( $column == 'password' ) {
                        $changes_arr[] = '<code>' . $column . '</code> changed <em class="text-muted">(values hidden)</em>';
                    } elseif ( strlen($old_value . $new_value) > 85 ) {
                        $changes_arr[] = '<code>' . $column . '</code> changed <em class="text-muted">(<a href="#" class="toggle-values">show values <i class="fal fa-angle-down"></i></a>)</em><div class="long-values hide"><strong>Old Value:</strong> ' . $old_value . '<br><strong>New Value:</strong> ' . $new_value . '</div>';
                    } else {
                        $changes_arr[] = '<code>' . $column . '</code> changed from <strong>' . $old_value . '</strong> to <strong>' . $new_value . '</strong>';
                    }
                }
            }
            $changes = implode('<br>', $changes_arr);
        } else {
            $changes = '<em class="text-muted">resource ' . $log->description . '</em>';
        }
        return $changes;
    }

    public function prepareValues($old_value, $new_value)
    {
        $empty_html = '<em class="text-danger font-weight-normal">(Empty)</em>';
        if ( $old_value <= 1 && $new_value <= 1 ) {
            $old_value = $old_value === 1 ? '<span class="text-success">true</span>' : ($old_value === 0 ? '<span class="text-danger">false</span>' : $old_value);
            $new_value = $new_value === 1 ? '<span class="text-success">true</span>' : ($new_value === 0 ? '<span class="text-danger">false</span>' : $new_value);
        }
        // handle json encoded values
        $old_value = is_json($old_value) ? (!empty(json_decode($old_value)) ? implode(', ', json_decode($old_value)) : $empty_html) : $old_value;
        $new_value = is_json($new_value) ? (!empty(json_decode($new_value)) ? implode(', ', json_decode($new_value)) : $empty_html) : $new_value;
        // check for dates
        $old_value = preg_match('/^\d{4}-/', $old_value) ? date('M j, Y ' . (preg_match('/00:00:00/', $old_value) ? '' : 'g:i A'), strtotime($old_value)) : $old_value;
        $new_value = preg_match('/^\d{4}-/', $new_value) ? date('M j, Y ' . (preg_match('/00:00:00/', $new_value) ? '' : 'g:i A'), strtotime($new_value)) : $new_value;
        // check for empty values
        $old_value = empty($old_value) ? $empty_html : $old_value;
        $new_value = empty($new_value) ? $empty_html : $new_value;
        return [
            $old_value,
            $new_value
        ];
    }

    /**
     * Color the description string accordingly
     *
     * @param $log
     *
     * @return string
     */
    public function formatDescription($log)
    {
        $description = '';
        switch ( $log->description ) {
            case 'created':
                $description = '<span class="text-success">Created</span>';
                break;
            case 'updated':
                $description = '<span class="text-primary">Updated</span>';
                break;
            case 'deleted':
                $description = '<span class="text-danger">Deleted</span>';
                break;
            case 'restored':
                $description = '<span class="text-info">Restored</span>';
                break;
            default:
                $description = ucfirst($log->description);
        }
        return $description;
    }

    /**
     * Log changes made via relationships
     *
     * @param $old_values
     * @param $new_values
     */
    public function logRelationshipChange($attribute, $subject, $old_value, $new_value)
    {
        if ( $old_value != $new_value ) {
            $properties = [
                'attributes' => [$attribute => $new_value],
                'old'        => [$attribute => $old_value]
            ];
            activity()->performedOn($subject)->withProperties($properties)->log('updated');
        }
    }

}