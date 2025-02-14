# PHP Borica EMV 3DS

## Requirements

- PHP >= 5.6 (including 8.2)
- ext-mbstring
- ext-openssl
- ext-curl
- ext-json

## Installation

| version | Supported signing schemas                       | Default signing schema |
|---------|-------------------------------------------------|------------------------|
| ^2.0    | MAC_EXTENDED <br/>MAC_ADVANCED <br/>MAC_GENERAL | **MAC_GENERAL**       
| ^1.0    | MAC_EXTENDED <br/>MAC_ADVANCED                     | **MAC_ADVANCED**       

```shell script
composer require veneliniliev/borica-3ds
```

For more methods, read [api documentation](API.md).

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

```php
use VenelinIliev\Borica3ds\SaleResponse;
// ....
$isSuccessfulPayment = (new SaleResponse())
            ->setPublicKey('<path to public certificate.cer>')
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
               ->setPublicKey('<path to public certificate.cer>')
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