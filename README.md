# odeo-php-sdk

[![Latest Stable Version](https://poser.pugx.org/febrianjiuwira/odeo-php-sdk/v/stable)](https://packagist.org/packages/febrianjiuwira/odeo-php-sdk)
[![Total Downloads](https://poser.pugx.org/febrianjiuwira/odeo-php-sdk/downloads)](https://packagist.org/packages/febrianjiuwira/odeo-php-sdk)
[![Latest Unstable Version](https://poser.pugx.org/febrianjiuwira/odeo-php-sdk/v/unstable)](https://packagist.org/packages/febrianjiuwira/odeo-php-sdk)
[![License](https://poser.pugx.org/febrianjiuwira/odeo-php-sdk/license)](https://packagist.org/packages/febrianjiuwira/odeo-php-sdk)

PHP library for calling https://api.v2.odeo.co.id/ API using GuzzleHttp.

Just install the package to your PHP Project and you're ready to go!

# Dependencies
* [Guzzle](http://docs.guzzlephp.org/en/stable/quickstart.html)

## Development
### Quick Start Guide
1. Install the package with Composer: `composer require odeoteknologi/odeo-php-sdk` 
2. Use the service you need in your application class - I.e. you are using the Disbursement service (*assuming you're using PSR-4*):
 ```php
use OdeoApi\Services\Disbursement;
```
3. Create a new instance of the class (`Disbursement`):
```php
$disbursement = new Disbursement();
```
4. Set environment:
```php
$disbursement->production();
```
5. Set Api Credentials:
```php
$disbursement->setCredentials($clientId, $clientSecret, $signingKey);
```
6. Refresh/set `accessToken`:
```php
$accessToken = $disbursement->refreshAccessToken()['access_token'];
$disbursement->setAccessToken($accessToken);
```
7. Use one of the class method to query the API - this example will request the bank list:
```php
$banks = $disbursement->bankList();
```
Tips: all of the API response has been formatted into associative array.
```php
// extracting bank id
$bankId = $banks['banks'][0]['bank_id'];
```

## Usage
### OdeoApi\OdeoApi
`OdeoApi` class provides the functions that is needed to configure your API calls and signature generation.
```php
// initialize OdeoApi class
$odeo = new OdeoApi();

// set API credentials
$odeo->setCredentials($clientId, $clientSecret, $signingKey);

// set API call to odeo production environment
$odeo->production();

// set API call to odeo staging/development environment
$odeo->staging();

// request API access token, this also calls setAccessToken
// you should store the access token in your system and refresh when it expires
$accessToken = $odeo->refreshAccessToken()['access_token'];

// set access token that will be used to create API calls
$odeo->setAccessToken($accessToken);

// request Disbursements Bank List API using createRequest
$odeo->createRequest('GET', '/dg/v1/banks');

// comparing and proccessing signature from your callbacks
$isValid = $odeo->isValidSignature($signatureToCompare, $method, $path, $timestamp, $body);
if ($isValid) {
  // ... code when signature is valid
} else {
  // ... code when signature is not valid
}
```

### OdeoApi\Services\Disbursement
`Disbursement` class extends `OdeoApi` class and simplify clients request for calling Disbursement API services. You'll be able to use some of `OdeoApi` method such as `requestToken` to ease your development.
```php
$disbursement = new Disbursement();
$disbursement->staging();
$disbursement->setCredentials($clientId, $clientSecret, $signingKey);

// request /dg/v1/bank-account-inquiry API
$disbursement->bankAccountInquiry($accountNo, $bankId, $customerName, $withValidation);

// request /dg/v1/banks API
$disbursement->bankList();

// request ​/dg​/v1​/disbursements API
$disbursement->executeDisbursement($accountNo, $amount, $bankId, $customerName, $referenceId, $description);

// request /dg/v1/disbursements/reference-id/{reference_id} API
$disbursement->checkDisbursementByReferenceId($referenceId);

// request /dg/v1/disbursements/{disbursement_id} API
$disbursement->checkDisbursementByDisbursementId($disbursementId);

// request /cash/me/balance API
$disbursement->checkBalance();
```

### OdeoApi\Services\PaymentGateway
Same as `Disbursement` class, `PaymentGateway` class also extends `OdeoApi` class.
 
```php
$paymentGateway = new PaymentGateway();
$paymentGateway->staging();
$paymentGateway->setCredentials($clientId, $clientSecret, $signingKey);

// request /pg/v1/payment/reference-id/{reference_id} API
$paymentGateway->checkPaymentByReferenceId($referenceId);

// request /pg/v1/payment/{payment_id} API
$paymentGateway->checkPaymentByPaymentId($paymentId);
```

### Examples
See `examples`. 