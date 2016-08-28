##Juspay PHP Client Library

-----------------------

The Juspay PHP library provides integration access to the Juspay services. The library is distributed using `composer`.

To add the library to your project, add it to your **composer.json**. This package requires a `minimum-stability` of `dev`.
Set the `minimum-stability` in your **composer.json** accordingly.

## Example usage

-----------------------

### SDK APIs

The SDK exposes 2 main api classes, the `Orders` and `Cards` APIs.

But before we start creating orders and process payments, we need to do some basic setup of the SDK.

#### Setting up the SDK for use.

To setup the SDK, we must first declare the **merchantId**, **apiKey** and **environment** that we wish to use.

Set up the SDK by invoking the following function: `configure($environment, $merchantId, $apiKey)` 
**Note: The merchantd that is passed to the configure method must be created on the corresponding environment. If you create an account in
sandbox.juspay.in and pass the environment as ** `JuspayConfiguration::ENVIRONMENT_PRODUCTION` ** API's will not work`.

Once the setup is done, one can create instances of the API classes, nameley the `Orders` and `Cards` classes.


        Juspay::configure('myMerchantId', '3954450E640447CAA7833D0C266CB2F8', JuspayConfiguration::ENVIRONMENT_SANDBOX);
        
        $socketConnectTimeout = 10;
        $connectionReadTimeout = 20;
        
        $ordersApiInstance = new Orders($socketConnectTimeout, $connectionReadTimout);
        $cardsApiInstance = new Cards($socketConnectTimeout, $connectionReadTimout);
 
As an alternative, one can invoke the `JuspayConfigure::configureAndSetUp($environment, $merchantId, $apiKey, $apiConnectTimeout, $apiReadTimeout)`
method, passing in a connect timeout and read timeout value. Calling this method sets up instances of the `Order` and `Card` APIs.

        $socketConnectTimeout = 10;
        $connectionReadTimeout = 20;
        
        JuspayConfiguration::configureAndSetUp(
            'myMerchantId', 
            '3954450E640447CAA7833D0C266CB2F8',
             JuspayConfiguration::ENVIRONMENT_SANDBOX,
             $socketConnectTimeout,
             $connectionReadTimeout
        ); 
        
        // Instances of the Cards and Orders APIs are avalable inside the ExpressCheckout class.
        $ordersApiInstance = ExpressCheckout::$Order;
        $cardsApiInstance = ExpressCheckout::$Card;
        

#### Note on API response

The responses from the Juspay HTTP API are always in JSON format. The JSON format data returned from the HTTP APIs are parsed using 
the PHP `json_decode` function. The response is decoded into PHP arrays.

All values returned from the `Cards` and `Orders` API are php `arrays` having the `body`, `headers` and
`responseCode` as keys. `body` consists of the HTTP payload of the response, the `headers` contain the response headers
and `responseCode` constains the HTTP status code of the response.

Example:

Response from the `ExpressCheckout::$Cards->addCard($params)`:

        Array
        (
            [body] => Array
                (
                    [card_token] => ab9e9d3f-35d4-4a24-93f1-762a082ae4e4
                    [card_reference] => 66d54d9150d10335ba9d947fb5fb1748
                    [card_fingerprint] => 3csmo6fvfsge9pqftfb858aicp
                )
        
            [headers] => HTTP/1.1 100 Continue
                         HTTP/1.1 200 OK
                         Cache-Control: private, no-cache, no-store, must-revalidate
                         Cache-control: no-cache="set-cookie"
                         Content-Type: application/json;charset=UTF-8
                         Date: Thu, 07 Jul 2016 06:28:46 GMT
                         Expires: Sat, 01 Jan 2000 00:00:00 GMT
                         Pragma: no-cache
                         Server: ***
                         Set-Cookie: AWSELB=6773E1D310F68F94E7244334D860EC90C0C6FD86A5BBFB381A3D95415EEA068E0EDC597C16422068E16BCEF4B4226E60D53FD0A870DA118459E1FF8E4B71D9020D5E78EC55;PATH=/;MAX-AGE=1800
                         Strict-Transport-Security: max-age=0; includeSubDomains
                         Content-Length: 166
                         Connection: keep-alive
        
            [responseCode] => 200
        )

