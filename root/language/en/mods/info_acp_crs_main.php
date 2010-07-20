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
* Package:		Language [EN]
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
	'ACP_MODS_INSTALLED'	=> 'Installed MODs',
	'ACP_CRS_MAIN'			=> 'Code Repository Settings',
));

?>
