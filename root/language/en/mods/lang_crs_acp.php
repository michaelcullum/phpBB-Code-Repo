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
* Package:		Language [en_GB]
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

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(

// Main entries
	'ACP_CRS_MAIN_SETTINGS'			=> 'Code Repository Main Settings',
	'ACP_CRS_MAIN_SETTINGS'			=> 'Code Repository Settings',
	'ACP_CRS_MAIN_SETTINGS_EXPLAIN'		=> 'Here you can enable/disable several Code Repository features, and change the source directory location that the Code Repository uses.',

// Settings and stuffs
	'CURRENT_CRS_VERSION'			=> 'Installed Code Repository Version',
	'REMOTE_CRS_VERSION'			=> 'Current Code Repository Version',
	
	'CHECK_FOR_UPDATES'				=> 'Check for updates',
	'VERSION_CHECK'					=> 'Version Check',
	
	'UPDATE_AVAILABLE'				=> 'Update available',
	'CRS_NEEDS_UPDATE'				=> 'A new update to the phpBB Code Repository has been released and is ready for download.  <br />
	Please read the <a href="%1$s" title="%1$s">release announcement</a> before updating, 
		as it may contain useful information pertaining to the update.',
		
	'CRS_IS_UP_TO_DATE'				=> 'Your phpBB Code Repository installation is up to date.',
	
	'CRS_ENABLED'					=> 'Enable the Code Repository',
	'CRS_ENABLED_EXPLAIN'			=> 'Enables/disables the Code Repository from being accessed.',
	
	'CRS_DUMP_GESHI_CSS'			=> 'Output GeSHi CSS Information',
	'CRS_DUMP_GESHI_CSS_EXPLAIN'	=> 'Outputs CSS classes to the page (when viewing a highlighted file) for use by GeSHi to reduce the size of the HTML source created.',

	'CRS_SOURCE_PATH'				=> 'Source Directory',
	'CRS_SOURCE_PATH_EXPLAIN'		=> 'The directory that is used for obtaining source files to display.',
	
	'CRS_USE_IDINFO_REPLACE'		=> 'Enable $id$ replacement',
	
	'CRS_IDINFO_REPLACEMENT'		=> '$id$ Replacement text',
	'CRS_IDINFO_REPLACEMENT_EXPLAIN'=> 'Text to replace $id$ with, within source files.',
	
// Errors
	'CRS_NOT_INSTALLED'				=> 'The Code Repository must be installed before this ACP Module can be used.',
	'CRS_DB_NOT_CURRENT'			=> 'The database component of the Code Repository is not current.  Please run the provided update script.',

	
// Recaching messages
	'NO_AUTH_PURGE_REPO_CACHE'	=> 'You are not authorized to rebuild the cache file for the Code Repository.',
	'CODE_REPO_RECACHE_CONFIRM'	=> 'Are you sure you want to reconstruct the Code Repository\'s cache file?  This action should <em>only</em> be taken 
						if the filelist must be immediately reconstructed to show file additions/removals, or if in the event of errors.
						<br /><br />Additionally, please ensure that the directory contains source files of some kind before proceeding, 
						else this will disable the Code Repository until files are added, and may cause <strong>heavy strain on the server</strong> 
						while it checks for source files every time the Code Repository is viewed.',
	'CODE_REPO_RECACHE_SUCCESS'	=> "The reconstruction of the Code Repository's cache file was successful.",
	'CRS_RECACHE'				=> 'Code Repository Caching',
	'CRS_RECACHE_FILES'			=> 'Rebuild the Code Repository Cache File',
));

?>
