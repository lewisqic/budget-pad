var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Signup class used on the public sign up page
 ******************************************************/
var StripePayment = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function StripePayment() {
        _classCallCheck(this, StripePayment);

        // declare our class properties
        this.stripe = Stripe(stripe_config.publishable_key);
        this.card = null;
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(StripePayment, [{
        key: 'init',
        value: function init() {
            // bind events
            this.bindEvents();
            // setup stripe
            this.setupElement();
        }

        /**
         * bind all necessary events
         */

    }, {
        key: 'bindEvents',
        value: function bindEvents() {
            var self = this;
            var formId = $('form.stripe-payment:first').attr('id');
            $(window).on(formId + '.validationSuccess', function (e, obj) {
                $('.error-wrapper').hide();
                var $token = $('input[name="token"]');
                if ($token.val() === '') {
                    obj.halt = true;
                    obj.button.button('loading');
                    self.stripe.createToken(self.card).then(function (result) {
                        if (result.error) {
                            self.setPaymentError(result.error.message);
                        } else {
                            self.setPaymentError();
                            $token.val(result.token.id);
                            $('#' + formId).submit();
                        }
                    });
                } else {
                    obj.halt = false;
                }
            });
        }

        /**
         * setup our stripe stuff
         */

    }, {
        key: 'setupElement',
        value: function setupElement() {

            var self = this;
            var elements = self.stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    color: '#495057',
                    fontSize: '16px',
                    lineHeight: '24px',
                    fontFamily: 'helvetica, tahoma, calibri, sans-serif'
                }
            };

            if ($('#card_element').length) {

                // Create an instance of the card Element
                self.card = elements.create('card', { style: style });

                // Add an instance of the card Element into the `card-element` <div>
                self.card.mount('#card_element');
                self.card.on('ready', function () {
                    $('button.submit').prop('disabled', false);
                });

                // setup error listening on card element
                self.card.addEventListener('change', function (event) {
                    if (event.error) {
                        self.setPaymentError(event.error.message);
                    } else {
                        self.setPaymentError();
                    }
                });
            }
        }

        /**
         * set our error message
         */

    }, {
        key: 'setPaymentError',
        value: function setPaymentError(message) {
            $('input[name="token"]').val('');
            $('.error-message').html(message);
            if (message === undefined) {
                $('.error-wrapper').hide();
            } else {
                $('.error-wrapper').show();
            }
            if (message !== undefined) {
                $('form.stripe-payment button[data-loading-text]').button('reset');
            }
        }
    }]);

    return StripePayment;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    new StripePayment();
});
