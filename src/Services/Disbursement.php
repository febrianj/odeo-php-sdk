<?php

namespace OdeoApi\Services;

use OdeoApi\OdeoApi;

class Disbursement extends OdeoApi {

  function __construct($clientId, $clientSecret, $signingKey, $environment = 'production') {
    parent::__construct($clientId, $clientSecret, $signingKey, $environment);
  }

  function bankAccountInquiry($token, $accountNo, $bankId, $customerName, $withValidation = false) {
    return $this->createRequest('POST', '/dg/v1/bank-account-inquiry', $token, [
      'account_number' => $accountNo,
      'bank_id' => $bankId,
      'customer_name' => $customerName,
      'with_validation' => $withValidation
    ]);
  }

  function bankList($token) {
    return $this->createRequest('GET', '/dg/v1/banks', $token);
  }

  function executeDisbursement($token, $accountNo, $amount, $bankId, $customerName, $description, $referenceId) {
    return $this->createRequest('POST', '/dg/v1/disbursements', $token, [
      'account_number' => $accountNo,
      'amount' => $amount,
      'bank_id' => $bankId,
      'customer_name' => $customerName,
      'description' => $description,
      'reference_id' => $referenceId
    ]);
  }

  function checkDisbursementByReferenceId($token, $referenceId) {
    return $this->createRequest('GET', '​/dg​/v1​/disbursements​/reference-id​/' . $referenceId, $token);
  }

  function checkDisbursementByDisbursementId($token, $disbursementId) {
    return $this->createRequest('GET', '/dg/v1/disbursements/' . $disbursementId, $token);
  }

  function checkBalance($token) {
    return $this->createRequest('GET', '/cash/me/balance', $token);
  }

}