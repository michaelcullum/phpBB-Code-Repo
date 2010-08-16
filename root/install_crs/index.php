<?php
/**
*
*===================================================================
*
*  phpBB Code Repository -- Main File
*-------------------------------------------------------------------
*	Script info:
* Version:		1.0.0 - "Cataram"
* Copyright:	Current Contributor(c) 2010 | Unknown Bliss
* Copyright:	Ex-Contributor (c) 2008, 2009 | Obsidian
* License:		http://opensource.org/licenses/gpl-license.php  |  GNU Public License v2
* Package:		Install
*
*===================================================================
*
*/

/**
* Version info -
* @ignore
*/
define('CRS_VERSION', '1.0.0');
define('CRS_VERSION_BIG', 'phpBB Code Repository Version 1.0.0');

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
	
if(!defined('PHPBB_ROOT_PATH'))
{
	define('PHPBB_ROOT_PATH', $phpbb_root_path);
}
if(!defined('PHP_EXT'))
{
	define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
}

include(PHPBB_ROOT_PATH . 'includes/crs/crs_constants.' . PHP_EXT);
include(PHPBB_ROOT_PATH . 'includes/crs/crs_misc_functions.' . PHP_EXT);

/**
* Start session management
*/
$user->session_begin();
$auth->acl($user->data);
$user->setup(array('mods/lang_crs', 'mods/lang_crs_install'));

//! Load our installation information...
load_crs_install_info();

//! Don't you know who /I/ am?
if (!$user->data['is_registered'])
{
	if ($user->data['is_bot'])
	{
		redirect(append_sid(PHPBB_ROOT_PATH . 'index.' . PHP_EXT));
	}
	// Hold on, my ID has /got/ to be in here somewhere..
	login_box('', 'LOGIN');
}
else if ((int) $user->data['user_type'] !== USER_FOUNDER)
{
	//! Give them das boot out of there.  :3
	trigger_error('NOT_AUTHORIZED');
}

//! Mode check...where shall we go tomorrow?
$mode = request_var('mode', '');
switch($mode)
{
	case 'install':
		if (isset($config['crs_version']))
		{
			trigger_error('CRS_INSTALL_FAILED_NO_REINST');
		}
		//Implicit else.
		
		$install_path = './schemas/';
	//! Load in the schema and execute.  This may get bloody.  @_@;;
		//load_schema($install_path, false);
		//No schemas in 1.0.x, we're good.

	//! Setup $auth_admin class to add...a turkey sandwich?  Nah, just more auths.  :P
		include(PHPBB_ROOT_PATH . 'includes/acp/auth.' . PHP_EXT);
		$auth_admin = new auth_admin();
		$auth_admin->acl_add_option(array(
			'local'		=> array(),
			'global'	=> array(
				'u_crs_view', 
				'u_crs_viewfile', 
				'u_crs_highlightfile', 
				'u_crs_download', 
				'u_crs_viewimage',
			),
		));
	//! Configuratization-mabobber-jigger-whatsit.  :D
		set_config('crs_version', CRS_VERSION);
		set_config('crs_enabled', 0); //Defaults to disabled so admin can go setup perms first without n00bs whining.
		set_config('crs_source_path', 'source');
		set_config('crs_dump_geshi_css', 0);
		
		/**
		* EAMI integration for lazy people.  XD
		*/
		if(!class_exists('eami'))
		{
			include(PHPBB_ROOT_PATH . 'install_crs/eami.' . PHP_EXT);
		}
		
		$eami = new eami();

		// Add our ACP module and a parent module...
		// Parent module...
		$module_data = array(
			'module_langname'	=> 'ACP_MODS_INSTALLED',
		);
		$eami->add_module('acp', 'ACP_CAT_DOT_MODS', $module_data);		
		
		// Main module...
		$sql_ary = array(
			'module_basename'	=> 'crs_main',
			'module_langname'	=> 'ACP_CRS_MAIN',
			'module_mode'		=> 'default',
			'module_auth'		=> 'acl_a_server');
		$eami->add_module('acp', 'ACP_MODS_INSTALLED', $sql_ary);

		trigger_error('CRS_INSTALLED');
	break;


	case 'upgrade':
		if(!isset($config['crs_version']))
		{
			trigger_error('CRS_UPGRADE_FAILED_NOT_INST');
		}
		if(!version_compare($config['crs_version'], CRS_VERSION, '<'))
		{
			trigger_error('CRS_INSTALL_UP_TO_DATE');
		}
		//Implicit else of previous checks.
	//! Upgrade cycle...only ever use /two/ breaks. One at the end of the upgrade cycle, the other for version recognition failure.  
		switch ($config['crs_version'])
		{
			case '1.0.0 RC 2':
				//Nil.
			case '1.0.0 RC 3':
				//Nil, once again.
			case '1.1.0':
				//No DB changes this go around.
			case '1.2.0':
				// Code Repository update for 1.0.2 ($id$ replacement feature)
				set_config('crs_use_idinfo_replace', 0);
				set_config('crs_idinfo_replacement', '$ Code Repository File $');
			case '1.3.0':
				//Nil...again...
			case '1.4.0':
				//Nil...this was a codebase update.
			case '1.5.0':
				//Remove the $id$ replacement feature.
				$sql = 'DELETE FROM ' . CONFIG_TABLE . "
					WHERE config_value = 'crs_use_idinfo_replace' OR config_value = 'crs_idinfo_replacement'";
				$db->sql_query($sql);
			case '1.6.0':
				// Nil for DB updates.
				
			break;
			default:
				trigger_error('CRS_UPGRADE_FAILED_UNK_VERS');
			break;
		}
		//Version update ONLY AT THE END.
		set_config('crs_version', CRS_VERSION);
		trigger_error('CRS_UPGRADED');
	break;


	default:
		//Check the version config entry and see what we should do.
		if(isset($config['crs_version']))
		{
			if(version_compare($config['crs_version'], CRS_VERSION, '<'))
			{
				trigger_error($user->lang['CRS_UPGRADE_MAIN'] . '<br /><br />' .  '<a href="' . append_sid(PHPBB_ROOT_PATH . 'install_crs/index.' . PHP_EXT, 'mode=upgrade') . '">' . $user->lang['UPGRADE_CRS'] . '</a>');
			}
			else
			{
				trigger_error('CRS_INSTALL_UP_TO_DATE');
			}
		}
		else
		{
			trigger_error($user->lang['CRS_INSTALL_MAIN'] . '<br /><br />' .  '<a href="' . append_sid(PHPBB_ROOT_PATH . 'install_crs/index.' . PHP_EXT, 'mode=install') . '">' . $user->lang['INSTALL_CRS'] . '</a>');
		}
	break;
}

