define([
    'underscore',
    'jquery',
    'mage/storage',
    'mage/url',
    'mage/template',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magento_Customer/js/customer-data'
], function (
    _,
    $,
    storage,
    urlBuilder,
    mageTemplate,
    errorProcessor,
    Component,
    additionalValidators,
    customerData
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Monogo_Alphabank/payment/alphabank'
        },

        options: {
            hiddenFormTmpl: mageTemplate(
                '<% _.each(data.inputs, function(val, key){ %>' +
                '<input value="<%= val %>" name="<%= key %>" type="hidden" />' +
                '<% }); %>'
            )
        },

        context: function() {
            return this;
        },

        getCode: function() {
            return 'alphabank';
        },

        isActive: function() {
            return true;
        },

        getData: function () {
            return $.extend(true, this._super(), {
                'additional_data': {
                    'payMethod': $('#' + this.getCode() + '-form select[name="paymethod"] option:selected').val()
                }
            });
        },

        getCartTypes: function () {
            return _.map(window.checkoutConfig.payment.alphabank.cardTypes, function (value, key) {
                return {
                    'value': key,
                    'text': value
                };
            });
        },

        placeOrder: function (data, event) {
            return this.continueToAlphabank(data, event);
        },

        continueToAlphabank: function (data, event) {
            var self = this;

            if (event) {
                event.preventDefault();
            }

            if (this.validate() && additionalValidators.validate()) {
                this.isPlaceOrderActionAllowed(false);

                this.getPlaceOrderDeferredObject()
                    .fail(
                        function () {
                            self.isPlaceOrderActionAllowed(true);
                        }
                    ).done(
                        function () {
                            // customerData.invalidate(['cart']);
                            // self.afterPlaceOrder();
                            storage.get(
                                urlBuilder.build('alphabank/payment/info', {})
                            ).fail(
                                function (response) {
                                    errorProcessor.process(response, self.messageContainer);
                                }
                            ).success(
                                function (response) {
                                    var $form = $('#' + self.getCode() + '-form');
                                    $form.attr('action', window.checkoutConfig.payment.alphabank.apiUrl);
                                    $form.attr('method', 'post');
                                    var tmpl = self.options.hiddenFormTmpl({
                                        data: {inputs: response}
                                    });
                                    $form.append(tmpl);
                                    $form.submit();
                                }
                            );
                        }
                    );

                return true;
            }

            return false;
        },
    });
});
