<?php
/**
*
*===================================================================
*
*  phpBB Code Repository -- ACP Module Info File
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
* @package module_install
*/
class acp_crs_main_info
{
	function module()
	{		
		global $user;
		$user->add_lang(array('mods/lang_crs_acp', 'mods/info_acp_crs_main'));

		return array(
			'filename'	=> 'acp_crs_main',
			'title'		=> 'ACP_CRS_MAIN',
			'version'	=> '1.0.3',
			'modes'		=> array(
				'default'	=> array('title' => 'ACP_CRS_MAIN', 'auth' => 'acl_a_server', 'cat' => array('ACP_DOT_MODS')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>
