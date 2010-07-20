<?php
/**
*
*===================================================================
*
*  phpBB Code Repository -- Functions File
*-------------------------------------------------------------------
*	Script info:
* Version:		1.0.6 - "Juno"
* Copyright:	(c) 2008, 2009 | Obsidian -- Infinityhouse Creations
* License:		http://opensource.org/licenses/gpl-license.php  |  GNU Public License v2
* Package:		Includes
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

class file_fns
{
	/**
	* Returns what the parent directory would be.  Just about the same as dirname(), only it uses the {@link: file_fns::drop_trailing_slash() } function on input.
	*/
	function updir($path)
	{
		return dirname(file_fns::drop_trailing_slash($path));
	}
	
	/**
	* Removes a trailing slash if present.
	*/
	function drop_trailing_slash($path)
	{
		return ((substr($path, strlen($path) - 1) == '/') ? (substr($path, 0, strlen($path) - 1)) : $path);
	}
	
	/**
	* Filters out bits from a file's path for security.
	*/
	function filter_path($filepath, $dumped_path)
	{
		if (substr($filepath, 0, strlen($dumped_path)) == $dumped_path)
		{
			$filepath = substr($filepath, strlen($dumped_path), (strlen($filepath) - strlen($dumped_path)));
		}
		return $filepath;
	}

	/**
	* Identify what a file is from the extension, if there's an entry for it.
	* Updated to use {@link: crs_core::guess_lang(); }, borrowed from GeSHi and the Pastebin MOD.
	*/
	function identify_ext($filename)
	{
		global $user;
		return ($user->lang['LANGUAGES'][crs_core::guess_lang(file_fns::get_fileext($filename))] . $user->lang['POST_LANG_FILE']);
	}
	
	/**
	* Drops file's name and gets the last file extension (in lowercase), if there /is/ one.  Returns false if no detectable extension.
	*/
	function get_fileext($file)
	{
		if(!preg_match("(\.)", $file))  //-- /Everybody stand back/ -- I know regular expressions
		{
			return false;
		}
		return strtolower(substr(strrchr($file, '.'), 1));
	}
	
	/**
	* Is a file an image?  This function will check for it VIA the mimetype, as of 1.0.3
	*/
	function check_image($filename)
	{
		return (strpos(get_mimetype($filename), 'image') !== false) ? true : false; 
	}
}
?>
