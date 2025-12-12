# PHP Borica EMV 3DS

## Requirements

- PHP >= 5.6 (including 8.5)
- ext-mbstring
- ext-openssl
- ext-curl
- ext-json

## Installation

Install the package via Composer:

```shell script
composer require veneliniliev/borica-3ds
```

### Version Compatibility

| Library Version | Supported Signing Schemas                       | Default Signing Schema | PHP Support      |
|-----------------|-------------------------------------------------|------------------------|------------------|
| ^2.0           | MAC_EXTENDED, MAC_ADVANCED, MAC_GENERAL         | MAC_GENERAL           | PHP 5.6 - 8.5    |
| ^1.0           | MAC_EXTENDED, MAC_ADVANCED                      | MAC_ADVANCED          | PHP 5.6 - 8.5    |

### Signing Schema Information

- **MAC_GENERAL**: The latest schema with enhanced security (default in v2.0+)
- **MAC_EXTENDED**: Extended schema with additional fields
- **MAC_ADVANCED**: Advanced schema with specific field requirements

You can switch between signing schemas using the following methods:
- `setSigningSchemaMacGeneral()` - Use MAC_GENERAL schema
- `setSigningSchemaMacExtended()` - Use MAC_EXTENDED schema
- `setSigningSchemaMacAdvanced()` - Use MAC_ADVANCED schema

For more methods, read [api documentation](API.md).

For official Borica resources like their API documentation, public keys for validation and more visit https://3dsgate-dev.borica.bg/

## Certificates

### Generate private key

```shell script
# Production key
openssl genrsa -out production.key -aes256 2048
# Development key
openssl genrsa -out development.key -aes256 2048
```

### Generate CSR

**IMPORTANT**: in `Organizational Unit Name (eg, section)` enter your terminal ID and
in `Common Name (eg, fully qualified host name)` enter your domain name.

```shell script
# Production csr
openssl req -new -key production.key -out VNNNNNNN_YYYYMMDD_P.csr
# Development csr
openssl req -new -key development.key -out VNNNNNNN_YYYYMMDD_D.csr
```

Имената на файловете се създават по следната конвенция: **VNNNNNNN_YYYYMMDD_T**, където:

- **VNNNNNNN** – TID на терминала, предоставен от Финансовата Институция
- **YYYYMMDD** – дата на заявка
- **T** – тип на искания сертификат, значения – **D** – за development среда, **Р** – за продукционна среда

## Usage

**IMPORTANT**: Switch signing schema MAC_EXTENDED / MAC_ADVANCED / MAC_GENERAL with methods:

````php
$saleRequest->setSigningSchemaMacGeneral(); // use MAC_GENERAL
$saleRequest->setSigningSchemaMacExtended(); // use MAC_EXTENDED
$saleRequest->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
````

Default signing schema is **MAC_GENERAL**!

### Sale request

````php
use VenelinIliev\Borica3ds\SaleRequest;
// ...
$saleRequest = (new SaleRequest())
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('test')
    ->setMerchantUrl('https://test.com') // optional
    ->setTerminalID('<TID - V*******>')
    ->setMerchantId('<MID - 15 chars>')
    ->setPrivateKey('\<path to certificate.key>', '<password / or use method from bottom>')
    ->setMInfo(array( // Mandatory cardholderName and ( email or MobilePhone )
        'email'=>'user@sample.com',
        'cardholderName'=>'CARDHOLDER NAME', // Max 45 chars
        'mobilePhone'=> array( 
            'cc'=>'359', // Country code
            'subscriber'=>'8939999888', // Subscriber number
        ),
        'threeDSRequestorChallengeInd'=>'04', //  Optional for Additional Authentication
    ))
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
    ->setPrivateKeyPassword('test');

$formHtml = $saleRequest->generateForm(); // only generate hidden html form with filled inputs 
// OR
$saleRequest->send(); // generate and send form with js 
````

### Sale response

Catch response from borica on `BACKREF` url

`->setPublicKey` is the Borica public key and not the one you've generated. You can download borica key for DEV and PROD environment from here: https://3dsgate-dev.borica.bg/

```php
use VenelinIliev\Borica3ds\SaleResponse;
// ....
$isSuccessfulPayment = (new SaleResponse())
            ->setPublicKey('<path to public certificate.cer>') # Borica public key for the specific env
            ->setResponseData($_POST) //Set POST data from borica response
            //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
            //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
            //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
            ->isSuccessful();
```

#### Get response code

