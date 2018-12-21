var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Auth class used on our auth route form actions
 ******************************************************/
var Auth = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function Auth() {
        _classCallCheck(this, Auth);

        // declare our class properties
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(Auth, [{
        key: 'init',
        value: function init() {
            // bind events
            this.bindEvents();
            // check for notification message
            this.checkNotifications();
        }

        /**
         * bind all necessary events
         */

    }, {
        key: 'bindEvents',
        value: function bindEvents() {
            var self = this;
            // handle form success action
            $(window).on('auth_form.success', function (e, obj) {
                self.handleSuccess(obj);
            });
            // handle form beforeSubmit action
            $(window).on('auth_form.beforeSubmit', function (e, obj) {
                self.handleBeforeSubmit();
            });
            // handle form error action
            $(window).on('auth_form.error', function (e, obj) {
                self.handleError(obj);
            });
        }

        /**
         * handle the ajax form submission error
         */

    }, {
        key: 'handleError',
        value: function handleError(obj) {
            obj.halt = true;
            obj.button.button('reset');
            $('.danger-wrapper .message').html(obj.message);
            $('.danger-wrapper').fadeIn('fast');
        }

        /**
         * handle the ajax form submission success
         */

    }, {
        key: 'handleSuccess',
        value: function handleSuccess(obj) {
            obj.halt = true;
            obj.button.button('success');
            window.location = Core.url(obj.data.route ? obj.data.route : '');
        }

        /**
         * handle the ajax form beforeSubmit event
         */

    }, {
        key: 'handleBeforeSubmit',
        value: function handleBeforeSubmit(obj) {
            $('.danger-wrapper').fadeOut('fast');
        }

        /**
         * Show any notifications we might have
         */

    }, {
        key: 'checkNotifications',
        value: function checkNotifications() {
            if (notification !== null) {
                $('.' + notification.status + '-wrapper .message').html(notification.message);
                $('.' + notification.status + '-wrapper').show();
            }
        }
    }]);

    return Auth;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    window.Auth = new Auth();
});
