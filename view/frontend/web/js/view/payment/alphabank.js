define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'alphabank',
            component: 'Monogo_Alphabank/js/view/payment/method-renderer/alphabank-method'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});