```php
use VenelinIliev\Borica3ds\SaleResponse;
// ...
$saleResponse= (new SaleResponse())
               ->setPublicKey('<path to public certificate.cer>')  # Borica public key for the specific env
               //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
               //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
               //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED

// ...
// automatic fill data from $_POST or can be set by ->setResponseData(<array>)
// ...

$saleResponse->getResponseCode(); // return RC from response
$saleResponse->getVerifiedData('<key from post request ex: RC>'); // return verified data from post by key
$saleResponse->isSuccessful(); // RC === 00 and data is verified
```

Response codes table

| Response Code (RC) | RC DESCRIPTION                  |    
|--------------------|---------------------------------|   
| 00                 | Sucessfull                      |
|                    | => Timeout                      |
| "01"               | Refer to card issuer            |
| "04"               | Pick Up                         |
| "05"               | Do not Honour                   |
| "13"               | Invalid amount                  |
| "30"               | Format error                    |
| "65"               | Soft Decline                    |
| "91"               | Issuer or switch is inoperative |
| "96"               | System Malfunction              |   

### Transaction status check

```php
 use VenelinIliev\Borica3ds\Enums\TransactionType;
 use VenelinIliev\Borica3ds\StatusCheckRequest;
 // ...
 $statusCheckRequest = (new StatusCheckRequest())
    //->inDevelopment()
    ->setPrivateKey('\<path to certificate.key>', '<password / or use method from bottom>')
    ->setPublicKey('<path to public certificate.cer>')
    ->setTerminalID('<TID - V*******>')
    ->setOrder('<order>')
    ->setOriginalTransactionType(TransactionType::SALE()) // transaction type
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED

        
//send to borica
$statusCheckResponse = $statusCheckRequest->send();
 
// get data from borica response
$verifiedResponseData = $statusCheckResponse->getResponseData();

// get field from borica response
$statusCheckResponse->getVerifiedData('<field from response. ex: ACTION');

```

### Reversal request

```php
 use VenelinIliev\Borica3ds\ReversalRequest;
 // ...
 $reversalRequest = (new ReversalRequest())
        //->inDevelopment()
        ->setPrivateKey('\<path to certificate.key>', '<password / or use method from bottom>')
        ->setPublicKey('<path to public certificate.cer>')
        ->setTerminalID('<TID - V*******>')
        ->setAmount(123.32)
        ->setOrder(123456)
        ->setDescription('test reversal')
        ->setMerchantId('<MID - 15 chars>')
        ->setRrn('<RRN - Original transaction reference (From the sale response data)>')
        ->setIntRef('<INT_REF - Internal reference (From the sale response data)>')
        //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
        //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
        //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
        
//send reversal request to borica
$reversalRequestResponse = $reversalRequest->send();

// get data from borica reversal response
$verifiedResponseData = $reversalRequestResponse->getResponseData();

// get field from borica reversal response
$reversalRequestResponse->getVerifiedData('STATUSMSG');
```
### Pre-authorisation

You can also send pre-authorisation requests like this 

````php
use VenelinIliev\Borica3ds\PreAuthorisationRequest;
// ...
$preAuthorisationRequest = (new PreAuthorisationRequest())
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('test')
    ->setMerchantUrl('https://test.com') // optional
    ->setTerminalID('<TID - V*******>')
    ->setMerchantId('<MID - 15 chars>')
    ->setPrivateKey('\<path to certificate.key>', '<password / or use method from bottom>')
    ->setMInfo(array( // Mandatory cardholderName and ( email or MobilePhone )
        'email'=>'user@sample.com',
        'cardholderName'=>'CARDHOLDER NAME', // Max 45 chars
        'mobilePhone'=> array( 
            'cc'=>'359', // Country code
            'subscriber'=>'8939999888', // Subscriber number
        ),
        'threeDSRequestorChallengeInd'=>'04', //  Optional for Additional Authentication
    ))
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
    ->setPrivateKeyPassword('test');

$formHtml = $preAuthorisationRequest->generateForm(); // only generate hidden html form with filled inputs 
// OR
$preAuthorisationRequest->send(); // generate and send form with js 
````

### Pre-authorisation completion

After successful pre-authorisation in 30 days you can make only successful/failed completion.

