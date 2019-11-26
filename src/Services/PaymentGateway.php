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

  public function __construct($environment = 'production') {
    parent::__construct($environment);
  }

  public function checkPaymentByReferenceId($referenceId) {
    return $this->createRequest('GET', '​/pg​/v1​/payment​/reference-id​/' . $referenceId);
  }

  public function checkPaymentByPaymentId($paymentId) {
    return $this->createRequest('GET', '/pg/v1/payment/' . $paymentId);
  }

}