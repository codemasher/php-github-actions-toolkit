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
use chillerlan\HTTP\Psr7\Response;
use chillerlan\HTTP\Psr7\Stream;
use chillerlan\HTTP\Psr7\Uri;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * https://github.com/actions/toolkit
 */
class GitHubActionsToolkit{

	const TOOLKIT_SRC = __DIR__;
	const USER_AGENT  = 'phpGithubActionsToolkit/1.0 +https://github.com/codemasher/php-github-actions-toolkit';

	private CurlClient $http;

	/**
	 *
	 */
	public function __construct(){

		$httpOptions = new HTTPOptions([
			'ca_info'    => self::TOOLKIT_SRC.'/cacert.pem',
			'user_agent' => self::USER_AGENT,
			'timeout'    => 0,
		]);

		$this->http  = new CurlClient($httpOptions);
	}

	/**
	 *
	 */
	public function getActionRoot():string{
		return $_SERVER['GITHUB_ACTION_PATH'] ?? realpath(__DIR__.'/..');
	}

	/**
	 *
	 */
	public function getWorkspaceRoot():string{
		return $_SERVER['GITHUB_WORKSPACE'] ?? $this->getActionRoot();
	}

	/**
	 *
	 */
	public function getActionTmp():string{
		$tmpdir = $this->getWorkspaceRoot().'/.github/.action_toolkit_tmp';

		if(!file_exists($tmpdir)){
			mkdir($tmpdir);
		}

		return realpath($tmpdir) ?: '';
	}

	/**
	 * @todo https://github.blog/changelog/2022-10-11-github-actions-deprecating-save-state-and-set-output-commands/
	 */
	public function outputVar(string $name, string $value):void{
		echo "::set-output name=$name::$value\n";
#		`echo "{$name}={$value}" >> \$GITHUB_OUTPUT`;
#		exec('echo "'.$name.'='.$value.'" >> $GITHUB_ENV');
	}

	/**
	 *
	 */
	public function outputVars(array $vars):void{
		foreach($vars as $name => $value){
			$this->outputVar($name, $value);
		}
	}

	/**
	 * Send a request to the given URL and retrieve the response body or null if an error occurs - no questions asked.
	 *
	 * @throws \chillerlan\HTTP\Psr18\RequestException
	 */
	public function fetchFromURL(string $url):?string{
		$request  = new Request('GET', new Uri($url));
		$response = $this->http->sendRequest($request);

		// i wonder how this works out on GitHub with its broken curl_getinfo()
		if(!in_array($response->getStatusCode(), [200, 204, 206, 301], true)){
#			throw new \chillerlan\HTTP\Psr18\RequestException(sprintf('could fulfill request: %s', $response->getStatusCode()), $request);

			return null;
		}

		return $response->getBody()->getContents();
	}

	/**
	 *
	 */
	public function downloadFile(string $url, string $destination = null):bool{
		$destination ??= $this->getActionTmp();
		$dest          = dirname($destination);

		if(!file_exists($dest) || !is_dir($dest) || !is_writable($dest)){
			throw new RuntimeException(sprintf('download destination is not writable: %s', $dest));
		}

		if(is_dir($destination)){
			$destination .= DIRECTORY_SEPARATOR.basename($url);
		}

		$responseFactory = new class($destination) implements ResponseFactoryInterface{
			private string $dest;

			public function __construct(string $dest){
				$this->dest = $dest;
			}

			public function createResponse(int $code = 200, string $reasonPhrase = ''):ResponseInterface{
				return new Response($code, null, new Stream(fopen($this->dest, 'w')), null, $reasonPhrase);
			}
		};

		// the PHP-FIG doesn't want us to have nice things https://github.com/php-fig/http-factory-util/pull/1
		$this->http->setResponseFactory($responseFactory);

		$request  = new Request('GET', new Uri($url));
		$response = $this->http->sendRequest($request);

		if($response->getStatusCode() !== 200){
			return false;
		}

		return true;
	}

	/**
	 *
	 */
	function unzip(string $zipfile, string $dest):bool{
#		echo "extracting: $zipfile to $dest\n";

		if(!is_readable($zipfile)){
#			echo "zip file not readable: $zipfile\n";
			return false;
		}

		$zip = new ZipArchive;

		if($zip->open($zipfile) === false){
#			echo "failed to open zip file: $zipfile\n";
			return false;
		}

		if($zip->extractTo($dest) === false){
#			echo "failed to extract zip file: $zipfile to $dest\n";
			return false;
		}

		$zip->close();

		return true;
	}

}
