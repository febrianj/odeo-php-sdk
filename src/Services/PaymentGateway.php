<?php
/**
 * Created by PhpStorm.
 * User: febrianjiuwira
 * Date: 26/11/19
 * Time: 11.01
 */

namespace OdeoApi\Services;

use OdeoApi\OdeoApi;

class PaymentGateway extends OdeoApi {

  public function __construct($clientId, $clientSecret, $signingKey, $environment = 'production') {
    parent::__construct($clientId, $clientSecret, $signingKey, $environment);
  }

  public function checkPaymentByReferenceId($token, $referenceId) {
    return $this->createRequest('GET', '​/pg​/v1​/payment​/reference-id​/' . $referenceId, $token);
  }

  public function checkPaymentByPaymentId($token, $paymentId) {
    return $this->createRequest('GET', '/pg/v1/payment/' . $paymentId, $token);
  }

}