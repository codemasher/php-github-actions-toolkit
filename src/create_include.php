<?php
/**
 * create_include.php
 *
 * @created      15.10.2022
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2022 smiley
 * @license      MIT
 */

$include = '<?php
require_once \''.realpath(__DIR__.'/vendor/autoload.php').'\';
';

$path = ($_SERVER['GITHUB_WORKSPACE'] ?? realpath(__DIR__.'/..')).'/.github/github_actions_toolkit.php';
file_put_contents($path, $include);

var_dump(realpath($path));
