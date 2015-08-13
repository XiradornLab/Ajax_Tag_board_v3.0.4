<?php
/**
*
* @package	install
* @version	3.0.7
* @license	GNU Public License
* @author	draghetto
*
*/

/**
* @ignore
*/
define('IN_PHPBB',true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'),1);
include($phpbb_root_path . 'common.' . $phpEx);

// Tb version
$tb_version = '3.0.7';

// Start session management
$user->session_begin();
$user->setup('tag_board');

// Create table
$sql = "CREATE TABLE IF NOT EXISTS " . $table_prefix . "tag_board (
	tb_post_id mediumint(8) UNSIGNED NOT NULL auto_increment,
	tb_poster_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	tb_post_time int(11) UNSIGNED DEFAULT '0' NOT NULL,
	tb_post_username varchar(255) DEFAULT '' NOT NULL,
	tb_post_text mediumtext NOT NULL,
	tb_bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	tb_bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	tb_flags int(1) UNSIGNED DEFAULT '3' NOT NULL,
	PRIMARY KEY (tb_post_id)
)";
$db->sql_query($sql);

// Insert Tag Board Module
$module_name = $user->lang['TB_TB'];
if (!class_exists('acp_modules'))
{
	include($phpbb_root_path . 'includes/acp/acp_modules.' . $phpEx);
}
$module = new acp_modules();
$sql = 'SELECT module_id
	FROM ' . MODULES_TABLE . "
	WHERE module_langname = '$module_name'";
$result = $db->sql_query($sql);
if (!$db->sql_fetchrow($result))
{
	// Insert Category Module
	$cat_module_data = array(
		'module_enabled'	=> 1,
		'module_display'	=> 1,
		'module_class'		=> 'acp',
		'parent_id'			=> 0,
		'module_langname'	=> $user->lang['TB_TB'],
		'module_auth'		=> '',
	);
	$module->update_module_data($cat_module_data, true);

	// Insert Parent Module
	$parent_module_data = array(
		'module_enabled'	=> 1,
		'module_display'	=> 1,
		'module_class'		=> 'acp',
		'parent_id'			=> $cat_module_data['module_id'],
		'module_langname'	=> $user->lang['TB_TB'],
	);
	$module->update_module_data($parent_module_data, true);

	// Settings Module
	$front_module_data = array(
		'module_enabled'	=> 1,
		'module_display'	=> 1,
		'module_class'		=> 'acp',
		'parent_id'			=> $parent_module_data['module_id'],
		'module_langname'	=> $user->lang['TB_ACP'],
		'module_basename'	=> 'tag_board',
		'module_mode'		=> 'settings',
		'module_auth'		=> '',
	);
	$module->update_module_data($front_module_data, true);
}

// Initializate all vars
set_config('tb_version',$tb_version);
if(!isset($config['tb_null']))
{
	set_config('tb_null','');
}
if(!isset($config['tb_auth']))
{
	set_config('tb_auth','STANDARD');
}
if(!isset($config['tb_groups']))
{
	set_config('tb_groups','');
}
if(!isset($config['tb_denied']))
{
	set_config('tb_denied','');
}
if(!isset($config['tb_guest']))
{
	set_config('tb_guest','');
}
if(!isset($config['tb_limit']))
{
	set_config('tb_limit','20');
}
if(!isset($config['tb_heigth']))
{
	set_config('tb_heigth','150');
}
if(!isset($config['tb_maxlength']))
{
	set_config('tb_maxlength','500');
}
if(!isset($config['tb_bbcode']))
{
	set_config('tb_bbcode','1');
}
if(!isset($config['tb_custom']))
{
	set_config('tb_custom','0');
}
if(!isset($config['tb_fsize']))
{
	set_config('tb_fsize','1');
}
if(!isset($config['tb_img']))
{
	set_config('tb_img','0');
}
if(!isset($config['tb_flash']))
{
	set_config('tb_flash','0');
}
if(!isset($config['tb_smilies']))
{
	set_config('tb_smilies','1');
}
if(!isset($config['tb_urls']))
{
	set_config('tb_urls','1');
}
if(!isset($config['tb_buttons']))
{
	set_config('tb_buttons','1');
}
if(!isset($config['tb_delete']))
{
	set_config('tb_delete','1');
}
if(!isset($config['tb_edit']))
{
	set_config('tb_edit','1');
}
if(!isset($config['tb_edit_time']))
{
	set_config('tb_edit_time','1');
}
if(!isset($config['tb_purge']))
{
	set_config('tb_purge','0');
}
if(!isset($config['tb_flood']))
{
	set_config('tb_flood','0');
}
if(!isset($config['tb_refresh']))
{
	set_config('tb_refresh','10');
}
if(!isset($config['tb_history']))
{
	set_config('tb_history','0');
}
if(!isset($config['tb_deleteall']))
{
	set_config('tb_deleteall','0');
}

trigger_error($user->lang['TB_INSTALL']);

?>