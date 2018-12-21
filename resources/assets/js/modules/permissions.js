/******************************************************
 * Permissions class used on our permissions stuff
 ******************************************************/
class Permissions {

    /**
     * Class constructor, called when instantiating new class object
     */
    constructor() {
        // declare our class properties
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */
    init() {
        // bind events
        this.bindEvents();
        // process checkboxes on load
        this.onLoad();
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;

        $('input.permission-group').on('change', function(e) {
            let checked = $(this).prop('checked');
            $(this).closest('.permission-group-wrapper').find('.permission-function').prop('checked', checked);
        });

        $('input.permission-function').on('change', function() {
            let $group = $(this).closest('.permission-group-wrapper').find('input.permission-group');
            let yes = false;
            let no = false;
            $(this).closest('.functions').find('.permission-function').each(function() {
                if ( $(this).prop('checked') ) {
                    yes = true;
                } else {
                    no = true;
                }
            });
            if ( yes && no ) {
                $group.prop('readOnly', true).prop('indeterminate', true);
            } else if ( yes && !no ) {
                $group.prop('readOnly', false).prop('indeterminate', false).prop('checked', true);
            } else if ( !yes && no ) {
                $group.prop('readOnly', false).prop('indeterminate', false).prop('checked', false);
            }
        });

        $('input[name="name"]').on('blur', function() {
            let value = $(this).val();
            if ( $('input[name="slug"]').val() === '' ) {
                $('input[name="slug"]').val(value.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,''));
            }
        });

    }


    /**
     * process checkboxes on load
     */
    onLoad() {
        $('.permission-group-wrapper').each(function() {
            $(this).find('.permission-function:first').trigger('change');
        });
    }


}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new Permissions();
});