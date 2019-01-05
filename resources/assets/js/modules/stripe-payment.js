/******************************************************
 * Stripe payment form
 ******************************************************/
class StripePayment {

    /**
     * Class constructor, called when instantiating new class object
     */
    constructor() {
        // declare our class properties
        this.stripe = Stripe(stripe_config.publishable_key);
        this.card = null;
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */
    init() {
        // setup stripe
        this.setupStripe();
        // bind events
        this.bindEvents();
    }

    /**
     * setup our stripe stuff
     */
    setupStripe() {

        let self = this;
        let elements = self.stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        let style = {
            base: {
                color: '#495057',
                fontSize: '16px',
                lineHeight: '24px',
                fontFamily: 'helvetica, tahoma, calibri, sans-serif'
            }
        };

        if ( $('#card_element').length ) {

            // Create an instance of the card Element
            self.card = elements.create('card', {style: style});

            // Add an instance of the card Element into the `card-element` <div>
            self.card.mount('#card_element');
            self.card.on('ready', function() {
                $('#card_element').closest('form').find('button.submit').prop('disabled', false);
            });

            // setup error listening on card element
            self.card.addEventListener('change', function(event) {
                if ( event.error ) {
                    self.setPaymentError(event.error.message);
                } else {
                    self.setPaymentError();
                }
            });

        }

    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;
        let formId = $('form.stripe-payment:first').attr('id');
        $(window).on(formId + '.validationSuccess', function (e, obj) {
            $('.error-wrapper').hide();
            if ( $('#stripe_token').val() === '' && $('#card_element').is(':visible') ) {
                obj.halt = true;
                obj.button.button('loading');
                self.getToken();
            } else {
                obj.halt = false;
            }
        });
    }

    /**
     * get card token from stripe
     */
    getToken() {
        let self = this;
        self.stripe.createToken(self.card).then(function(result) {
            if ( result.error ) {
                self.setPaymentError(result.error.message);
            } else {
                self.setPaymentError();
                $('#stripe_token').val(result.token.id);
                $('form.stripe-payment').submit();
            }
        });
    }

    /**
     * set our error message
     */
    setPaymentError(message) {
        $('#dataValue').val('');
        $('#dataDescriptor').val('');
        $('.error-message').html(message);
        if ( message === undefined ) {
            $('.error-wrapper').hide();
        } else {
            $('.error-wrapper').show();
        }
        if ( message !== undefined ) {
            $('form.stripe-payment button[data-loading-text]').button('reset');
        }
    }

}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new StripePayment();
});