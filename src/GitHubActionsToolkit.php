<?php
/**
 * Class GitHubActionsToolkit
 *
 * @created      15.10.2022
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2022 smiley
 * @license      MIT
 */

use chillerlan\HTTP\HTTPOptions;
use chillerlan\HTTP\Psr18\CurlClient;
use chillerlan\HTTP\Psr7\Request;
use chillerlan\HTTP\Psr7\Uri;
use Psr\Http\Client\ClientInterface;

/**
 *
 */
class GitHubActionsToolkit{

	/** @var \chillerlan\HTTP\HTTPOptions */
	private $httpOptions;

	/**
	 *
	 */
	public function __construct(){
		$this->httpOptions = new HTTPOptions;
		$this->httpOptions->ca_info    = ACTION_TOOLKIT_SRC.'/cacert.pem';
		$this->httpOptions->user_agent = ACTION_TOOLKIT_UA;
	}

	/**
	 * Returns a PSR-18 compatible http client
	 */
	private function getHttpClient():ClientInterface{
		return new CurlClient($this->httpOptions);
	}

	/**
	 * Send a request to the given URL and retrieve the response body or null if an error occurs - no questions asked.
	 *
	 * @throws \chillerlan\HTTP\Psr18\RequestException
	 */
	public function fetchFromURL(string $url):?string{
		$http = $this->getHttpClient();

		$request  = new Request('GET', new Uri($url));
		$response = $http->sendRequest($request);

		// i wonder how this works out on GitHub with its broken curl_getinfo()
		if(!in_array($response->getStatusCode(), [200, 204, 206, 301], true)){
#			throw new \chillerlan\HTTP\Psr18\RequestException(sprintf('could fulfill request: %s', $response->getStatusCode()), $request);

			return null;
		}

		return $response->getBody()->getContents();
	}

}
