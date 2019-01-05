<div class="modal fade" id="add_payment_method" tabindex="-1" role="dialog" aria-labelledby="add_payment_method_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ url('account/billing/payment-method') }}" method="post" class="labels-right payment-form" id="add_payment_method_form">
                <input type="hidden" name="token" value="">
                {!! Html::hiddenInput(['method' => 'post']) !!}

                <div class="modal-body">

                    <h6 class="text-muted mt-3 mb-4">
                        <img src="{{ url('assets/images/credit-cards.jpg') }}" class="float-right">
                        Add New Credit/Debit Card
                    </h6>


                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div id="card_element">
                                <!-- a Stripe Element will be inserted here. -->
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                                <input type="checkbox" name="is_default" id="is_default" value="1" checked>
                                <label for="is_default">Make this my default payment method</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 error-wrapper display-none">
                        <div class="alert alert-alt alert-danger">
                            <button type="button" class="close" data-hide="error-wrapper" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <div class="mb10">
                                <strong><i class="fa fa-exclamation-triangle"></i> Oh snap! Something went wrong...</strong>
                            </div>
                            <em class="error-message"></em>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>"><i class="fa fa-check"></i> Save</button>
                </div>

            </form>

        </div>
    </div>
</div>