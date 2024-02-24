<?php
if (!function_exists('paypro_dynamic')) {
    function paypro_dynamic(float $price = 0, float $discount_per = 0, string $currency = 'USD', string $name = null, string $description = null, int $dynamic_product_id = 0)
    {
        include('PayproCrypt/AES.php');

        // your 32-char key from the dynamic product settings
        $key = substr(md5(config('constants.emd_paypro_dynamic_plan_key') . '-key'), 0, 32);
        // your 16-char iv from the dynamic product settings
        $iv = substr(md5(config('constants.emd_paypro_dynamic_plan_key') . '-iv'), 0, 16);

        // initialize cryptography
        $aes = new Crypt_AES();
        $aes->setKey($key);
        $aes->setIV($iv);
        $aes->setKeyLength(256);

        // dynamic product parameters represented as an associative array
        $data = array();

        // please note we have special characters like '&' and '=' and they will be displayed fine
        $data['Name'] = $name;

        // special characters and HTML is allowed in description
        $data['Description'] = $description ?: '';

        // the first currency provided will be used as a base currency in automatic currency conversion to other currencies
        $currency_allows = ['USD', 'EUR', 'GBP'];
        if (!in_array($currency, $currency_allows)) {
            $currency = 'USD';
        }
        $data['Price'][$currency]['Amount'] = $price;


        // if you don't want automatic currency conversion rate for a price in some currencies
        // you can provide your own price value to be used for any currency
        // if customer switches the currency to one of those he will get the price value you provide
        // $data['Price']['GBP']['Amount'] = 95.95;
        // $data['Price']['EUR']['Amount'] = 95.95;
        $for_sku_code = config('constants.emd_paypro_dynamic_plan_key') . "_" . time();


        // any stock tracking unit you may want to use to mark he product with it
        $data['Price']['USD']['Sku'] = $for_sku_code;

        // specify dynamic product setting for recurring price that will be charged on each re-bill
        // product in the control panel should be configured as a subscription product to accept this setting
        $data['RecurringPrice']['USD']['Amount'] = $price;

        // any stock tracking unit you may want to use to mark he product with it
        $data['RecurringPrice']['USD']['Sku'] = $for_sku_code;

        // for discount in percentage DiscountPercentage=XX.YY;
        $data['DiscountPercentage'] = $discount_per;

        // any parameter that starts from 'x-' is treated as a custom field
        // that is saved together with the order and can be used to track
        // any custom information you want to keep in the order that will be passed in a secure way
        // $data['x-marketing-campaign'] = 'some marketing campaign name';

        $data_str = http_build_query($data);

        $data_final = urlencode(base64_encode($aes->encrypt($data_str)));

        $dynamic_product_url = 'https://store.payproglobal.com/checkout?currency=' . $currency . '&products[1][id]=' . $dynamic_product_id . '&products[1][data]=' . $data_final;


        // echo "\r\n";
        // echo "Dynamic product purchase URL:\r\n";
        return $dynamic_product_url;
        // echo "\r\n";
    }
}
