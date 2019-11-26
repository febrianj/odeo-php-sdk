<?php

namespace OdeoApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class OdeoApi {

  protected $clientId;
  protected $clientSecret;
  protected $signingKey;
  protected $baseUrl;

  /**
   * @var Client
   */
  protected $client;

  public function __construct($clientId, $clientSecret, $signingKey, $environment = 'production') {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->signingKey = $signingKey;

    switch ($environment) {
      case 'production':
        $this->production();
        break;
      case 'staging':
        $this->staging();
        break;
      default:
        $this->staging();
        break;
    }

    $this->newClient();
  }

  public function production() {
    $this->baseUrl = 'https://api.v2.odeo.co.id/';
  }

  public function staging() {
    $this->baseUrl = 'http://api.v2.staging.odeo.co.id/';
  }

  protected function newClient() {
    $this->client = new Client([
      'base_uri' => $this->baseUrl
    ]);
  }

  public function requestToken($scope = '') {
    $response = $this->client->request('POST', '/oauth2/token', [
      'json' => [
        'client_id' => $this->clientId,
        'client_secret' => $this->clientSecret,
        'grant_type' => 'client_credentials',
        'scope' => $scope
      ]
    ]);

    return $response->getBody()->getContents();
  }

  public function createRequest($method, $path, $token, $body = []) {
    if ($method == 'POST') {
      $options['json'] = $body;
    }
    $options['headers'] = $this->createHeaders($method, $path, $token, $body);

    try {
      $response = $this->client->request($method, $path, $options);
      return $response->getBody()->getContents();
    } catch (ClientException $e) {
      return $e->getResponse()->getBody()->getContents();
    }
  }

  protected function generateSignature($method, $path, $accessToken, $timestamp, $body) {
    if (empty($body)) {
      $body = '';
    } else if (is_array($body)) {
      $body = json_encode($body);
    }

    $bodyHash = base64_encode(hash('sha256', $body, true));
    $messages = [$method, $path, '', $accessToken, $timestamp, $bodyHash];
    $stringToSign = implode(':', $messages);
    $signature = base64_encode(hash_hmac('sha256', $stringToSign, $this->signingKey, true));

    return $signature;
  }

  protected function createHeaders($method, $path, $token, $body) {
    $timestamp = time();
    $signature = $this->generateSignature($method, $path, $token, $timestamp, $body);

    return [
      'Authorization' => 'Bearer ' . $token,
      'X-Odeo-Timestamp' => $timestamp,
      'X-Odeo-Signature' => $signature
    ];
  }

  public function isValidSignature($signatureToCompare, $method, $path, $token, $timestamp, $body) {
    $signature = $this->generateSignature($method, $path, $token, $timestamp, $body);
    return $signatureToCompare == $signature;
  }

}