```php
 $response = (new PreAuthorisationCompletionRequest())
    //->inDevelopment()
    ->setPrivateKey('\<path to certificate.key>', '<password / or use method from bottom>')
    ->setPublicKey('<path to public certificate.cer>')
    ->setTerminalID('<TID - V*******>')
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('test reversal')
    ->setMerchantId('<MID - 15 chars>')
    ->setRrn('<RRN - Original transaction reference (From the sale response data)>')
    ->setIntRef('<INT_REF - Internal reference (From the sale response data)>')
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
    ->send();

$isSuccessful = $response->getVerifiedData('ACTION') === Action::SUCCESS &&
    $response->isSuccessful();
```
### Pre-authorisation reversal

The pre-authorisation reversal request is almost the same as completion request. But this request require the amount to
be the same as the amount of the pre-authorisation.
```php
$response = (new PreAuthorisationReversalRequest())
    //->inDevelopment()
    ->setPrivateKey('\<path to certificate.key>', '<password / or use method from bottom>')
    ->setPublicKey('<path to public certificate.cer>')
    ->setTerminalID('<TID - V*******>')
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('test reversal')
    ->setMerchantId('<MID - 15 chars>')
    ->setRrn('<RRN - Original transaction reference (From the sale response data)>')
    ->setIntRef('<INT_REF - Internal reference (From the sale response data)>')
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
    ->send();

$isSuccessful = $response->getVerifiedData('ACTION') === Action::SUCCESS &&
    $response->isSuccessful();
```

### Determine the response

You can use the determineResponse() get the instance of correct response class. If you do not provide an array with data
it will take the data from $_POST.

```php
$boricaResponse = (Response::determineResponse())
```

### Methods

#### Set environments

Default environment is **production**!

```php
$saleRequest->setEnvironment(true); // set to production
$saleRequest->setEnvironment(false); // set to development
$saleRequest->inDevelopment(); // set to development
$saleRequest->inProduction(); // set to production

$saleRequest->isProduction(); // check is production environment?
$saleRequest->isDevelopment(); // check is development environment?
```

#### Configure language

The library supports setting the language for the user payment form. Use the `setLang` method to set a specific language based on the enum `VenelinIliev\Borica3ds\Enums\Language`. Supported languages are **Bulgarian (BG)** and **English (EN)**.

```php
use VenelinIliev\Borica3ds\SaleRequest;
use VenelinIliev\Borica3ds\Enums\Language;

$saleRequest = (new SaleRequest())
    ->setAmount(100.50) // Transaction amount.
    ->setOrder('123456') // Unique order number.
    ->setDescription('Test product purchase') // Order description.
    ->setTerminalID('<TID - V*******>') // Terminal ID.
    ->setMerchantId('<MID - 15 chars>') // Merchant ID.
    ->setPrivateKey('<path to private key>', '<password>')
    ->setLang(Language::EN()); // Set transaction language to English.

// Alternatively, set the language to Bulgarian.
$saleRequest->setLang(Language::BG());
```

If an invalid language code is provided, the library will throw a `ParameterValidationException`.

Example with an invalid language code:

```php
$saleRequest->setLang('DE'); // Throws exception because 'DE' is not supported.
```

Using `setLang` ensures that users are presented with a language-specific payment form, delivering a more user-friendly experience.

### Configure currency

With Bulgaria joining the Eurozone, you can now set the currency to EUR using the `setCurrency` method.

```php
use VenelinIliev\Borica3ds\SaleRequest;

$saleRequest = (new SaleRequest())
    ->setAmount(100.50) // Transaction amount.
    ->setOrder('123456') // Unique order number.
    ->setDescription('Test product purchase') // Order description.
    ->setTerminalID('<TID - V*******>') // Terminal ID.
    ->setMerchantId('<MID - 15 chars>') // Merchant ID.
    ->setPrivateKey('<path to private key>', '<password>')
    ->setCurrency('EUR'); // Set transaction currency to Euro
```

This is especially important following Bulgaria's adoption of the Euro as its official currency.

### Additional Configuration Options

#### Set country code

```php
$saleRequest->setCountryCode('BG'); // Set the country code (2-letter ISO code)
```

#### Set merchant GMT timezone

```php
$saleRequest->setMerchantGMT('+02'); // Set the merchant's timezone offset
```

#### Set merchant name

```php
$saleRequest->setMerchantName('My Company Ltd.'); // Set the merchant's name
```

#### Set notification email

```php
$saleRequest->setEmailAddress('notification@mycompany.com'); // Set notification email address
```

#### Set 'AD.CUST_BOR_ORDER_ID' field

```php
$saleRequest->setAdCustBorOrderId('ORDER123456'); // Set identifier for the bank's financial files
```

### Advanced Transaction Types

#### Pre-authorisation

You can send pre-authorisation requests:

