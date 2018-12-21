var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Permissions class used on our permissions stuff
 ******************************************************/
var Permissions = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function Permissions() {
        _classCallCheck(this, Permissions);

        // declare our class properties
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(Permissions, [{
        key: 'init',
        value: function init() {
            // bind events
            this.bindEvents();
            // process checkboxes on load
            this.onLoad();
        }

        /**
         * bind all necessary events
         */

    }, {
        key: 'bindEvents',
        value: function bindEvents() {
            var self = this;

            $('input.permission-group').on('change', function (e) {
                var checked = $(this).prop('checked');
                $(this).closest('.permission-group-wrapper').find('.permission-function').prop('checked', checked);
            });

            $('input.permission-function').on('change', function () {
                var $group = $(this).closest('.permission-group-wrapper').find('input.permission-group');
                var yes = false;
                var no = false;
                $(this).closest('.functions').find('.permission-function').each(function () {
                    if ($(this).prop('checked')) {
                        yes = true;
                    } else {
                        no = true;
                    }
                });
                if (yes && no) {
                    $group.prop('readOnly', true).prop('indeterminate', true);
                } else if (yes && !no) {
                    $group.prop('readOnly', false).prop('indeterminate', false).prop('checked', true);
                } else if (!yes && no) {
                    $group.prop('readOnly', false).prop('indeterminate', false).prop('checked', false);
                }
            });

            $('input[name="name"]').on('blur', function () {
                var value = $(this).val();
                if ($('input[name="slug"]').val() === '') {
                    $('input[name="slug"]').val(value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
                }
            });
        }

        /**
         * process checkboxes on load
         */

    }, {
        key: 'onLoad',
        value: function onLoad() {
            $('.permission-group-wrapper').each(function () {
                $(this).find('.permission-function:first').trigger('change');
            });
        }
    }]);

    return Permissions;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    new Permissions();
});
