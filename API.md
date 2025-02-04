## Table of contents

- [\VenelinIliev\Borica3ds\SaleResponse](#class-venelinilievborica3dssaleresponse)
- [\VenelinIliev\Borica3ds\Request (abstract)](#class-venelinilievborica3dsrequest-abstract)
- [\VenelinIliev\Borica3ds\ReversalRequest](#class-venelinilievborica3dsreversalrequest)
- [\VenelinIliev\Borica3ds\Base (abstract)](#class-venelinilievborica3dsbase-abstract)
- [\VenelinIliev\Borica3ds\StatusCheckResponse](#class-venelinilievborica3dsstatuscheckresponse)
- [\VenelinIliev\Borica3ds\Response (abstract)](#class-venelinilievborica3dsresponse-abstract)
- [\VenelinIliev\Borica3ds\StatusCheckRequest](#class-venelinilievborica3dsstatuscheckrequest)
- [\VenelinIliev\Borica3ds\SaleRequest](#class-venelinilievborica3dssalerequest)
- [\VenelinIliev\Borica3ds\ReversalResponse](#class-venelinilievborica3dsreversalresponse)
- [\VenelinIliev\Borica3ds\PreAuthorisationRequest](#class-venelinilievborica3dspreauthorisationrequest)
- [\VenelinIliev\Borica3ds\PreAuthorisationResponse](#class-venelinilievborica3dspreauthorisationresponse)
- [\VenelinIliev\Borica3ds\PreAuthorisationCompletionRequest](#class-venelinilievborica3dspreauthorisationcompletionrequest)
- [\VenelinIliev\Borica3ds\PreAuthorisationCompletionResponse](#class-venelinilievborica3dspreauthorisationcompletionresponse)
- [\VenelinIliev\Borica3ds\PreAuthorisationReversalRequest](#class-venelinilievborica3dspreauthorisationreversalrequest)
- [\VenelinIliev\Borica3ds\PreAuthorisationReversalResponse](#class-venelinilievborica3dspreauthorisationreversalresponse)
- [\VenelinIliev\Borica3ds\ResponseInterface (interface)](#interface-venelinilievborica3dsresponseinterface)
- [\VenelinIliev\Borica3ds\RequestInterface (interface)](#interface-venelinilievborica3dsrequestinterface)
- [\VenelinIliev\Borica3ds\Enums\Language](#class-venelinilievborica3dsenumslanguage)
- [\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)
- [\VenelinIliev\Borica3ds\Exceptions\SendingException](#class-venelinilievborica3dsexceptionssendingexception)
- [\VenelinIliev\Borica3ds\Exceptions\DataMissingException](#class-venelinilievborica3dsexceptionsdatamissingexception)
- [\VenelinIliev\Borica3ds\Exceptions\ParameterValidationException](#class-venelinilievborica3dsexceptionsparametervalidationexception)
- [\VenelinIliev\Borica3ds\Exceptions\SignatureException](#class-venelinilievborica3dsexceptionssignatureexception)

<hr />

### Class: \VenelinIliev\Borica3ds\SaleResponse

> Class Sale

| Visibility | Function |
|:-----------|:---------|


*This class extends [\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)*

*This class implements [\VenelinIliev\Borica3ds\ResponseInterface](#interface-venelinilievborica3dsresponseinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\Request (abstract)

> Borica request

| Visibility | Function                                                                                                                                                                                                                                                                                                                 |
|:-----------|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| protected  | <strong>generateAdCustBorOrderId()</strong> : <em>array</em><br /><em>Generate AD.CUST_BOR_ORDER_ID borica field</em>                                                                                                                                                                                                    |
| public     | <strong>generateForm()</strong> : <em>string/array</em><br /><em>Generate hidden html form without submit. Or return a array with request data.</em>                                                                                                                                                                     |
| public     | <strong>generateSignature()</strong> : <em>string</em><br /><em>Generate signature of data</em>                                                                                                                                                                                                                          |
| public     | <strong>getAdCustBorOrderId()</strong> : <em>string</em><br /><em>Get 'AD.CUST_BOR_ORDER_ID' field</em>                                                                                                                                                                                                                  |
| public     | <strong>getAmount()</strong> : <em>float/null</em><br /><em>Get amount</em>                                                                                                                                                                                                                                              |
| public     | <strong>getBackRefUrl()</strong> : <em>string</em><br /><em>Get back ref url</em>                                                                                                                                                                                                                                        |
| public     | <strong>getCountryCode()</strong> : <em>string</em><br /><em>Get country code</em>                                                                                                                                                                                                                                       |
| public     | <strong>getCurrency()</strong> : <em>string</em><br /><em>Get currency</em>                                                                                                                                                                                                                                              |
| public     | <strong>getDescription()</strong> : <em>mixed</em><br /><em>Get description</em>                                                                                                                                                                                                                                         |
| public     | <strong>getEmailAddress()</strong> : <em>string</em><br /><em>Get notification email address</em>                                                                                                                                                                                                                        |
| public     | <strong>getIntRef()</strong> : <em>string</em>                                                                                                                                                                                                                                                                           |
| public     | <strong>getLang()</strong> : <em>null/string</em><br /><em>Get language</em>                                                                                                                                                                                                                                             |
| public     | <strong>getMerchantGMT()</strong> : <em>string/null</em><br /><em>Get merchant GMT</em>                                                                                                                                                                                                                                  |
| public     | <strong>getMerchantName()</strong> : <em>string</em>                                                                                                                                                                                                                                                                     |
| public     | <strong>getMerchantUrl()</strong> : <em>string</em><br /><em>Get merchant URL</em>                                                                                                                                                                                                                                       |
| public     | <strong>getMInfo()</strong> : <em>string</em>                                                                                                                                                                                                                                                                            |
| public     | <strong>getNonce()</strong> : <em>string</em>                                                                                                                                                                                                                                                                            |
| public     | <strong>getOrder()</strong> : <em>mixed</em><br /><em>Get order</em>                                                                                                                                                                                                                                                     |
| public     | <strong>getRequestType()</strong> : <em>[VenelinIliev\Borica3ds\RequestTypes\RequestType](#class-venelinilievborica3dsrequesttypesrequesttype)</em><br /><em>Get request type</em>                                                                                                                                       |
| public     | <strong>getRrn()</strong> : <em>string</em>                                                                                                                                                                                                                                                                              |
| public     | <strong>getSignatureTimestamp()</strong> : <em>string</em><br /><em>Get signature timestamp</em>                                                                                                                                                                                                                         |
| public     | <strong>getTransactionType()</strong> : <em>[\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)</em><br /><em>Get transaction type</em>                                                                                                                                    |
| public     | <strong>send()</strong> : <em>void/string/[\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)</em><br /><em>Send data to borica</em>                                                                                                                                                       |
| public     | <strong>setAdCustBorOrderId(</strong><em>string</em> <strong>$adCustBorOrderId</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set 'AD.CUST_BOR_ORDER_ID' field</em>                                                                              |
| public     | <strong>setAmount(</strong><em>string/float/integer</em> <strong>$amount</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set amount</em>                                                                                                          |
| public     | <strong>setBackRefUrl(</strong><em>string</em> <strong>$backRefUrl</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set back ref url</em>                                                                                                          |
| public     | <strong>setCountryCode(</strong><em>string</em> <strong>$countryCode</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set country code</em>                                                                                                        |
| public     | <strong>setCurrency(</strong><em>string</em> <strong>$currency</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set currency</em>                                                                                                                  |
| public     | <strong>setDescription(</strong><em>string</em> <strong>$description</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set description</em>                                                                                                         |
| public     | <strong>setEmailAddress(</strong><em>string</em> <strong>$emailAddress</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set notification email address</em>                                                                                        |
| public     | <strong>setIntRef(</strong><em>string</em> <strong>$intRef</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set transaction internal reference.</em>                                                                                               |
| public     | <strong>setLang(</strong><em>null/string</em> <strong>$lang</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set language</em>                                                                                                                     |
| public     | <strong>setMerchantGMT(</strong><em>string</em> <strong>$merchantGMT</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set merchant GMT</em>                                                                                                        |
| public     | <strong>setMerchantName(</strong><em>string</em> <strong>$merchantName</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em>                                                                                                                                     |
| public     | <strong>setMerchantUrl(</strong><em>string</em> <strong>$merchantUrl</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set merchant URL</em>                                                                                                        |
| public     | <strong>setMInfo(</strong><em>array</em> <strong>$mInfo</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set transaction reference.</em>                                                                                                           |
| public     | <strong>setNonce(</strong><em>string</em> <strong>$nonce</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em>                                                                                                                                                   |
| public     | <strong>setOrder(</strong><em>mixed</em> <strong>$order</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set order</em>                                                                                                                            |
| public     | <strong>setRequestType(</strong><em>[VenelinIliev\Borica3ds\RequestTypes\RequestType](#class-venelinilievborica3dsrequesttypesrequesttype)</em> <strong>$requestType</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set request type</em>        |
| public     | <strong>setRrn(</strong><em>string</em> <strong>$rrn</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set transaction reference.</em>                                                                                                              |
| public     | <strong>setSignatureTimestamp(</strong><em>string/null</em> <strong>$signatureTimestamp=null</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set signature timestamp</em>                                                                         |
| public     | <strong>setTransactionType(</strong><em>[\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)</em> <strong>$transactionType</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set transaction type</em> |
| public     | <strong>validateRequiredParameters()</strong> : <em>void</em><br /><em>Validate required fields to post</em>                                                                                                                                                                                                             |

*This class extends [\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)*

<hr />

### Class: \VenelinIliev\Borica3ds\ReversalRequest

| Visibility | Function                                                                                                                                                      |
|:-----------|:--------------------------------------------------------------------------------------------------------------------------------------------------------------|
| public     | <strong>__construct()</strong> : <em>void</em><br /><em>ReversalRequest constructor.</em>                                                                     |
| public     | <strong>generateSignature()</strong> : <em>string</em><br /><em>Generate signature of data</em>                                                               |
| public     | <strong>getData()</strong> : <em>array</em>                                                                                                                   |
| public     | <strong>send()</strong> : <em>[\VenelinIliev\Borica3ds\ReversalResponse](#class-venelinilievborica3dsreversalresponse)</em><br /><em>Send data to borica</em> |
| public     | <strong>validateRequiredParameters()</strong> : <em>void</em><br /><em>Validate required fields to post</em>                                                  |

*This class extends [\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)*

*This class implements [\VenelinIliev\Borica3ds\RequestInterface](#interface-venelinilievborica3dsrequestinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\Base (abstract)

> Borica base

| Visibility | Function                                                                                                                                                                                                                                                           |
|:-----------|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| public     | <strong>getEnvironmentUrl()</strong> : <em>string</em>                                                                                                                                                                                                             |
| public     | <strong>getMerchantId()</strong> : <em>mixed</em><br /><em>Get merchant ID</em>                                                                                                                                                                                    |
| public     | <strong>getPrivateKey()</strong> : <em>string</em><br /><em>Get private key</em>                                                                                                                                                                                   |
| public     | <strong>getPrivateKeyPassword()</strong> : <em>string/null</em><br /><em>Get private key password</em>                                                                                                                                                             |
| public     | <strong>getPublicKey()</strong> : <em>string</em><br /><em>Get public key</em>                                                                                                                                                                                     |
| public     | <strong>getTerminalID()</strong> : <em>mixed</em><br /><em>Get terminal ID</em>                                                                                                                                                                                    |
| public     | <strong>inDevelopment()</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch to development mode</em>                                                                                                     |
| public     | <strong>inProduction()</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch to production mode</em>                                                                                                       |
| public     | <strong>isDevelopment()</strong> : <em>boolean</em>                                                                                                                                                                                                                |
| public     | <strong>isProduction()</strong> : <em>boolean</em>                                                                                                                                                                                                                 |
| public     | <strong>setEnvironment(</strong><em>bool/boolean</em> <strong>$production=true</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch environment to development/production</em>                  |
| public     | <strong>setMerchantId(</strong><em>mixed</em> <strong>$merchantId</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set merchant ID</em>                                                            |
| public     | <strong>setPrivateKey(</strong><em>string</em> <strong>$privateKeyPath</strong>, <em>string/null</em> <strong>$password=null</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set private key</em> |
| public     | <strong>setPrivateKeyPassword(</strong><em>string/null</em> <strong>$privateKeyPassword</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set private key password</em>                             |
| public     | <strong>setPublicKey(</strong><em>string</em> <strong>$publicKey</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set public key</em>                                                              |
| public     | <strong>setSigningSchemaMacAdvanced()</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch signing schema to MAC_ADVANCED</em>                                                                            |
| public     | <strong>setSigningSchemaMacExtended()</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch signing schema to MAC_EXTENDED</em>                                                                            |
| public     | <strong>setSigningSchemaMacGeneral()</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch signing schema to MAC_EXTENDED</em>                                                                             |
| public     | <strong>setTerminalID(</strong><em>string</em> <strong>$terminalID</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set terminal ID</em>                                                           |
| protected  | <strong>getPrivateSignature(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>string</em><br /><em>Generate signature of data with private key</em>                                                                                                   |
| protected  | <strong>getSignatureSource(</strong><em>array</em> <strong>$data</strong>, <em>bool/boolean</em> <strong>$isResponse=false</strong>)</strong> : <em>string</em><br /><em>Generate signature source</em>                                                            |
| protected  | <strong>isSigningSchemaMacAdvanced()</strong> : <em>boolean</em><br /><em>Is MAC_ADVANCE signing schema?</em>                                                                                                                                                      |
| protected  | <strong>isSigningSchemaMacGeneral()</strong> : <em>boolean</em><br /><em>Is MAC_ADVANCE signing schema?</em>                                                                                                                                                       |

<hr />

### Class: \VenelinIliev\Borica3ds\StatusCheckResponse

| Visibility | Function |
|:-----------|:---------|

*This class extends [\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)*

*This class implements [\VenelinIliev\Borica3ds\ResponseInterface](#interface-venelinilievborica3dsresponseinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\Response (abstract)

| Visibility | Function                                                                                                                                                                                                                                      |
|:-----------|:----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| public     | <strong>determineResponse(</strong><em>null/array</em> <strong>$responseData = null</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)</em><br /><em>Determine the repose class.</em> |
| public     | <strong>getResponseData(</strong><em>bool/boolean</em> <strong>$verify=true</strong>)</strong> : <em>array</em><br /><em>Get response data</em>                                                                                               |
| public     | <strong>getVerifiedData(</strong><em>string</em> <strong>$key</strong>)</strong> : <em>mixed</em><br /><em>Get verified data by key</em>                                                                                                      |
| public     | <strong>getResponseCode()</strong> : <em>string</em><br /><em>Get response code - value of 'RC' field</em>                                                                                                                                    |
| public     | <strong>isSuccessful()</strong> : <em>boolean</em><br /><em>Is success payment?</em>                                                                                                                                                          |
| public     | <strong>setResponseData(</strong><em>array</em> <strong>$responseData</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)</em><br /><em>Set response data</em>                         |
| protected  | <strong>getVerifyingFields()</strong> : <em>string[]</em>                                                                                                                                                                                     |
| protected  | <strong>verifyData()</strong> : <em>void</em><br /><em>Verify data with public certificate</em>                                                                                                                                               |
| protected  | <strong>verifyPublicSignature(</strong><em>array</em> <strong>$data</strong>, <em>string</em> <strong>$publicSignature</strong>)</strong> : <em>void</em><br /><em>Verify data with public certificate</em>                                   |


*This class extends [\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)*

<hr />

### Class: \VenelinIliev\Borica3ds\StatusCheckRequest

| Visibility | Function                                                                                                                                                                                                                                                                                                                                        |
|:-----------|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| public     | <strong>__construct()</strong> : <em>void</em><br /><em>StatusCheckRequest constructor.</em>                                                                                                                                                                                                                                                    |
| public     | <strong>getData()</strong> : <em>array</em>                                                                                                                                                                                                                                                                                                     |
| public     | <strong>getOriginalTransactionType()</strong> : <em>[\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)</em>                                                                                                                                                                                      |
| public     | <strong>send()</strong> : <em>[\VenelinIliev\Borica3ds\StatusCheckResponse](#class-venelinilievborica3dsstatuscheckresponse)</em><br /><em>Send data to borica</em>                                                                                                                                                                             |
| public     | <strong>setOriginalTransactionType(</strong><em>[\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)</em> <strong>$tranType</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\StatusCheckRequest](#class-venelinilievborica3dsstatuscheckrequest)</em><br /><em>Set original transaction type</em> |
| public     | <strong>validateRequiredParameters()</strong> : <em>void</em><br /><em>Validate required fields to post</em>                                                                                                                                                                                                                                    |

*This class extends [\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)*

*This class implements [\VenelinIliev\Borica3ds\RequestInterface](#interface-venelinilievborica3dsrequestinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\SaleRequest

> Class Sale

| Visibility | Function                                                                                                     |
|:-----------|:-------------------------------------------------------------------------------------------------------------|
| public     | <strong>__construct()</strong> : <em>void</em><br /><em>SaleRequest constructor.</em>                        |
| public     | <strong>getData()</strong> : <em>array</em><br /><em>Get data required for request to borica</em>            |
| public     | <strong>send()</strong> : <em>void</em><br /><em>Send to borica. Generate form and auto submit with JS.</em> |

*This class extends [\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)*

*This class implements [\VenelinIliev\Borica3ds\RequestInterface](#interface-venelinilievborica3dsrequestinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\ReversalResponse

> Class ReversalResponse

| Visibility | Function |
|:-----------|:---------|

*This class extends [\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)*

*This class implements [\VenelinIliev\Borica3ds\ResponseInterface](#interface-venelinilievborica3dsresponseinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\PreAuthorisationRequest

> Class PreAuthorisationRequest

| Visibility | Function                                                                                                     |
|:-----------|:-------------------------------------------------------------------------------------------------------------|
| public     | <strong>__construct()</strong> : <em>void</em><br /><em>PreAuthorisationRequest constructor.</em>            |
| public     | <strong>getData()</strong> : <em>array</em><br /><em>Get data required for request to borica</em>            |
| public     | <strong>send()</strong> : <em>void</em><br /><em>Send to borica. Generate form and auto submit with JS.</em> |

*This class extends [\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)*

*This class implements [\VenelinIliev\Borica3ds\RequestInterface](#interface-venelinilievborica3dsrequestinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\PreAuthorisationResponse

> Class PreAuthorisationResponse

| Visibility | Function |
|:-----------|:---------|

*This class extends [\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)*

*This class implements [\VenelinIliev\Borica3ds\ResponseInterface](#interface-venelinilievborica3dsresponseinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\PreAuthorisationCompletionRequest

> Class PreAuthorisationCompletionRequest

| Visibility | Function                                                                                                     |
|:-----------|:-------------------------------------------------------------------------------------------------------------|
| public     | <strong>__construct()</strong> : <em>void</em><br /><em>PreAuthorisationCompletionRequest constructor.</em>  |
| public     | <strong>getData()</strong> : <em>array</em><br /><em>Get data required for request to borica</em>            |
| public     | <strong>send()</strong> : <em>void</em><br /><em>Send to borica. Generate form and auto submit with JS.</em> |

*This class extends [\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)*

*This class implements [\VenelinIliev\Borica3ds\RequestInterface](#interface-venelinilievborica3dsrequestinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\PreAuthorisationCompletionResponse

> Class PreAuthorisationCompletionResponse

| Visibility | Function |
|:-----------|:---------|

*This class extends [\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)*

*This class implements [\VenelinIliev\Borica3ds\ResponseInterface](#interface-venelinilievborica3dsresponseinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\PreAuthorisationReversalRequest

> Class PreAuthorisationReversalRequest

| Visibility | Function                                                                                                     |
|:-----------|:-------------------------------------------------------------------------------------------------------------|
| public     | <strong>__construct()</strong> : <em>void</em><br /><em>PreAuthorisationReversalRequest constructor.</em>  |
| public     | <strong>getData()</strong> : <em>array</em><br /><em>Get data required for request to borica</em>            |
| public     | <strong>send()</strong> : <em>void</em><br /><em>Send to borica. Generate form and auto submit with JS.</em> |

*This class extends [\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)*

*This class implements [\VenelinIliev\Borica3ds\RequestInterface](#interface-venelinilievborica3dsrequestinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\PreAuthorisationReversalResponse

> Class PreAuthorisationReversalResponse

| Visibility | Function |
|:-----------|:---------|

*This class extends [\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)*

*This class implements [\VenelinIliev\Borica3ds\ResponseInterface](#interface-venelinilievborica3dsresponseinterface)*

<hr />

### Interface: \VenelinIliev\Borica3ds\ResponseInterface

> Interface ResponseInterface

| Visibility | Function                                                                                          |
|:-----------|:--------------------------------------------------------------------------------------------------|
| public     | <strong>getResponseData()</strong> : <em>array</em>                                               |
| public     | <strong>getVerifiedData(</strong><em>string</em> <strong>$key</strong>)</strong> : <em>mixed</em> |

<hr />

### Interface: \VenelinIliev\Borica3ds\RequestInterface

> Interface RequestInterface

| Visibility | Function                                                                                                                                                                            |
|:-----------|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| public     | <strong>generateForm()</strong> : <em>mixed</em><br /><em>Generate hidden html form without submit</em>                                                                             |
| public     | <strong>generateSignature()</strong> : <em>string</em><br /><em>Sign request</em>                                                                                                   |
| public     | <strong>getData()</strong> : <em>array</em><br /><em>Get data with post inputs</em>                                                                                                 |
| public     | <strong>send()</strong> : <em>void/[\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)</em><br /><em>Generate html form and send request with js</em> |
| public     | <strong>validateRequiredParameters()</strong> : <em>void</em><br /><em>Validate required data before sending</em>                                                                   |

<hr />

### Class: \VenelinIliev\Borica3ds\Enums\Language

> Class Language

| Visibility | Function |
|:-----------|:---------|

*This class extends \MyCLabs\Enum\Enum*

*This class implements \JsonSerializable*

<hr />

### Class: \VenelinIliev\Borica3ds\Enums\TransactionType

> Class TransactionType

| Visibility | Function |
|:-----------|:---------|

*This class extends \MyCLabs\Enum\Enum*

*This class implements \JsonSerializable*

<hr />

### Class: \VenelinIliev\Borica3ds\Exceptions\SendingException

| Visibility | Function |
|:-----------|:---------|

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \VenelinIliev\Borica3ds\Exceptions\DataMissingException

| Visibility | Function |
|:-----------|:---------|

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \VenelinIliev\Borica3ds\Exceptions\ParameterValidationException

| Visibility | Function |
|:-----------|:---------|

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \VenelinIliev\Borica3ds\Exceptions\SignatureException

| Visibility | Function |
|:-----------|:---------|

*This class extends \Exception*

*This class implements \Throwable*

