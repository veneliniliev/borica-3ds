## Table of contents

- [\VenelinIliev\Borica3ds\SaleResponse](#class-venelinilievborica3dssaleresponse)
- [\VenelinIliev\Borica3ds\Request (abstract)](#class-venelinilievborica3dsrequest-abstract)
- [\VenelinIliev\Borica3ds\Base (abstract)](#class-venelinilievborica3dsbase-abstract)
- [\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)
- [\VenelinIliev\Borica3ds\Response (abstract)](#class-venelinilievborica3dsresponse-abstract)
- [\VenelinIliev\Borica3ds\RequestInterface (interface)](#interface-venelinilievborica3dsrequestinterface)
- [\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)
- [\VenelinIliev\Borica3ds\Exceptions\DataMissingException](#class-venelinilievborica3dsexceptionsdatamissingexception)
- [\VenelinIliev\Borica3ds\Exceptions\ParameterValidationException](#class-venelinilievborica3dsexceptionsparametervalidationexception)
- [\VenelinIliev\Borica3ds\Exceptions\SignatureException](#class-venelinilievborica3dsexceptionssignatureexception)

<hr />

### Class: \VenelinIliev\Borica3ds\SaleResponse

> Class Sale

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getResponseCode()</strong> : <em>string</em><br /><em>Get response code - value of 'RC' field</em> |
| public | <strong>getResponseData()</strong> : <em>array</em><br /><em>Get response data</em> |
| public | <strong>isSuccessful()</strong> : <em>boolean</em><br /><em>Is success payment?</em> |
| public | <strong>setResponseData(</strong><em>array</em> <strong>$responseData</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\SaleResponse](#class-venelinilievborica3dssaleresponse)</em><br /><em>Set response data</em> |
| protected | <strong>getVerifiedData(</strong><em>string</em> <strong>$key</strong>)</strong> : <em>mixed</em><br /><em>Get verified data by key</em> |

*This class extends [\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)*

<hr />

### Class: \VenelinIliev\Borica3ds\Request (abstract)

> Borica request

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getAmount()</strong> : <em>float/null</em><br /><em>Get amount</em> |
| public | <strong>getBackRefUrl()</strong> : <em>string</em><br /><em>Get back ref url</em> |
| public | <strong>getCurrency()</strong> : <em>string</em><br /><em>Get currency</em> |
| public | <strong>getDescription()</strong> : <em>mixed</em><br /><em>Get description</em> |
| public | <strong>getOrder()</strong> : <em>mixed</em><br /><em>Get order</em> |
| public | <strong>getSignatureTimestamp()</strong> : <em>string</em><br /><em>Get signature timestamp</em> |
| public | <strong>getTransactionType()</strong> : <em>[\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)</em><br /><em>Get transaction type</em> |
| public | <strong>setAmount(</strong><em>string/float/integer</em> <strong>$amount</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set amount</em> |
| public | <strong>setBackRefUrl(</strong><em>string</em> <strong>$backRefUrl</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set back ref url</em> |
| public | <strong>setCurrency(</strong><em>string</em> <strong>$currency</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set currency</em> |
| public | <strong>setDescription(</strong><em>string</em> <strong>$description</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set description</em> |
| public | <strong>setOrder(</strong><em>mixed</em> <strong>$order</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set order</em> |
| public | <strong>setSignatureTimestamp(</strong><em>string/null</em> <strong>$signatureTimestamp=null</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set signature timestamp</em> |
| public | <strong>setTransactionType(</strong><em>[\VenelinIliev\Borica3ds\Enums\TransactionType](#class-venelinilievborica3dsenumstransactiontype)</em> <strong>$transactionType</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)</em><br /><em>Set transaction type</em> |

*This class extends [\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)*

<hr />

### Class: \VenelinIliev\Borica3ds\Base (abstract)

> Borica base

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getEnvironmentUrl()</strong> : <em>string</em> |
| public | <strong>getMerchantId()</strong> : <em>mixed</em><br /><em>Get merchant ID</em> |
| public | <strong>getPrivateKey()</strong> : <em>string</em><br /><em>Get private key</em> |
| public | <strong>getPrivateKeyPassword()</strong> : <em>string/null</em><br /><em>Get private key password</em> |
| public | <strong>getTerminalID()</strong> : <em>mixed</em><br /><em>Get terminal ID</em> |
| public | <strong>inDevelopment()</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch to development mode</em> |
| public | <strong>inProduction()</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch to production mode</em> |
| public | <strong>isDevelopment()</strong> : <em>boolean</em> |
| public | <strong>isProduction()</strong> : <em>boolean</em> |
| public | <strong>setEnvironment(</strong><em>bool/boolean</em> <strong>$production=true</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Switch environment to development/production</em> |
| public | <strong>setMerchantId(</strong><em>mixed</em> <strong>$merchantId</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set merchant ID</em> |
| public | <strong>setPrivateKey(</strong><em>string</em> <strong>$privateKeyPath</strong>, <em>string/null</em> <strong>$password=null</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set private key</em> |
| public | <strong>setPrivateKeyPassword(</strong><em>string/null</em> <strong>$privateKeyPassword</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set private key password</em> |
| public | <strong>setTerminalID(</strong><em>string</em> <strong>$terminalID</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)</em><br /><em>Set terminal ID</em> |
| protected | <strong>getPrivateSignature(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>string</em><br /><em>Generate signature of data with private key</em> |
| protected | <strong>getSignatureSource(</strong><em>array</em> <strong>$data</strong>, <em>bool/boolean</em> <strong>$isResponse=false</strong>)</strong> : <em>string</em><br /><em>Generate signature source</em> |

<hr />

### Class: \VenelinIliev\Borica3ds\Sale

> Class Sale

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct()</strong> : <em>void</em><br /><em>Sale constructor.</em> |
| public | <strong>generateForm()</strong> : <em>string</em><br /><em>Generate HTML hidden form</em> |
| public | <strong>generateSignature()</strong> : <em>string</em><br /><em>Generate signature of data</em> |
| public | <strong>getAdCustBorOrderId()</strong> : <em>string</em><br /><em>Get 'AD.CUST_BOR_ORDER_ID' field</em> |
| public | <strong>getCountryCode()</strong> : <em>string</em><br /><em>Get country code</em> |
| public | <strong>getData()</strong> : <em>array</em><br /><em>Get data required for request to borica</em> |
| public | <strong>getEmailAddress()</strong> : <em>string</em><br /><em>Get notification email address</em> |
| public | <strong>getMerchantGMT()</strong> : <em>string/null</em><br /><em>Get merchant GMT</em> |
| public | <strong>getMerchantName()</strong> : <em>string</em> |
| public | <strong>getMerchantUrl()</strong> : <em>string</em><br /><em>Get merchant URL</em> |
| public | <strong>send()</strong> : <em>void</em><br /><em>Send to borica. Generate form and auto submit with JS.</em> |
| public | <strong>setAdCustBorOrderId(</strong><em>string</em> <strong>$adCustBorOrderId</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)</em><br /><em>Set 'AD.CUST_BOR_ORDER_ID' field</em> |
| public | <strong>setCountryCode(</strong><em>string</em> <strong>$countryCode</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)</em><br /><em>Set country code</em> |
| public | <strong>setEmailAddress(</strong><em>string</em> <strong>$emailAddress</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)</em><br /><em>Set notification email address</em> |
| public | <strong>setMerchantGMT(</strong><em>string</em> <strong>$merchantGMT</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)</em><br /><em>Set merchant GMT</em> |
| public | <strong>setMerchantName(</strong><em>string</em> <strong>$merchantName</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)</em> |
| public | <strong>setMerchantUrl(</strong><em>string</em> <strong>$merchantUrl</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)</em><br /><em>Set merchant URL</em> |
| public | <strong>setNonce(</strong><em>string</em> <strong>$nonce</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Sale](#class-venelinilievborica3dssale)</em> |
| public | <strong>validateRequiredParameters()</strong> : <em>void</em><br /><em>Validate required fields to post</em> |

*This class extends [\VenelinIliev\Borica3ds\Request](#class-venelinilievborica3dsrequest-abstract)*

*This class implements [\VenelinIliev\Borica3ds\RequestInterface](#interface-venelinilievborica3dsrequestinterface)*

<hr />

### Class: \VenelinIliev\Borica3ds\Response (abstract)

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getPublicKey()</strong> : <em>string</em><br /><em>Get public key</em> |
| public | <strong>setPublicKey(</strong><em>string</em> <strong>$publicKey</strong>)</strong> : <em>[\VenelinIliev\Borica3ds\Response](#class-venelinilievborica3dsresponse-abstract)</em><br /><em>Set public key</em> |
| protected | <strong>verifyPublicSignature(</strong><em>array</em> <strong>$data</strong>, <em>string</em> <strong>$publicSignature</strong>)</strong> : <em>void</em><br /><em>Verify data with public certificate</em> |

*This class extends [\VenelinIliev\Borica3ds\Base](#class-venelinilievborica3dsbase-abstract)*

<hr />

### Interface: \VenelinIliev\Borica3ds\RequestInterface

> Interface RequestInterface

| Visibility | Function |
|:-----------|:---------|
| public | <strong>generateForm()</strong> : <em>mixed</em><br /><em>Generate hidden html form without submit</em> |
| public | <strong>generateSignature()</strong> : <em>string</em><br /><em>Sign request</em> |
| public | <strong>getData()</strong> : <em>array</em><br /><em>Get data with post inputs</em> |
| public | <strong>send()</strong> : <em>void</em><br /><em>Generate html form and send request with js</em> |
| public | <strong>validateRequiredParameters()</strong> : <em>void</em><br /><em>Validate required data before sending</em> |

<hr />

### Class: \VenelinIliev\Borica3ds\Enums\TransactionType

> Class TransactionType

| Visibility | Function |
|:-----------|:---------|

*This class extends \MyCLabs\Enum\Enum*

*This class implements \JsonSerializable*

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

