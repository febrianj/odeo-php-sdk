<?php

namespace OdeoApi\Services;

use OdeoApi\OdeoApi;

class Disbursement extends OdeoApi {

  public function __construct($clientId, $clientSecret, $signingKey, $environment = 'production') {
    parent::__construct($clientId, $clientSecret, $signingKey, $environment);
  }

  public function bankAccountInquiry($token, $accountNo, $bankId, $customerName, $withValidation = false) {
    return $this->createRequest('POST', '/dg/v1/bank-account-inquiry', $token, [
      'account_number' => $accountNo,
      'bank_id' => $bankId,
      'customer_name' => $customerName,
      'with_validation' => $withValidation
    ]);
  }

  public function bankList($token) {
    return $this->createRequest('GET', '/dg/v1/banks', $token);
  }

  public function executeDisbursement($token, $accountNo, $amount, $bankId, $customerName, $description, $referenceId) {
    return $this->createRequest('POST', '/dg/v1/disbursements', $token, [
      'account_number' => $accountNo,
      'amount' => $amount,
      'bank_id' => $bankId,
      'customer_name' => $customerName,
      'description' => $description,
      'reference_id' => $referenceId
    ]);
  }

  public function checkDisbursementByReferenceId($token, $referenceId) {
    return $this->createRequest('GET', '​/dg​/v1​/disbursements​/reference-id​/' . $referenceId, $token);
  }

  public function checkDisbursementByDisbursementId($token, $disbursementId) {
    return $this->createRequest('GET', '/dg/v1/disbursements/' . $disbursementId, $token);
  }

  public function checkBalance($token) {
    return $this->createRequest('GET', '/cash/me/balance', $token);
  }

}