/**
* Load a schema (and execute)
*
* @param string $install_path Path to folder containing schema files
* @param mixed $install_dbms Alternative database system than $dbms
*/
function load_schema($install_path = '', $install_dbms = false)
{
	global $db;
	global $table_prefix;

	if ($install_dbms === false)
	{
		global $dbms;
		$install_dbms = $dbms;
	}

	static $available_dbms = false;

	if (!$available_dbms)
	{
		if (!function_exists('get_available_dbms'))
		{
			global $phpbb_root_path, $phpEx;
			include($phpbb_root_path . 'includes/functions_install.' . $phpEx);
		}

		$available_dbms = get_available_dbms($install_dbms);

		if ($install_dbms == 'mysql')
		{
			if (version_compare($db->sql_server_info(true), '4.1.3', '>='))
			{
				$available_dbms[$install_dbms]['SCHEMA'] .= '_41';
			}
			else
			{
				$available_dbms[$install_dbms]['SCHEMA'] .= '_40';
			}
		}
	}

	$remove_remarks = $available_dbms[$install_dbms]['COMMENTS'];
	$delimiter = $available_dbms[$install_dbms]['DELIM'];

	$dbms_schema = $install_path . $available_dbms[$install_dbms]['SCHEMA'] . '_schema.sql';

	if (file_exists($dbms_schema))
	{
		$sql_query = @file_get_contents($dbms_schema);
		$sql_query = preg_replace('#phpbb_#i', $table_prefix, $sql_query);

		$remove_remarks($sql_query);

		$sql_query = split_sql_file($sql_query, $delimiter);

		foreach ($sql_query as $sql)
		{
			$db->sql_query($sql);
		}
		unset($sql_query);
	}

	if (file_exists($install_path . 'schema_data.sql'))
	{
		$sql_query = file_get_contents($install_path . 'schema_data.sql');

		switch ($install_dbms)
		{
			case 'mssql':
			case 'mssql_odbc':
				$sql_query = preg_replace('#\# MSSQL IDENTITY (phpbb_[a-z_]+) (ON|OFF) \##s', 'SET IDENTITY_INSERT \1 \2;', $sql_query);
			break;

			case 'postgres':
				$sql_query = preg_replace('#\# POSTGRES (BEGIN|COMMIT) \##s', '\1; ', $sql_query);
			break;
		}

		$sql_query = preg_replace('#phpbb_#i', $table_prefix, $sql_query);
		$sql_query = preg_replace_callback('#\{L_([A-Z0-9\-_]*)\}#s', 'adjust_language_keys_callback', $sql_query);

		remove_remarks($sql_query);

		$sql_query = split_sql_file($sql_query, ';');

		foreach ($sql_query as $sql)
		{
			$db->sql_query($sql);
		}
		unset($sql_query);
	}
}

?>
