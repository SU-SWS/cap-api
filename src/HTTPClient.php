<?php

use SUSWS\CAPAPI\LayoutsLib\LayoutsLib;
use SUSWS\CAPAPI\SearchLib\SearchLib;
use SUSWS\CAPAPI\SchemaLib\SchemaLib;
use SUSWS\CAPAPI\ProfileLib\ProfileLib;
use SUSWS\CAPAPI\OrgLib\OrgLib;
use GuzzleHttp\Client;
use SUSWS\APIAuthLib\Authlib;

namespace SUSWS\CAPAPI;

/**
 * CAP HTTPClient extending Guzzle :)
 *
 * This client is used for communicating with the various endpoints of the
 * CAP API. The base of this class is the Guzzle HTTP client but contains a few
 * helpers and a lightweight lazy loading API library.
 *
 * Some API functions require an authentication token. This can be obtained
 * through the AuthLib and the authenticate() method.
 *
 * EXAMPLES:
 *
 * $client = new HTTPClient();
 *
 * $auth = $client->api('auth');
 * $auth->authenticate('username', 'password');
 * $token = $auth->getAuthToken();
 *
 * $client->setAPIToken($token);
 *
 * $schema = $client->api('schema')->profile();
 */
class HTTPClient {

  // Storage for the Guzzle http client object.
  protected $httpClient;
  // Authentication Client.
  protected $authClient;
  // Default CAP Endpoint url.
  protected $httpEndpoint = 'https://api.stanford.edu';
  // Auth Token is a very long string that is obtained from the CAP API after
  // successfully authenticating a username and password. See AuthLib.
  protected $httpAuthToken;
  // HTTP Options is an array of extra options to pass into the HTTP Client.
  protected $httpOptions;

  /**
   * Build with an HTTP Client such as guzzle.
   *
   * Live... live!
   */
  public function __construct($client, \SUSWS\APIAuthLib\Authlib $authClient) {
    $this->setHttpClient($client);
    $this->authClient = $authClient;
    $this->setApiToken($authClient->getAuthApiToken());
  }

  /**
   * Getter for $httpEndpoint.
   *
   * @return string
   *   A fully qualified url without the last slash.
   */
  public function getEndpoint() {
    return $this->httpEndpoint;
  }

  /**
   * Setter for $httpEndpoint.
   *
   * @param string $end
   *   A fully qualified URL without the last slash.
   */
  public function setEndpoint($end) {
    $this->httpEndpoint = $end;
  }

  /**
   * Getter for $httpClient.
   *
   * @return Client
   *   A Guzzle HTTP client.
   */
  public function getHttpClient() {

    // If we have a set client just return it.
    if (!is_null($this->httpClient)) {
      return $this->httpClient;
    }

    // If we do not have a client we need to create one.
    $client = new Client($this->getEndpoint());
    $this->setHttpClient($client);

    return $client;
  }

  /**
   * Setter for $httpClient.
   *
   * @param Client $client
   *   A Guzzle client object.
   */
  public function setHttpClient($client) {
    $this->httpClient = $client;
  }

  /**
   * Setter for $httpAuthToken.
   *
   * @param string $token
   *   A very long string to use with authenticated requests.
   */
  public function setApiToken($token) {
    $this->httpAuthToken = $token;
  }

  /**
   * Getter for $httpAuthToken.
   *
   * @return string
   *   The authenticated token or null.
   */
  protected function getApiToken() {
    if (empty($this->httpAuthToken)) {
      return NULL;
    }
    return $this->httpAuthToken;
  }

  /**
   * Getter for $httpOptions.
   *
   * @return array
   *   An associative array of options to pass to the HTTP client.
   */
  public function getHttpOptions() {
    return $this->httpOptions;
  }

  /**
   * Setter for $httpOptions.
   *
   * @param array $opts
   *   An associative array of options to pass to the HTTP client.
   */
  public function setHttpOptions($opts) {
    $this->httpOptions = $opts;
  }

  /**
   * [setLimit description]
   * @param [type] $int [description]
   */
  public function setLimit($int) {
    $httpOpts = $this->getHttpOptions();
    $httpOpts['query']['ps'] = $int;
    $this->setHttpOptions($httpOpts);
  }

  /**
   * [getLimit description]
   * @return [type] [description]
   */
  public function getLimit() {
    $httpOpts = $this->getHttpOptions();
    return $httpOpts['query']['ps'];
  }

  /**
   * [setPage description]
   * @param [type] $int [description]
   */
  public function setPage($int) {
    $httpOpts = $this->getHttpOptions();
    $httpOpts['query']['p'] = $int;
    $this->setHttpOptions($httpOpts);
  }

  /**
   * [getPage description]
   * @return [type] [description]
   */
  public function getPage() {
    $httpOpts = $this->getHttpOptions();
    return $httpOpts['query']['ps'];
  }

  //
  // ---------------------------------------------------------------------------
  //

  /**
   * This API function acts as a gateway for the various parts of this Library.
   *
   * By default it handles the passing of the http client and httpAuth token
   * into the HTTP client.
   *
   * @param string $name
   *   The name of the library part to use. eg: auth, org, profile, schema,
   *   layout, or search.
   *
   * @return object
   *   An API Lib object for a specific part of the CAP API.
   */
  public function api($name) {

    $client = $this->getHttpClient();
    $options = $this->getHttpOptions();

    // Add access token or we wont be able to communicate.
    $options['query']['access_token'] = $this->getApiToken();

    switch ($name) {
      case "org":
      case "orgs":
        $api = new OrgLib\OrgLib($client, $options);
        break;

      case "profile":
      case "profiles":
        $api = new ProfileLib\ProfileLib($client, $options);
        break;

      case "schema":
        $api = new SchemaLib\SchemaLib($client, $options);
        break;

      case "search":
        $api = new SearchLib\SearchLib($client, $options);
        break;

      case "layout":
      case "layouts":
        $api = new LayoutsLib\LayoutsLib($client, $options);
        break;

      default:
        throw new Exception(sprintf('Undefined api instance called: "%s"', $name));
    }

    $api->setEndpoint($this->getEndpoint());
    return $api;
  }

}
