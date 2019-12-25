<?php

namespace Monogo\Alphabank\Gateway\Request;

use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;

class RequestBuilder extends DataObject
{
    const TXN_TYPE = 1; // 1 = sale, 2 = authorization

    const CONFIG_MERCHANT_ID = 'merchant_id';

    const CONFIG_SECRET = 'secret_key';

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param ConfigInterface $config
     * @param UrlInterface $url
     * @param array $data
     */
    public function __construct(ConfigInterface $config, UrlInterface $url, $data = [])
    {
        $this->config = $config;
        $this->url = $url;

        parent::__construct($data);
    }

    /**
     * @param OrderAdapterInterface $order
     * @return array
     */
    protected function buildRequest($order)
    {
        $form_data_array = array();
        $fieldsArr = array();

        $shippingAddress = $order->getShippingAddress();
        $shippingStreet = '';
        if (!empty($shippingAddress->getStreetLine1()) && !empty($shippingAddress->getStreetLine2())) {
            $shippingStreet = $shippingAddress->getStreetLine1() . ', ' . $shippingAddress->getStreetLine2();
        } else if (!empty($shippingAddress->getStreetLine1())) {
            $shippingStreet = $shippingAddress->getStreetLine1();
        } else if (!empty($shippingAddress->getStreetLine2())) {
            $shippingStreet = $shippingAddress->getStreetLine2();
        }

        $billingAddress = $order->getBillingAddress();
        $billingStreet = '';
        if (!empty($billingAddress->getStreetLine1()) && !empty($billingAddress->getStreetLine2())) {
            $billingStreet = $billingAddress->getStreetLine1() . ', ' . $billingAddress->getStreetLine2();
        } else if (!empty($billingAddress->getStreetLine1())) {
            $billingStreet = $billingAddress->getStreetLine1();
        } else if (!empty($billingAddress->getStreetLine2())) {
            $billingStreet = $billingAddress->getStreetLine2();
        }
//        $paymentInfo = $this->getInfoInstance();
//        $additional_information = $paymentInfo->getAdditionalInformation();

        $fieldsArr['mid']  = $this->getConfig()->getValue(self::CONFIG_MERCHANT_ID);
        $form_data_array[1] = $fieldsArr['mid'] ;                                                   //Req
        $fieldsArr['lang'] = '';
        $form_data_array[2] = $fieldsArr['lang'];                                                   //Opt
        $fieldsArr['deviceCategory'] = "";
        $form_data_array[3] = $fieldsArr['deviceCategory'];                                         //Opt
        $fieldsArr['orderid'] = $order->getId();
        $form_data_array[4] = $fieldsArr['orderid'];                                                //Req
        $fieldsArr['orderDesc'] = 'Order by ' . $billingAddress->getFirstname() . ' ' . $billingAddress->getLastname();
        $form_data_array[5] = $fieldsArr['orderDesc'];                                              //Opt
        $fieldsArr['orderAmount'] = $order->getGrandTotalAmount();
        $form_data_array[6] = $fieldsArr['orderAmount'];                                            //Req
        $fieldsArr['currency'] = $order->getCurrencyCode();
        $form_data_array[7] = $fieldsArr['currency'];                                               //Req
        $fieldsArr['payerEmail'] = $billingAddress->getEmail();
        $form_data_array[8] = $fieldsArr['payerEmail'];                                             //Req
        $fieldsArr['payerPhone'] = $billingAddress->getTelephone();
        $form_data_array[9] = $fieldsArr['payerPhone'];                                             //Opt        
        $fieldsArr['billCountry'] = $billingAddress->getCountryId();
        $form_data_array[10] = $fieldsArr['billCountry'];                                           //Opt
        $fieldsArr['billState'] = $billingAddress->getRegionCode();
        $form_data_array[11] = $fieldsArr['billState'];                                             //Opt
        $fieldsArr['billZip'] = $billingAddress->getPostcode();
        $form_data_array[12] = $fieldsArr['billZip'];                                               //Opt
        $fieldsArr['billCity'] = $billingAddress->getCity();
        $form_data_array[13] = $fieldsArr['billCity'];                                              //Opt
        $fieldsArr['billAddress'] = $billingStreet;
        $form_data_array[14] = $fieldsArr['billAddress'];                                           //Opt
        $fieldsArr['weight'] = '';
        $form_data_array[15] = $fieldsArr['weight'];                                                //Opt
        $fieldsArr['dimensions'] = '';
        $form_data_array[16] = $fieldsArr['dimensions'];                                            //Opt
        $fieldsArr['shipCountry'] = $shippingAddress->getCountryId();
        $form_data_array[17] = $fieldsArr['shipCountry'];                                           //Opt
        $fieldsArr['shipState'] = $shippingAddress->getRegionCode();
        $form_data_array[18] = $fieldsArr['shipState'];                                             //Opt
        $fieldsArr['shipZip'] = $shippingAddress->getPostcode();
        $form_data_array[19] = $fieldsArr['shipZip'];                                               //Opt
        $fieldsArr['shipCity'] = $shippingAddress->getCity();
        $form_data_array[20] = $fieldsArr['shipCity'];                                              //Opt
        $fieldsArr['shipAddress'] = $shippingStreet;
        $form_data_array[21] = $fieldsArr['shipAddress'];                                           //Opt
        $fieldsArr['addFraudScore'] = '';
        $form_data_array[22] = $fieldsArr['addFraudScore'];                                         //Opt
        $fieldsArr['maxPayRetries'] = '';
        $form_data_array[23] = $fieldsArr['maxPayRetries'];                                         //Opt
        $fieldsArr['reject3dsU'] = '';
        $form_data_array[24] = $fieldsArr['reject3dsU'];                                            //Opt
        $fieldsArr['payMethod'] = '';
        $form_data_array[25] = $fieldsArr['payMethod'];                                             //Opt
        $fieldsArr['trType'] = $this->getTransactionType();
        $form_data_array[26] = $fieldsArr['trType'];                                                //Opt
        $fieldsArr['extInstallmentoffset'] = '';
        $form_data_array[27] = $fieldsArr['extInstallmentoffset'];                                  //Opt
        $fieldsArr['extInstallmentperiod'] = '';
        $form_data_array[28] = $fieldsArr['extInstallmentperiod'];                                  //Opt
        $fieldsArr['extRecurringfrequency'] = '';
        $form_data_array[29] = $fieldsArr['extRecurringfrequency'];                                 //Opt
        $fieldsArr['extRecurringenddate'] = '';
        $form_data_array[30] = $fieldsArr['extRecurringenddate'];                                   //Opt
        $fieldsArr['blockScore'] = '';
        $form_data_array[31] = $fieldsArr['blockScore'];                                            //Opt
        $fieldsArr['cssUrl'] = '';
        $form_data_array[32] = $fieldsArr['cssUrl'];                                                //Opt
        $fieldsArr['confirmUrl'] = $this->getConfirmUrl();
        $form_data_array[33] = $fieldsArr['confirmUrl'];                                            //Req
        $fieldsArr['cancelUrl'] = $this->getCancelUrl();
        $form_data_array[34] = $fieldsArr['cancelUrl'];                                             //Req
        $fieldsArr['var1'] = '';
        $form_data_array[35] = $fieldsArr['var1'];
        $fieldsArr['var2'] = '';
        $form_data_array[36] = $fieldsArr['var2'];
        $fieldsArr['var3'] = '';
        $form_data_array[37] = $fieldsArr['var3'];
        $fieldsArr['var4'] = '';
        $form_data_array[38] = $fieldsArr['var4'];
        $fieldsArr['var5'] = '';
        $form_data_array[39] = $fieldsArr['var5'];
        $form_secret = $this->getConfig()->getValue(self::CONFIG_SECRET);
        $form_data_array[40] = $form_secret;                                                        //Req

        $form_data = implode("", $form_data_array);
        $digest = base64_encode(sha1($form_data,true));
        $fieldsArr['digest'] = $digest;

//        $this->_debug(['request' => $fieldsArr]);

        return $fieldsArr;
    }

    /**
     * @return ConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @return int
     */
    private function getTransactionType()
    {
        return static::TXN_TYPE;
    }

    /**
     * @return string
     */
    private function getConfirmUrl()
    {
        return $this->url->getUrl('alphabank/order/success');
    }

    /**
     * @return string
     */
    private function getCancelUrl()
    {
        return $this->url->getUrl('alphabank/order/cancel');
    }
}