```php
use VenelinIliev\Borica3ds\PreAuthorisationRequest;

$preAuthorisationRequest = (new PreAuthorisationRequest())
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('test pre-authorisation')
    ->setMerchantUrl('https://test.com') // optional
    ->setTerminalID('<TID - V*******>')
    ->setMerchantId('<MID - 15 chars>')
    ->setPrivateKey('\<path to certificate.key>', '<password>')
    ->setMInfo(array( // Mandatory cardholderName and ( email or MobilePhone )
        'email'=>'user@sample.com',
        'cardholderName'=>'CARDHOLDER NAME', // Max 45 chars
        'mobilePhone'=> array(
            'cc'=>'359', // Country code
            'subscriber'=>'8939999888', // Subscriber number
        ),
    ))
    ->setPrivateKeyPassword('test');

$formHtml = $preAuthorisationRequest->generateForm(); // only generate hidden html form with filled inputs
// OR
$preAuthorisationRequest->send(); // generate and send form with js
```

#### Pre-authorisation completion

After successful pre-authorisation, you can complete the transaction within 30 days:

```php
use VenelinIliev\Borica3ds\PreAuthorisationCompletionRequest;

$response = (new PreAuthorisationCompletionRequest())
    //->inDevelopment()
    ->setPrivateKey('\<path to certificate.key>', '<password>')
    ->setPublicKey('<path to public certificate.cer>')
    ->setTerminalID('<TID - V*******>')
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('pre-authorisation completion')
    ->setMerchantId('<MID - 15 chars>')
    ->setRrn('<RRN - Original transaction reference>')
    ->setIntRef('<INT_REF - Internal reference>')
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
    ->send();

$isSuccessful = $response->getVerifiedData('ACTION') === \VenelinIliev\Borica3ds\Enums\Action::SUCCESS &&
    $response->isSuccessful();
```

#### Pre-authorisation reversal

You can reverse a pre-authorisation:

```php
use VenelinIliev\Borica3ds\PreAuthorisationReversalRequest;

$response = (new PreAuthorisationReversalRequest())
    //->inDevelopment()
    ->setPrivateKey('\<path to certificate.key>', '<password>')
    ->setPublicKey('<path to public certificate.cer>')
    ->setTerminalID('<TID - V*******>')
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('pre-authorisation reversal')
    ->setMerchantId('<MID - 15 chars>')
    ->setRrn('<RRN - Original transaction reference>')
    ->setIntRef('<INT_REF - Internal reference>')
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED
    ->send();

$isSuccessful = $response->getVerifiedData('ACTION') === \VenelinIliev\Borica3ds\Enums\Action::SUCCESS &&
    $response->isSuccessful();
```

#### Transaction status check

Check the status of a transaction:

```php
use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\StatusCheckRequest;

$statusCheckRequest = (new StatusCheckRequest())
    //->inDevelopment()
    ->setPrivateKey('\<path to certificate.key>', '<password>')
    ->setPublicKey('<path to public certificate.cer>')
    ->setTerminalID('<TID - V*******>')
    ->setOrder('<order>')
    ->setOriginalTransactionType(TransactionType::SALE()) // transaction type
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED

//send to borica
$statusCheckResponse = $statusCheckRequest->send();

// get data from borica response
$verifiedResponseData = $statusCheckResponse->getResponseData();

// get field from borica response
$statusCheckResponse->getVerifiedData('<field from response. ex: ACTION');
```

#### Reversal request

Reverse a completed transaction:

```php
use VenelinIliev\Borica3ds\ReversalRequest;

$reversalRequest = (new ReversalRequest())
    //->inDevelopment()
    ->setPrivateKey('\<path to certificate.key>', '<password>')
    ->setPublicKey('<path to public certificate.cer>')
    ->setTerminalID('<TID - V*******>')
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('reversal')
    ->setMerchantId('<MID - 15 chars>')
    ->setRrn('<RRN - Original transaction reference>')
    ->setIntRef('<INT_REF - Internal reference>')
    //->setSigningSchemaMacGeneral(); // use MAC_GENERAL
    //->setSigningSchemaMacExtended(); // use MAC_EXTENDED
    //->setSigningSchemaMacAdvanced(); // use MAC_ADVANCED

//send reversal request to borica
$reversalRequestResponse = $reversalRequest->send();

// get data from borica reversal response
$verifiedResponseData = $reversalRequestResponse->getResponseData();

// get field from borica reversal response
$reversalRequestResponse->getVerifiedData('STATUSMSG');
```

### Determine Response Type

