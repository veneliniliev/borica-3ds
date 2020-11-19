# PHP Borica EMV 3DS

[![Build Status](https://travis-ci.org/veneliniliev/borica-3ds.svg?branch=master)](https://travis-ci.org/veneliniliev/borica-3ds)

## Requirements

- PHP >= 5.6

## Installation

```shell script
composer require veneliniliev/borica-3ds
```

For more methods, read [api documentation](API.md).

## Certificates

### Generate private key

```shell script
openssl genrsa -out real.key -aes256 2048
openssl genrsa -out test.key -aes256 2048
```

### Generate CSR

**IMPORTANT**: in `Organizational Unit Name (eg, section)` enter your  terminal ID and in `Common Name (eg, fully qualified host name)` enter your domain name.

```shell script
openssl req -new -key real.key -out real.csr
openssl req -new -key test.key -out test.csr
```

Имената на файловете се създават по следната конвенция: **VNNNNNNN_YYYYMMDD_T**, където:
- **VNNNNNNN** – TID на терминала, предоставен от Финансовата Институция
- **YYYYMMDD** – дата на заявка
- **T** – тип на искания сертификат, значения – **D** – за development среда, **Р** – за продукционна среда

## Usage

### Sale request

````php
use VenelinIliev\Borica3ds\Sale;
// ...
$saleRequest = (new Sale())
    ->setAmount(123.32)
    ->setOrder(123456)
    ->setDescription('test')
    ->setMerchantUrl('https://test.com') // optional
    ->setBackRefUrl('https://test.com/back-ref-url') // optional / required for development
    ->setTerminalID('<TID - V*******>')
    ->setMerchantId('<MID - 15 chars>')
    ->setPrivateKey('\<path to certificate.key>', '<password / or use method from bottom>')
    ->setPrivateKeyPassword('test');

$formHtml = $saleRequest->generateForm(); // only generate hidden html form with filled inputs 
// OR
$saleRequest->send(); // generate and send form with js 
````

### Sale response

Catch response from borica on `BACKREF` url (*$saleRequest->setBackRefUrl('\<url>')*)

```php
use VenelinIliev\Borica3ds\SaleResponse;
// ....
$saleResponse = (new SaleResponse())
            ->setPublicKey('<path to public certificate.cer>')
            ->setResponseData($_POST) //Set POST data from borica response
            ->isSuccess();
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

#### Get response code

```php
$saleResponse->getResponseCode();
```

Response codes table

|Response Code (RC)|RC DESCRIPTION |    
|------------------|---------------|   
|00                | Sucessfull    |
|                  | => Timeout |
|"01"              | Refer to card issuer |
|"04"              | Pick Up |
|"05"              | Do not Honour |
|"13"              | Invalid amount |
|"30"              | Format error |
|"65"              | Soft Decline |
|"91"              | Issuer or switch is inoperative |
|"96"              | System Malfunction |    

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


| Тип на карта | Номер на карта (PAN) | Реакция на APGW / RC                     | Изисква тестов ACS    |   |
|--------------|----------------------|------------------------------------------|-----------------------|---|
| Visa         | 4010119999999897     | Зависи от сумата. Виж таблицата по-долу. | Не                    |   |
| Mastecard    | 5100789999999895     |                                          | Да, паролата е 111111 |   |

| Сума от | Сума до | Реакция на APGW / Reponse code | RC Описание                     | Коментар              |
|---------|---------|--------------------------------|---------------------------------|-----------------------|
|    1.00 |    1.99 |                             01 | Refer to card issuer            |                       |
|    2.00 |    2.99 |                             04 | Pick Up                         |                       |
|    3.00 |    3.99 |                             05 | Do not Honour                   |                       |
|    4.00 |    4.99 |                             13 | Invalid amount                  | Response after 30 sec |
|    5.00 |    5.99 |                             30 | Format error                    |                       |
|    6.00 |    6.99 |                             91 | Issuer or switch is inoperative |                       |
|    7.00 |    7.99 |                             96 | System Malfunction              |                       |
|    8.00 |    8.99 |                                | Timeout                         |                       |
|   30.00 |   40.00 |                             01 | Refer to card issuer            |                       |
|   50.00 |   70.00 |                             04 | Pick Up                         |                       |
|   80.00 |   90.00 |                             05 | Do not Honour                   |                       |
|  100.00 |  110.00 |                             13 | Invalid amount                  | Response after 30 sec |
|  120.00 |  130.00 |                             30 | Format error                    |                       |
|  140.00 |  150.00 |                             91 | Issuer or switch is inoperative |                       |
|  160.00 |  170.00 |                             96 | System Malfunction              |                       |
|  180.00 |  190.00 |                                | Timeout                         |                       |

## Todo 
 - laravel integration