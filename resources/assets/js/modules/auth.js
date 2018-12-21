/******************************************************
 * Auth class used on our auth route form actions
 ******************************************************/
class Auth {

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
        // check for notification message
        this.checkNotifications();
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;
        // handle form success action
        $(window).on('auth_form.success', function(e, obj) {
            self.handleSuccess(obj);
        });
        // handle form beforeSubmit action
        $(window).on('auth_form.beforeSubmit', function(e, obj) {
            self.handleBeforeSubmit();
        });
        // handle form error action
        $(window).on('auth_form.error', function(e, obj) {
            self.handleError(obj);
        });
    }

    /**
     * handle the ajax form submission error
     */
    handleError(obj) {
        obj.halt = true;
        obj.button.button('reset');
        $('.danger-wrapper .message').html(obj.message);
        $('.danger-wrapper').fadeIn('fast');
    }

    /**
     * handle the ajax form submission success
     */
    handleSuccess(obj) {
        obj.halt = true;
        obj.button.button('success');
        window.location = Core.url(obj.data.route ? obj.data.route : '');
    }

    /**
     * handle the ajax form beforeSubmit event
     */
    handleBeforeSubmit(obj) {
        $('.danger-wrapper').fadeOut('fast');
    }

    /**
     * Show any notifications we might have
     */
    checkNotifications() {
        if ( notification !== null ) {
            $('.' + notification.status + '-wrapper .message').html(notification.message);
            $('.' + notification.status + '-wrapper').show();
        }
    }

}


/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    window.Auth = new Auth();
});