You can use the `determineResponse()` method to automatically get the correct response instance:

```php
use VenelinIliev\Borica3ds\Response;

$boricaResponse = Response::determineResponse(); // Will auto-detect the response type from $_POST data
// OR
$boricaResponse = Response::determineResponse($customData); // Process with custom data array

// The returned response object will be the correct type based on the transaction type
// and can be used with all the standard response methods like isSuccessful(), getVerifiedData(), etc.
```

### Response Code and Action Enums

The library includes enums for response codes and actions:

```php
// Action constants
use VenelinIliev\Borica3ds\Enums\Action;

// Action values:
// Action::SUCCESS = '0' - Transaction successfully completed
// Action::DUPLICATE = '1' - Duplicate transaction found
// Action::DECLINE = '2' - Transaction declined
// Action::PROCESSING_ERROR = '3' - Transaction processing error
// Action::DUPLICATE_DECLINE = '6' - Duplicate, declined transaction
// Action::DUPLICATE_AUTHENTICATION_ERROR = '7' - Duplicate, authentication error
// Action::DUPLICATE_NO_RESPONSE = '8' - Duplicate, no response
// Action::SOFT_DECLINE = '21' - Soft decline

// TransactionType values:
// TransactionType::SALE = 1
// TransactionType::PRE_AUTHORISATION = 12
// TransactionType::PRE_AUTHORISATION_COMPLETION = 21
// TransactionType::PRE_AUTHORISATION_REVERSAL = 22
// TransactionType::TRANSACTION_STATUS_CHECK = 90
// TransactionType::REVERSAL = 24
```

### Credit cards for testing

#### Cards

| Тип на карта | Номер на карта (PAN) | Реакция на APGW / Reponse code                                                          | Response Code Описание          | Изисква тестов ACS    |
|--------------|----------------------|-----------------------------------------------------------------------------------------|---------------------------------|-----------------------|
| Mastecard    | 5100770000000022     | Response code = 00                                                                      | Successfully completed          | Не                    |
| Mastecard    | 5555000000070019     | Response code = 04                                                                      | Pick Up                         | Не                    |
| Mastecard    | 5555000000070027     | Системата се забавя 30 сек. за авторизация, Response code = 13                          | Invalid amount                  | Не                    |
| Mastecard    | 5555000000070035     | Timeout, Response code = 91                                                             | Issuer or switch is inoperative | Не                    |
| Visa         | 4341792000000044     | Response code = 00 Това е пълен тест с автентификация от тестов Visa ACS и авторизация. | Successfully Completed          | Да, паролата е 111111 |

#### Карти, за които се получава съответен резултат при транзакция според сумата

| Тип на карта | Номер на карта (PAN) | Реакция на APGW / RC                     | Изисква тестов ACS    |     |
|--------------|----------------------|------------------------------------------|-----------------------|-----|
| Visa         | 4010119999999897     | Зависи от сумата. Виж таблицата по-долу. | Не                    |     |
| Mastecard    | 5100789999999895     |                                          | Да, паролата е 111111 |     |

| Сума от | Сума до | Реакция на APGW / Reponse code | RC Описание                     | Коментар              |
|---------|---------|--------------------------------|---------------------------------|-----------------------|
| 1.00    | 1.99    | 01                             | Refer to card issuer            |                       |
| 2.00    | 2.99    | 04                             | Pick Up                         |                       |
| 3.00    | 3.99    | 05                             | Do not Honour                   |                       |
| 4.00    | 4.99    | 13                             | Invalid amount                  | Response after 30 sec |
| 5.00    | 5.99    | 30                             | Format error                    |                       |
| 6.00    | 6.99    | 91                             | Issuer or switch is inoperative |                       |
| 7.00    | 7.99    | 96                             | System Malfunction              |                       |
| 8.00    | 8.99    |                                | Timeout                         |                       |
| 30.00   | 40.00   | 01                             | Refer to card issuer            |                       |
| 50.00   | 70.00   | 04                             | Pick Up                         |                       |
| 80.00   | 90.00   | 05                             | Do not Honour                   |                       |
| 100.00  | 110.00  | 13                             | Invalid amount                  | Response after 30 sec |
| 120.00  | 130.00  | 30                             | Format error                    |                       |
| 140.00  | 150.00  | 91                             | Issuer or switch is inoperative |                       |
| 160.00  | 170.00  | 96                             | System Malfunction              |                       |
| 180.00  | 190.00  |                                | Timeout                         |                       |

## Todo

- laravel integration
