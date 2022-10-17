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

foreach(['curl', 'openssl'] as $ext){
	if(!extension_loaded($ext)){
		throw new RuntimeException(sprintf('extension "%s" is required!', $ext));
	}
}

// set paths
define('GITHUB_ACTION_ROOT', $_SERVER['GITHUB_ACTION_PATH'] ?? realpath(__DIR__.'/..'));
define('ACTION_TOOLKIT_SRC', realpath(GITHUB_ACTION_ROOT.'/src'));
define('GITHUB_WORKSPACE_ROOT', $_SERVER['GITHUB_WORKSPACE'] ?? GITHUB_ACTION_ROOT);

$tmpdir = '/.action_toolkit_tmp';

if(!file_exists(GITHUB_ACTION_ROOT.$tmpdir)){
	mkdir(GITHUB_ACTION_ROOT.$tmpdir);
}

define('ACTION_TOOLKIT_TMP', realpath(GITHUB_ACTION_ROOT.$tmpdir));

// some constants for internal use
const ACTION_TOOLKIT_UA = 'phpGithubActionsToolkit/1.0 +https://github.com/codemasher/php-github-actions-toolkit';

