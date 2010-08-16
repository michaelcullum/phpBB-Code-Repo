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
* Package:		Language  [en_GB]
*
*===================================================================
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'CRS_INSTALL_MAIN'		=> 'Welcome to the phpBB Code Repository <abbr title="Database Component Installer">DCI</abbr>.<br />Please click the link below to begin the installation of the Code Repository\'s config entries and permissions.',
	'INSTALL_CRS'			=> 'Install Code Repository',

//Success!
	'CRS_INSTALLED'			=> 'The Code Repository has been successfully installed, config entries added, ACP Modules added, and authorizations have been added.<br />
								Please go to the ACP and assign permissions under the Code Repository tab next to begin using your newly installed Code Repository!',
	'CRS_UPGRADED'			=> 'The Code Repository has been successfully updated.<br />Enjoy using your newly upgraded Code Repository!',
	
	'CRS_UPGRADE_MAIN'		=> 'Welcome to the phpBB Code Repository <abbr title="Database Component Installer">DCI</abbr>.<br />Please click the link below to begin the Database-component update of the phpBB Code Repository.',
	'UPGRADE_CRS'			=> 'Update Code Repository',

//No upgrade needed.
	'CRS_INSTALL_UP_TO_DATE'	=> 'The Code Repository\'s installation is complete and up-to-date and does not require upgrading, or upgraded files or not present.',
	
//Failure messages...
	'CRS_UPGRADE_FAILED_UNK_VERS'		=> 'The Code Repository\'s update encountered an unknown installation version and could not complete.',
	'CRS_UPGRADE_FAILED_NOT_INST'		=> 'The Code Repository must be previously installed in order for an update to be possible.',
	'CRS_INSTALL_FAILED_NO_REINST'		=> 'The Code Repository\'s installation was aborted as a previous installation was detected.',
));

?>
