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

// Adding new category
$lang['permission_cat']['crs']   = 'Code Repository';

// Adding the permissions
$lang = array_merge($lang, array(
	'acl_u_crs_view'		=> array('lang' => 'Can view the Code Repository', 'cat' => 'crs'),
	'acl_u_crs_viewfile'		=> array('lang' => 'Can view source files', 'cat' => 'crs'),
	'acl_u_crs_viewimage'		=> array('lang' => 'Can view images', 'cat' => 'crs'),
	'acl_u_crs_download'		=> array('lang' => 'Can download files from Code Repository', 'cat' => 'crs'),
	'acl_u_crs_highlightfile'	   	=> array('lang' => 'Can use syntax highlighting when viewing source files', 'cat' => 'crs'),
));

?>
