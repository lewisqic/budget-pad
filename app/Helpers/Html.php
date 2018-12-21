<?php

namespace App\Helpers;

class Html {


	/**
	 * return hidden input fields for a form
	 * @param  string  $method
	 * @param  boolean $ajax
	 * @return html
	 */
	public static function hiddenInput($options = [])
	{
	    //$method = null, $ajax = false

		$output = '<input type="hidden" name="_token" value="' . csrf_token() . '">';
		if ( isset($options['method']) ) {
			$output .= '<input type="hidden" name="_method" value="' . $options['method'] . '">';
		}
		if ( (isset($options['ajax']) && $options['ajax'] == true) || (\Request::ajax() && \Request::input('_ajax') != 'false') ) {
			$output .= '<input type="hidden" name="_ajax" value="true">';
		}
		if ( \Request::has('_redir') ) {
			$output .= '<input type="hidden" name="_redir" value="' . \Request::input('_redir') . '">';
		}
        if ( \Request::has('_profile') && app('app_user') && decrypt(\Request::input('_profile')) == app('app_user')->id ) {
            $output .= '<input type="hidden" name="_profile" value="' . \Request::input('_profile') . '">';
        }
		return $output;
	}

	/**
	 * return html for our datatables action column
	 * @return html 
	 */
	public static function dataTablesActionColumn($click_only = false) {
		return '<th data-class="action_column hidden-xs ' . ($click_only ? 'hidden-xs-up' : '') . '" data-name="action" data-order="false" data-search="false"></th>';
	}

	/**
	 * return html for datatables action column
	 * @param  array $buttons_arr
	 * @return html
	 */
	public static function dataTablesActionButtons($buttons_arr) {
		$output = '';
		$buttons = '';
		foreach ( $buttons_arr as $button => $url ) {
			if ( is_null($url) ) continue;
			switch ( $button ) {
				case 'edit':
                    if ( has_permission(route_name($url, 'get')) ) {
                        $buttons .= '<a href="' . url($url) . '" class="btn btn-sm btn-outline-light invisible ' . (in_array('disable_sidebar', $buttons_arr) ? '' : 'open-sidebar') . '"><i class="fal fa-lg fa-edit text-primary"></i></a>';
                    }
				break;
			}
		}
		if ( !empty($buttons_arr['delete']) || !empty($buttons_arr['restore']) ) {
            $confirm_delete = false;
		    if ( isset($buttons_arr['delete']) && is_array($buttons_arr['delete']) ) {
                $buttons_arr['delete'] = $buttons_arr['delete']['url'] ?? '';
                $confirm_delete = $buttons_arr['delete']['confirm'] ?? true;
            }
			$action = !empty($buttons_arr['restore']) ? $buttons_arr['restore'] : $buttons_arr['delete'];
            $action_parts = explode('/', $action);
			$method  = !empty($buttons_arr['restore']) ? 'patch' : 'delete';
            $form_id  = !empty($buttons_arr['restore']) ? 'restore_form' : 'delete_form';

            if ( has_permission(route_name($action, $method)) ) {
                $output .= '<form action="' . url($action) . '" method="post" class="validate" id="' . $form_id . '-' . $action_parts[count($action_parts) - 1] . '">' . \Html::hiddenInput(['method' => $method]) . '
			                <div class="btn-group">' . (!empty($buttons_arr['restore']) ?
                                '<button class="btn btn-sm btn-outline-light invisible"><i class="fal fa-lg fa-undo text-success"></i></button>' :
                                $buttons . '<button class="btn btn-sm btn-outline-light invisible' . ($confirm_delete ? ' confirm-click' : '') . '"><i class="fal fa-lg fa-trash-alt text-danger"></i></button>'
                        ) . '</div>
                        </form>';
            } else {
                $output .= '<div class="btn-group">' . $buttons . '</div>';
            }
		} else {
			$output .= '<div class="btn-group">' . $buttons . '</div>';
		}
		if ( !empty($buttons_arr['click']) && empty($buttons_arr['restore']) && has_permission(route_name($buttons_arr['click'], 'get')) ) {
			$output .= '<input type="hidden" class="click-location" value="' . url($buttons_arr['click']) . '">';
		}
		return $output;
	}

	/**
	 * output our undo delete icon and link
	 * @param  string $route 
	 * @return html
	 */
	public static function undoLink($route)
	{
		$output = '<br><form action="' . url($route) . '" method="post" class="validate d-inline" id="restore_form">
                    ' . \Html::hiddenInput(['method' => 'patch']) . '
                        <button type="submit" class="alert-link"><i class="fal fa-undo"></i> Undo</button>
                    </form>';
		return $output;
	}

    /**
     * return html code to display status history data in a popover
     * @param  string $anchor_text
     * @param  string $popover_title
     * @param  collection $status_history
     * @return html
     */
    public static function statusHistoryPopover($model, $popover_title = 'Status History', $anchor_text = 'view status history')
    {
        $output = '';
        $status_history = $model->statusHistory()->orderBy('created_at', 'asc')->get();
        if ( $status_history->count() > 0 ) {
            $history = '<div class="status-history-popover">';
            foreach ( $status_history as $row ) {
                $history .= '<div class="row">
								<div class="col-sm-4 alignright">' . $row->status . '</div>
								<div class="col-sm-8">' . $row->created_at->format('m/d/y h:i A') . '</div>
							</div>';
            }
            $history .= '</div>';
            $output = '<a tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-title="' . $popover_title . '" data-content=\'' . $history . '\'>' . $anchor_text . '</a>';
        }
        return $output;
    }

    /**
     * return a font icon for the payment type
     * @param  string $cc_type
     * @return html
     */
    public static function ccIcon($cc_type)
    {
        $class = $cc_type == 'American Express' ? 'amex' : strtolower($cc_type);
        return '<i class="fab fa-cc-' . strtolower($class) . '"></i>';
    }

    /**
     * return html for our page heading icon
     * @param  string $icon
     * @return string
     */
    public static function pageIcon($icon)
    {
        return '<i class="' . $icon . ' fa-fw" data-toggle="tooltip" title="Add page to favorites"></i>';
    }

    /**
     * return true or false
     * @param  string $icon
     * @return string
     */
    public static function yesOrNo($value, $color = false)
    {
        if ( $value !== 1 && $value !== 0 ) {
            return $value;
        }
        return '<span class="' . ($color ? ($value ? 'text-success' : 'text-danger') : '') . '">' . ($value ? 'Yes' : 'No') . '</span>';
    }


}