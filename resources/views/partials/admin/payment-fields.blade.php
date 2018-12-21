<div class="form-group row">
    <label class="col-form-label col-sm-3">
        Payment Method
    </label>
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-4">
                <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Card Number" maxlength="16" data-fv-notempty="true">
            </div>
            <div class="col-sm-2">
                <input type="text" name="card_expiration" id="card_expiration" class="form-control" placeholder="MM/YY" maxlength="5" data-fv-notempty="true">
            </div>
            <div class="col-sm-2">
                <input type="text" name="card_code" id="card_code" class="form-control" placeholder="CVV" maxlength="4" data-fv-notempty="true">
            </div>
            <div class="col-sm-4">
                <img src="{{ url('images/credit-cards.jpg') }}" class="">
            </div>
        </div>
    </div>
</div>
<div class="form-group row mt-4 error-wrapper hide">
    <div class="col-sm-9 offset-sm-3">
        <div class="alert alert-alt alert-danger">
            <button type="button" class="close" data-hide="error-wrapper" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="fal fa-exclamation-triangle"></i> <span class="error-message"></span>
        </div>
    </div>
</div>