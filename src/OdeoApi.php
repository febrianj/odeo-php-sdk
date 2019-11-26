<?php

namespace OdeoApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class OdeoApi {

  protected $clientId;
  protected $clientSecret;
  protected $signingKey;
  protected $baseUrl;
  protected $token;
  protected $accessToken;

  /**
   * @var Client
   */
  protected $client;

  public function __construct($environment = 'production') {
    $this->clientId = '';
    $this->clientSecret = '';
    $this->signingKey = '';

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
  }

  public function setCredentials($clientId, $clientSecret, $signingKey) {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->signingKey = $signingKey;
  }

  public function setAccessToken($accessToken) {
    $this->accessToken = $accessToken;
  }

  public function production() {
    $this->baseUrl = 'https://api.v2.odeo.co.id/';
    $this->newClient();
  }

  public function staging() {
    $this->baseUrl = 'http://api.v2.staging.odeo.co.id/';
    $this->newClient();
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
    $result = $response->getBody()->getContents();
    $accessToken = json_decode($result)->access_token;
    $this->setAccessToken($accessToken);
    return $result;
  }

  public function createRequest($method, $path, $body = []) {
    if ($method == 'POST') {
      $options['json'] = $body;
    }
    $options['headers'] = $this->createHeaders($method, $path, $body);
    $options['timeout'] = 60;

    try {
      $response = $this->client->request($method, $path, $options);
      return $response->getBody()->getContents();
    } catch (ClientException $e) {
      return $e->getResponse()->getBody()->getContents();
    }
  }

  public function isValidSignature($signatureToCompare, $method, $path, $timestamp, $body, $accessToken = '') {
    $signature = $this->generateSignature($method, $path, $timestamp, $body, $accessToken);

    return $signatureToCompare == $signature;
  }

  protected function newClient() {
    $this->client = new Client([
      'base_uri' => $this->baseUrl
    ]);
  }

  protected function generateSignature($method, $path, $timestamp, $body, $accessToken) {
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

  protected function createHeaders($method, $path, $body) {
    $timestamp = time();
    $signature = $this->generateSignature($method, $path, $timestamp, $body, $this->accessToken);

    return [
      'Authorization' => 'Bearer ' . $this->token,
      'X-Odeo-Timestamp' => $timestamp,
      'X-Odeo-Signature' => $signature
    ];
  }

}