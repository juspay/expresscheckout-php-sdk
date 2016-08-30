# Juspay PHP Client Library #

-----------------------

The Juspay PHP library provides integration access to the Juspay services. The library is distributed using `composer`.

To add the library to your project, add it to your **composer.json**. This package requires a `minimum-stability` of `stable`.
Set the `minimum-stability` in your **composer.json** accordingly.

## Setting up the SDK for use. ##

By default SDK is initialised for Juspay production account.

**To setup PHP SDK for production account with default timeouts, use following code:**

```
#!php

JuspayEnvironment::init()
->withApiKey("your_api_key")

```


**To setup PHP SDK for sandbox account with default timeouts, use following code:**

```
#!php

JuspayEnvironment::init()
->withApiKey("your_api_key")
->withBaseUrl(JuspayEnvironment::SANDBOX_BASE_URL)

```

**To setup PHP SDK for production account with custom timeouts, use following code:**

```
#!php

JuspayEnvironment::init()
->withApiKey($yourApiKey)
->withConnectTimeout($connectTimeout)
->withReadTimeout($readTimeout);

```

**To setup PHP SDK for sandbox account with custom timeouts, use following code:**

```
#!php

JuspayEnvironment::init()
->withApiKey($yourApiKey)
->withBaseUrl(JuspayEnvironment::SANDBOX_BASE_URL)
->withConnectTimeout($connectTimeout)
->withReadTimeout($readTimeout);

```

## Using SDK ##

The input to all methods in SDK is an associative array and most of the methods will return the object of the class, in which they are defined.

### Example: ###

**Adding a card to JuspayLocker:**


```
#!php

        $params = array ();
        $params ['merchant_id'] = "merchantId";
        $params ['customer_id'] = "customerId";
        $params ['customer_email'] = "support@juspay.in";
        $params ['card_number'] = "4111111111111111";
        $params ['card_exp_year'] = "2018";
        $params ['card_exp_month'] = "07";
        $params ['name_on_card'] = "Juspay Technologies";
        $params ['nickname'] = "ICICI VISA";
        $card = Card::create ( $params );

```

