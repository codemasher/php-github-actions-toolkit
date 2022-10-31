<?php
/**
 * common.php
 *
 * PHP 7.4 compatible, just in case someone manages to run it with something else
 * than the php shipped with the GH actions runner...
 *
 * (we have cURL and OpenSSL, so all shall be good!)
 * @see https://github.com/actions/runner-images/blob/main/images/win/scripts/Installers/Install-PHP.ps1
 *
 * @created      15.10.2022
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2022 smiley
 * @license      MIT
 */

if(PHP_VERSION_ID < 70400){
	throw new RuntimeException('PHP 7.4+ is required!');
}

foreach(['curl', 'openssl', 'zip'] as $ext){
	if(!extension_loaded($ext)){
		throw new RuntimeException(sprintf('extension "%s" is required!', $ext));
	}
}

require_once __DIR__.'/GitHubActionsToolkit.php';
