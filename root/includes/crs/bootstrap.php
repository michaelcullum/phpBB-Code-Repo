<?php
/**
*
*===================================================================
*
*  phpBB Code Repository -- Bootstrap File
*-------------------------------------------------------------------
*	Script info:
* Version:		1.0.0 - "Cataram"
* Copyright:	Current Contributor(c) 2010 | Unknown Bliss
* Copyright:	Ex-Contributor (c) 2008, 2009 | Obsidian
* License:		http://opensource.org/licenses/gpl-license.php  |  GNU Public License v2
* Package:		Include
*
*===================================================================
*
* This Bootstrap system has been backported from phpBB "Ascraeus". 
*   The main intention is to allow an easier code layout for the coding 
*     of phpBB Code Repository 2.0.x, and for better organization.
*
* Bootstrap will kill uninstalled/unauthorized access attempts and try to force installation/upgrades automatically 
* 	if new updated files are detected, and will automatically load needed classes & files for each mode.
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
* Is this thing installed?  Is the DB installation not current?  
* Should we run around in circles screaming, or just push the user to the install page? O_o
*
* Also, let's check to see if the install directory is still present.  If it is, disable, and if the user is a founder, tell them to delete the thing already.
*/
	if(!isset($config['crs_version']) || version_compare($config['crs_version'], CRS_VERSION, '<'))
	{
		if((int) $user->data['user_type'] === USER_FOUNDER && @file_exists(PHPBB_ROOT_PATH . 'install_crs/index.' . PHP_EXT))
		{
			redirect(append_sid(PHPBB_ROOT_PATH . 'install_crs/index.' . PHP_EXT));
		}
		# [PANIC]
 		trigger_error('CRS_DISABLED');
	}
	
	// IDIOT...Get rid of the directory already, would you?  >_<  
	// (Only if we're not in DEBUG_EXTRA...for those brave developers, we disable this check!)
	if(@file_exists(PHPBB_ROOT_PATH . 'install_crs/') && !defined('DEBUG_EXTRA'))
	{
		if((int) $user->data['user_type'] === USER_FOUNDER)
		{
			trigger_error('CRS_INSTALL_DIR_PRESENT');
		}
		else
		{
			trigger_error('CRS_DISABLED');
		}
	}

//! Load auths, and check to see if this specific installation has been disabled, or if the user isn't allowed into the specific mode.
	// No access for non-founders if disabled.
	if($config['crs_enabled'] != true && (int) $user->data['user_type'] !== USER_FOUNDER)
	{
		trigger_error('CRS_DISABLED');
	}
	
	if($auth->acl_get('u_crs_view') == false)
	{
		trigger_error('NO_AUTHS_CRS_VIEW');
	}
	if($mode == 'view' && $auth->acl_get('u_crs_viewfile') == false)
	{
		trigger_error('NO_AUTHS_CRS_FILE');
	}
	if($mode == 'pipeimage' && $auth->acl_get('u_crs_viewimage') == false)
	{
		trigger_error('NO_AUTHS_CRS_IMAGE');
	}
	if($mode == 'highlight' && $auth->acl_get('u_crs_highlightfile') == false)
	{
		trigger_error('NO_AUTHS_CRS_HIGHLIGHT');
	}
	if($mode == 'download' && $auth->acl_get('u_crs_download') == false)
	{
		trigger_error('NO_AUTHS_CRS_DOWNLOAD');
	}

//! Okay now...what files do we need to import?
	include(PHPBB_ROOT_PATH . 'includes/crs/crs_constants.' . PHP_EXT); //Constants load first, ALWAYS.
	include(PHPBB_ROOT_PATH . 'includes/crs/crs_core.' . PHP_EXT);
	
	// In some other circumstances later on, we may not need these.  However, we constantly need them right now, so leave them in here.
	include(PHPBB_ROOT_PATH . 'includes/crs/crs_file_functions.' . PHP_EXT);
	include(PHPBB_ROOT_PATH . 'includes/crs/crs_misc_functions.' . PHP_EXT);

//! Mode-specific file-loading now.
	switch ($mode)
	{
		case 'pipeimage':
		case 'download':
			include(PHPBB_ROOT_PATH . 'includes/crs/crs_download.' . PHP_EXT);
		break;
		
		case 'highlight':
			include(PHPBB_ROOT_PATH . 'includes/geshi.' . PHP_EXT);
		break;
	}
	
//! Loadeth source path into Code Repository Core class.  
	// Shouldn't get this far if the DB component of the Code Repository isn't installed, Bootstrap *ought* to catch that.
	$crs = new crs_core($mode, PHPBB_ROOT_PATH . $config['crs_source_path']); 
//! Load our installation information...
	load_crs_install_info();

## END OF BOOTSTRAP FILE 
?>
