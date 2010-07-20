<?php
/**
*
*===================================================================
*
*  phpBB Code Repository -- ACP Module File
*-------------------------------------------------------------------
*	Script info:
* Version:		1.0.6 - "Juno"
* Copyright:	(c) 2008, 2009 | Obsidian -- Infinityhouse Creations
* License:		http://opensource.org/licenses/gpl-license.php  |  GNU Public License v2
* Package:		ACP
*
*===================================================================
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* Version info - 
* @ignore
*/
define('CRS_VERSION', '1.0.6');
define('CRS_VERSION_BIG', 'phpBB Code Repository Version 1.0.6');

/**
* In Code Repository 1.0.2+, we'll start using PHPBB_ROOT_PATH and PHP_EXT constants where we can. 
*	Hooray, progress!
*/
if(!defined('PHPBB_ROOT_PATH'))
{
	define('PHPBB_ROOT_PATH', $phpbb_root_path);
}
if(!defined('PHP_EXT'))
{
	define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
}

/**
 * @package acp
 */
class acp_crs_main
{
	var $u_action;

	function main($id, $mode)
	{
		global $user, $template, $config;

		$submit = (isset($_POST['submit'])) ? true : false;
		$recache = (isset($_POST['recache'])) ? true : false;
		$check_version = (isset($_POST['checkversion'])) ? true : false;
		$error = array();
		
		include(PHPBB_ROOT_PATH . 'includes/crs/crs_misc_functions.' . PHP_EXT);
		include(PHPBB_ROOT_PATH . 'includes/crs/crs_constants.' . PHP_EXT);
		//! Load our installation information...
		load_crs_install_info();
		
		$user->add_lang(array('mods/lang_crs_acp', 'mods/lang_crs', 'mods/info_acp_crs_main'));
		$this->tpl_name = 'acp_crs_main';
		$this->page_title = 'ACP_CRS_MAIN';
		
		if($check_version)
		{
			check_crs_version($latest, $latest_version, $announcement_url);
			$template->assign_vars(array(
				'L_TITLE'				=> $user->lang['ACP_CRS_MAIN'],
				'UP_TO_DATE'			=> $latest,
				'S_IN_VERSION_CHECK'	=> true,
			));
			if($latest !== true)
			{
				$template->assign_var('UPDATE_INSTRUCTIONS', sprintf($user->lang['CRS_NEEDS_UPDATE'], $announcement_url));
			}
			$template->assign_vars(array(
				'CURRENT_VERSION'			=> $config['crs_version'],
				'LATEST_VERSION'			=> $latest_version,
			));
		}
		
		$crs_vars = array(
			'crs_source_path' 				=> 'CRS_SOURCE_PATH',
		);
		
		$crs_bool_vars = array(
			'crs_enabled' 					=> 'CRS_ENABLED',
			'crs_dump_geshi_css' 			=> 'CRS_DUMP_GESHI_CSS',
		);

		$template->assign_vars(array(
			'U_ACTION'			=> $this->u_action,
			'L_TITLE'			=> $user->lang['ACP_CRS_MAIN'],
			'S_FOUNDER'			=> ((int) $user->data['user_type'] === USER_FOUNDER) ? true : false,
		));
		
		// Is the Code Repository installed?
		if(!isset($config['crs_version']))
		{
	 		trigger_error($user->lang['CRS_NOT_INSTALLED'], E_USER_WARNING);
		}
		// Are new files currently present?
		if(version_compare($config['crs_version'], CRS_VERSION, '<'))
		{
			trigger_error($user->lang['CRS_DB_NOT_CURRENT'], E_USER_WARNING);
		}
		// Check to see if the install directory still is present.  If it is, stop...in the name of looOOove~!
		// For the brave testing squads out there, we disable this check and allow them to proceed.
		if(@file_exists(PHPBB_ROOT_PATH . 'install_crs') && !defined('DEBUG_EXTRA')) 
		{
			// IDIOT...Get rid of the directory already, would you?  >_<
			trigger_error($user->lang['CRS_INSTALL_DIR_PRESENT'], E_USER_WARNING);
		}
		
		if($recache && !$check_version)
		{
			if(!confirm_box(true))
			{
				confirm_box(false, $user->lang['CODE_REPO_RECACHE_CONFIRM'], build_hidden_fields(array(
					'i'			=> $id,
					'mode'		=> $mode,
					'recache'	=> true,
				)));
			}
			else
			{
				if ($user->data['user_type'] != USER_FOUNDER)
				{
					trigger_error($user->lang['NO_AUTH_OPERATION'] . adm_back_link($this->u_action), E_USER_WARNING);
				}
				global $cache;
				$cache->destroy('_crs_files');
				
				trigger_error($user->lang['CODE_REPO_RECACHE_SUCCESS'] . adm_back_link($this->u_action));
			}
		}
		
		$form_key = 'acp_crs_main';
		add_form_key($form_key);
		
		if($submit && !check_form_key($form_key) && !$check_version)
		{
				$error[] = $user->lang['FORM_INVALID'];
		}
		
		if($submit && !sizeof($error) && check_form_key($form_key) && !$check_version)
		{
			//Background check on source path first before data storage.  Clean up the filepath and maketh it safe to use.
			$crs_source_dir = sanitize_filepath(request_var('crs_source_path', ''));

			if (!@file_exists(PHPBB_ROOT_PATH . $crs_source_dir))
			{
				$error[] = sprintf($user->lang['DIRECTORY_DOES_NOT_EXIST'], $crs_source_dir);
			}

			if (@file_exists(PHPBB_ROOT_PATH . $crs_source_dir) && !@is_dir(PHPBB_ROOT_PATH . $crs_source_dir))
			{
				$error[] = sprintf($user->lang['DIRECTORY_NOT_DIR'], $crs_source_dir);
			}
			
			// Do not write values if there is an error!
			if($submit && !sizeof($error))
			{
				$config_vars = array_keys($crs_bool_vars);
				foreach ($config_vars as $config_var)
				{
					if(request_var($config_var, 0) != 0)
					{
						set_config($config_var, 1);
					}
					else
					{
						set_config($config_var, 0);
					}
				}
				$config_vars = array_keys($crs_vars);
				foreach ($config_vars as $config_var)
				{
					if($config_var == 'crs_source_path')
					{
						set_config($config_var, $crs_source_dir);
					}
					else
					{
						set_config($config_var, request_var($config_var, '', true));
					}
				}
				if($crs_source_dir != $config['crs_source_path'])
				{
					$cache->destroy('_crs_files');  //Destroy the cache file, we've changed the source directory.
					$cache->destroy('config');
				}
				//Should only get here if everything is okay.  If it isn't, we ought to just reoutput the page instead.
				trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
			}
		}

		//Output previous settings..
		foreach ($crs_vars as $crs_var => $template_var)
		{
			$template->assign_var($template_var, $config[$crs_var]);
		}
		foreach ($crs_bool_vars as $crs_var => $template_var)
		{
			$template->assign_var($template_var, (($config[$crs_var] == 1) ? true : false));
		}
		$template->assign_vars(array(
			'CRS_VERSION'		=> $config['crs_version'],
			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'ERROR_MSG'			=> implode('<br />', $error),
		));

	}
}

?>