<div id="modal-field-tags" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">{{ __('messages.template_tags') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('messages.close') }}">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                
                <p><kbd>{company.name}</kbd></p>
                <p><kbd>{company.vat_number}</kbd></p>
                <p><kbd>{company.billing.address_1}</kbd></p>
                <p><kbd>{company.billing.address_2}</kbd></p>
                <p><kbd>{company.billing.city}</kbd></p>
                <p><kbd>{company.billing.state}</kbd></p>
                <p><kbd>{company.billing.country}</kbd></p>
                <p><kbd>{company.billing.phone}</kbd></p>
                <p><kbd>{company.billing.zip}</kbd></p>
                <br>
                <p><kbd>{customer.name}</kbd></p>
                <p><kbd>{customer.vat_number}</kbd></p>
                <p><kbd>{customer.billing.address_1}</kbd></p>
                <p><kbd>{customer.billing.address_2}</kbd></p>
                <p><kbd>{customer.billing.city}</kbd></p>
                <p><kbd>{customer.billing.state}</kbd></p>
                <p><kbd>{customer.billing.country}</kbd></p>
                <p><kbd>{customer.billing.phone}</kbd></p>
                <p><kbd>{customer.billing.zip}</kbd></p>
                <br>
                <p><kbd>{customer.shipping.address_1}</kbd></p>
                <p><kbd>{customer.shipping.address_2}</kbd></p>
                <p><kbd>{customer.shipping.city}</kbd></p>
                <p><kbd>{customer.shipping.state}</kbd></p>
                <p><kbd>{customer.shipping.country}</kbd></p>
                <p><kbd>{customer.shipping.phone}</kbd></p>
                <p><kbd>{customer.shipping.zip}</kbd></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>