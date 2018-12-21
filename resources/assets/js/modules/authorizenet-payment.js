/******************************************************
 * Authorize.Net payment form
 ******************************************************/
class AuthorizenetPayment {

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
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;
        let formId = $('form.authorizenet-payment:first').attr('id');
        $(window).on(formId + '.validationSuccess', function (e, obj) {
            $('.error-wrapper').hide();
            if ( $('#dataValue').val() === '' && $('#card_number:visible').length ) {
                obj.halt = true;
                obj.button.button('loading');
                self.submitCard();
            } else {
                if ( $('#card_expiration').val() !== '' ) {
                    $('#' + formId).append('<input type="hidden" name="payment_methods[cc_expiration_month]" value="' + $('#card_expiration').val().split('/')[0] + '">');
                    $('#' + formId).append('<input type="hidden" name="payment_methods[cc_expiration_year]" value="' + $('#card_expiration').val().split('/')[1] + '">');
                }
                $('#card_number, #card_expiration, #card_code').val('');
                obj.halt = false;
            }
        });
        $('#card_expiration').on('keyup', function(e) {
            let val = $('#card_expiration').val();
            if ( val.length === 2 && e.which !== 8 ) {
                $('#card_expiration').val(val + '/');
            }
        });
    }

    /**
     * submit card details
     */
    submitCard() {
        let self = this;

        let authData = {};
        authData.clientKey = authorizenet_config.client_key;
        authData.apiLoginID = authorizenet_config.login_id;

        let cardData = {};
        cardData.cardNumber = $('#card_number').val();
        cardData.month = $('#card_expiration').val().split('/')[0];
        cardData.year = $('#card_expiration').val().split('/')[1];
        cardData.cardCode = $('#card_code').val();

        let secureData = {};
        secureData.authData = authData;
        secureData.cardData = cardData;

        Accept.dispatchData(secureData, function(response) {
            self.handleResponse(response);
        });

    }

    /**
     * handle success event 
     */
    handleResponse(response) {
        let self = this;
        if ( response.messages.resultCode === 'Ok' ) {
            $('#dataValue').val(response.opaqueData.dataValue);
            $('#dataDescriptor').val(response.opaqueData.dataDescriptor);
            $('form.authorizenet-payment').submit();
        } else {
            let error = '';
            let i = 0;
            while ( i < response.messages.message.length ) {
                error += response.messages.message[i].text;
                i = i + 1;
            }
            self.setPaymentError(error);
        }
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
            $('form.authorizenet-payment button[data-loading-text]').button('reset');
        }
    }

}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new AuthorizenetPayment();
});