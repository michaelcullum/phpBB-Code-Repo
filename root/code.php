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
* Package:		phpBB3
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
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
if(!defined('PHPBB_ROOT_PATH')) define('PHPBB_ROOT_PATH', $phpbb_root_path);
if(!defined('PHP_EXT')) define('PHP_EXT', $phpEx);

// Self linking purposes.  :P
define('CRS_SELFLINK', PHPBB_ROOT_PATH . 'code.' . PHP_EXT);
include($phpbb_root_path . 'common.' . $phpEx);

/**
* Start session management
*/
$mode =  request_var('mode', '');  //! Mode check...Where shall we go tomorrow?
//! Quickly check to see if we're in the download mode.  If so, pass false to the session_begin call.
if($mode == 'download' || $mode == 'pipeimage')
{
	$user->session_begin(false);
}
else
{
	$user->session_begin(true);
}

//! Load auths and setup.
$auth->acl($user->data);
$user->setup(array('mods/lang_crs', 'viewtopic'));
if($mode == 'about')  // Quick check to see if the mode is the about mode.  If so, we can skip a bunch of stuff.
{
	trigger_error('CODE_REPO_ABOUT');  //Yeah, yeah, the about page is an error.  So what.  <_<
}

//! Check for invalid mode now...we don't list about here because it should already have been picked up.
if(!in_array($mode, array('', 'download', 'pipeimage', 'highlight', 'view', 'browse')))
{
	trigger_error('NO_MODE');
}

//! Load CRS Bootstrap file.  This is *CRITICAL* to the Code Repository working right, so require it!
require(PHPBB_ROOT_PATH . "includes/crs/bootstrap." . PHP_EXT);

//! Setup some common template variables for in each CRS mode.
$template->assign_vars('U_ABOUT_CRS', append_sid(CRS_SELFLINK, 'mode=about'));

//! Build updir for certain modes before the main mode switch.
if(in_array($mode, array('', 'browse', 'view', 'highlight')))
{
	$updir = $crs->dynamic_updir(CRS_SELFLINK);
	if($updir !== false)
	{
		$updir = array_reverse($updir);
		foreach($updir as $level => $dir)
		{
			$template->assign_block_vars('updir', $dir);
		}
	}
	$template->assign_var('VIEWING_DIR', ((!$crs->is_root) ? basename(file_fns::filter_path($crs->dir, $crs->source_path)) : false));
}

