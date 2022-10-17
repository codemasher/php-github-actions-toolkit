<?php
/**
 * create_include.php
 *
 * @created      15.10.2022
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2022 smiley
 * @license      MIT
 */

require_once __DIR__.'/common.php';


var_dump([GITHUB_ACTION_ROOT, ACTION_TOOLKIT_SRC, ACTION_TOOLKIT_TMP, GITHUB_WORKSPACE_ROOT]);

$include = '<?php
require_once \''.realpath(ACTION_TOOLKIT_SRC.'/vendor/autoload.php').'\';
';

var_dump($include);

file_put_contents(GITHUB_ACTION_ROOT.'/github_actions_toolkit.php', $include);
file_put_contents(GITHUB_WORKSPACE_ROOT.'/github_actions_toolkit.php', $include);