//! Mode switch
switch ($mode)
{	
	case 'download':
	case 'pipeimage':
		//Okay, we need push out the file to the user.  

	//! Get mimetype, last modified time if possible, and send Priority Mail.
		$crs_file = array(
			'physical_filename'	=> basename($crs->file),
			'real_filename'		=> basename($crs->file),
			'mimetype'			=> get_mimetype($crs->file),
			'filetime'			=> filemtime($crs->file),
		);
		    push_file($crs_file, dirname(file_fns::filter_path($crs->file, PHPBB_ROOT_PATH)), (($mode == 'pipeimage') ? ATTACHMENT_CATEGORY_IMAGE : ATTACHMENT_CATEGORY_NONE));
	break;

	case 'highlight':
	case 'view':
		// So we want to look at a file?  Fine, fine.  Let's prep.
	//! Quick-dump our pre-loaded template variables that the Code Repository Core class pulled.
		$template->assign_vars($crs->templ);
	//! Dump the rest of the vars now.
		$template->assign_vars(array(
			'S_MODE_VIEW'			=> true,
			'S_IS_IMAGE'			=> ($crs->info['is_image'] == true) ? true : false,
			'CODE_DATA'				=> ($crs->info['is_image'] == false) ? $crs->info['file_source'] : false,
			'REPO_IMAGE'			=> ($crs->info['is_image'] == true) ? append_sid(CRS_SELFLINK, "mode=pipeimage&amp;d={$crs->dir_id}&amp;f={$crs->file_id}") : false,
			
			'HIGHLIGHT_POWER'		=> ($mode == 'highlight') ? sprintf($user->lang['HIGHLIGHT_POWER'], GESHI_VERSION) : false,
			
			'L_HLIGHT'				=> ($mode == 'highlight') ? $user->lang['DISABLE_HIGHLIGHTING'] : $user->lang['ENABLE_HIGHLIGHTING'],
			
			'U_BROWSE_DIR'			=> append_sid(CRS_SELFLINK, "mode=browse&amp;d={$crs->dir_id}"),
			'U_HLIGHT_LINK'			=> append_sid(CRS_SELFLINK, 'mode=' . (($mode != "highlight") ? 'highlight' : 'view') . "&amp;d={$crs->dir_id}&amp;f={$crs->file_id}"),
			'U_DOWNLOAD_LINK'		=> ($auth->acl_get('u_crs_download') == true) ? append_sid(CRS_SELFLINK, "mode=download&amp;d={$crs->dir_id}&amp;f={$crs->file_id}") : false,
			
			'S_CAN_DOWNLOAD'		=> ($auth->acl_get('u_crs_download') == true) ? true : false,
			
			'S_MAKE_TEXT_BIGGER'	=> (strpos(strtolower($user->browser), 'windows') !== false) ? true : false, // Stupid Winblows not showing text at the right size...ARGH!
		));

		$l_title = ($mode == 'view') ? ((!$crs->info['is_image']) ? $user->lang['VIEW_SOURCE_CODE'] : $user->lang['VIEW_REPO_IMAGE']) : $user->lang['VIEW_HIGHLIGHTED_CODE'];
	break;
	
	case 'browse':
	default:
		//Okay, we want to view some shtuff, do we?  Let's get going.
/* ## Stupid pagination...
		$start = array('start_subdir' => round(intval(request_var('ssd', 0)), -1), 'start_file' => round(intval(request_var('sf', 0)), -1));
		$start['start_subdir'] = ($start['start_subdir'] < 0) ? ($start['start_subdir']) : 0;
		$start['start_file'] = ($start['start_file'] < 0) ? ($start['start_file']) : 0;
*/
		$template->assign_vars(array(
			'QUICK_DOWNLOAD_IMG'	=> $user->img('icon_topic_latest', 'QUICK_DOWNLOAD'),
			'S_MODE_BROWSE'			=> true,
			'S_NO_CONTENTS'			=> ($crs->has_contents != true) ? true : false,
		));
			
	//! Loop through subdirs and files now
		if($crs->has_subdir)
		{
			$subdirs = $crs->subdirs[$crs->dir_id];	// Pagination disabled -- $crs->paginate_listing($crs->subdirs[$crs->dir_id], $start['start_subdir'], 10);
			foreach($subdirs as $key => $subdir)
			{
				$template->assign_block_vars('subdirrow', array(
					'U_VIEW_DIR'	=> append_sid(CRS_SELFLINK, "mode=browse&amp;d={$crs->flip_dirs[$subdir]}"),
					'SUBDIR_PATH'	=> file_fns::filter_path($subdir, $crs->source_path),
				));
			}
		}
		if($crs->has_file)
		{
			$files = $crs->files[$crs->dir_id];	// Pagination disabled -- $crs->paginate_listing($crs->files[$crs->dir_id], $start['start_file'], 10);
			foreach($files as $key => $file)
			{
				$is_image = file_fns::check_image($file);
				$template->assign_block_vars('filelistrow', array(
					'U_QUICK_DOWNLOAD'	=> ($auth->acl_get('u_crs_download') == true) ? append_sid(CRS_SELFLINK, "mode=download&amp;d={$crs->dir_id}&amp;f={$key}") : false,
					'U_QUICK_HIGHLIGHT'	=> ($auth->acl_get('u_crs_highlightfile') == true && !$is_image) ? append_sid(CRS_SELFLINK, "mode=highlight&amp;d={$crs->dir_id}&amp;f={$key}") : false,
					
					'U_VIEW_FILE'	=> (($auth->acl_get('u_crs_viewimage') == true && $is_image) || ($auth->acl_get('u_crs_viewfile') == true && !$is_image)) ? append_sid(CRS_SELFLINK, "mode=view&amp;d={$crs->dir_id}&amp;f={$key}") : false,
					
					'FILE_PATH'	=> file_fns::filter_path($file, $crs->source_path),
					'FILE_TYPE'	=> file_fns::identify_ext($file),
				));
			}
		}
		$l_title = $user->lang['VIEW_CODE_REPO'];
	break;
} //end $mode switch

page_header($l_title);

$template->set_filenames(array(
	'body' => 'crs/crs_body.html'
));
page_footer();

?>