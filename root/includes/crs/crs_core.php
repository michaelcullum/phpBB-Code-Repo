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

class crs_core
{

//! Elements..

		//- Main
	var $source_path = '';		// source_path | String | Used for storing the source path that the Code Repository uses.
	var $files = array();		// files | Array | Used for storing list of files found by crawldir in source directory.
	var $dirs = array();		// dirs | Array | Used for storing directories found by crawldir in source directory.
	var $subdirs = array();		// subdirs | Array | Used for storing list of subdirs found by crawldir in source directory.  Mainly used for creating a 'map' of the directory.
	var $file = '';				// file | String | Used to store the literal location of the file currently being viewed.
	var $dir = '';				// dir | String | Used to store the literal location of the directory currently being viewed.

		//- File info (highlight, view modes)
	var $info = array(); 
			// Lang {if not an image}, extension, whethor or not it is an image, and source
	var $templ = array();  
			// Name, MD5, size...for direct dump to template class.
	
		//- ID's of what user is looking at.
	var $file_id = 0;
	var $dir_id = 0;
	
		//- Directory stuff
	var $is_root = false;
	var $flip_dirs = array();
	var $has_subdir = false;
	var $has_file = false;
	var $has_contents = false;

		//- GeSHi integration
	var $geshi_dir  = '';
	var $geshi_list = array();
	
//! Constructor method - Set the Code Repository core class up and do some things according to mode specified.
	function crs_core($mode, $source_path)
	{
	//! Assign our source path first of all things.
		$this->source_path = $source_path;
		$this->dir_id 		= request_var('d', 0);
		$this->file_id 		= request_var('f', 0); 
		// ^-- Unneeded in $mode: 'browse', but meh.  Just have it in there anyways.	
		// @NOTE: We're going to assume a blank mode is 'browse' throughout to fix an earlier bug...

	//! Let's build our filelist now...
		$this->get_filelist($this->dirs, $this->files, $this->subdirs);
		
	//! ID existence check.  This is needed throughout (non-mode dependent), so don't move it into the mode switch.
		if(!isset($this->dirs[$this->dir_id]))
		{
			trigger_error('INVALID_DIR');
		}
		$this->dir = $this->source_path . $this->dirs[$this->dir_id];
		// Check to see if the directory we want actually exists.
		if(!@file_exists($this->dir))
		{
			trigger_error('NO_SUCH_DIR_IN_REPO');
		}
		
	//! For file-ID dependent modes, check for the files we want too.
		if(in_array($mode, array('download', 'view', 'highlight', 'pipeimage')))
		{
			if(!isset($this->files[$this->dir_id][$this->file_id]))
			{
				trigger_error('INVALID_FILE');
			}
			$this->file = $this->source_path . $this->files[$this->dir_id][$this->file_id];
			if(!@file_exists($this->file))
			{
				trigger_error('NO_SUCH_FILE_IN_REPO');
			}
		}
		
	//! Build the updir tree, depending on the mode...
		if(in_array($mode, array('', 'view', 'highlight', 'browse')))
		{
			$this->flip_dirs = flip_array($this->dirs);
			$this->is_root = ($this->dir == $this->source_path) ? true : false;
		}
		
		switch ($mode)
		{
			case 'pipeimage':
			//! Make sure the file we're looking at /is/ an image for 'pipeimage' mode.
				if(file_fns::check_image($this->file) === false)
				{
					trigger_error('VIEW_FILE_NOT_IMAGE');
				}

			case 'download':
				// Don't really need anything past here for download or pipeimage modes, so bail from the constructor.
				return;
			break;
			
			case 'highlight':
				//! Make sure the file we're looking at is not an image for 'highlight' mode.
				if(file_fns::check_image($this->file) === true)
				{
					trigger_error('NO_HIGHLIGHT_IMAGE');
				}
			case 'view':
			//! Let's figure out some stuff about this file the user seems to admire.
				$this->info = array(
					'ext'			=> file_fns::get_fileext($this->file),
					'is_image'		=> file_fns::check_image($this->file),
				);
				if(!$this->info['is_image'])
				{
					$this->info['lang'] = $this->guess_lang(file_fns::get_fileext($this->file));
				}
				$this->info['file_source'] = $this->display_source($this->file, $mode);
				// Used for direct dump to template system.  Makes it easier to alter later.
				$this->templ = array(
					'DOWNLOAD_NAME'		=> basename($this->file),
					'DOWNLOAD_SIZE'		=> get_formatted_filesize(filesize($this->file)),
					'DOWNLOAD_MD5'		=> md5($this->info['file_source']),
					'DOWNLOAD_TYPE'		=> file_fns::identify_ext(basename($this->file)),
				);
			break;
			
			case '':
			case 'browse':
			//! Pull out our current directory's address...by any means necessary.
				//Do we have subdirs or files to show?
				$this->has_subdir = (isset($this->subdirs[$this->dir_id])) ? true : false;
				$this->has_file = (isset($this->files[$this->dir_id])) ? true : false;
				$this->has_contents = (isset($this->files[$this->dir_id]) || isset($this->subdirs[$this->dir_id])) ? true : false;
			break;
		}
		
		
	} // End crs_core constructor function.
	
/**
* Code Repository methods for Crawldir and caching framework function(s)
* Do.  Not.  Alter.
*/
	
	/**
	* @function 	- Crawldir
	* @version 		- 1.4.1
	* @author	  	- {@link http://www.infinityhouse.org Obsidian}
	* @description	- PHP function for getting the contents of a directory (like scandir), and getting the contents of every subdirectory found (unlike scandir)..
	* @author-notes	- 
	* 				Only let it crawl the directory as necessary. May cause heavy server strain if used constantly.
	* 				Improved slightly in order to better manage bad situations. If directory is invalid, it will return constant DIR_INVALID; no files, constant NO_FILES_FOUND.  Be prepared!
	* 				This function will ignore files and directories specified in in the second and third params, but you /must/ be sure and to have the locations relative to the running script.
	*
	* @license:		- {@link http://opensource.org/licenses/gpl-license.php GNU Public License v2}
	* @copyright   	- (c) 2008, 2009 | Obsidian -- Infinityhouse Creations {@link http://www.infinityhouse.org  Infinityhouse Creations}
	*
	* @param		- string  $path - Filepath to run Crawldir on
	* @param	 	- array  $ignore_dirs - Directories to ignore in the crawl.  This means the contents of such directories will also be ignored.
	* @param	 	- array  $ignore_files - Files to ignore in the crawl.
	*
	* @return		- array|integer - Array of all the results, or a constant for returning errors encountered.
	*/
	function crawldir($path, $ignore_dirs = false, $ignore_files = false)
	{
		if (!@file_exists($path) || !@is_dir($path) || @is_link($path) || !@is_readable($path))
		{
			return DIR_INVALID; 
		}
		$ignore_dirs = (is_array($ignore_dirs)) ? $ignore_dirs : array();
		$ignore_files = (is_array($ignore_files)) ? $ignore_files : array();
		$return['dirs'][] = '/';
		$homepath = $path;
		$default = $return;
		$scan = scandir($path);
		foreach($scan as $key => $item)
		{
			$filepath = $path . '/' . $item;
			$relpath = '/' . $item;
			if($item == '.' || $item == '..' || $item == '.svn' || @is_link($filepath))
			{
				continue; 
			}
			if(is_dir($filepath))
			{
				if(in_array(($filepath), $ignore_dirs))
				{
					continue;
				}
				$subdirs[] = $filepath;
				$return['dirs'][] = $relpath;
				$return['subdirs'][0][] = $relpath;
			}
			else
			{
				if(in_array(($filepath), $ignore_files))
				{
					continue;
				}
				$return['files'][0][] = $relpath;
			}
		}
		if($return === $default)
		{
			return NO_FILES_FOUND;
		}

		$i = 0;  //If anyone knows a way around having to use an incrementer in this, and it's just as effective, LET ME KNOW.
		while(@sizeof($subdirs) > 0)
		{
			$scan = scandir($subdirs[$i]);
			foreach($scan as $key => $item)
			{
				$filepath = $subdirs[$i] . '/' . $item;
				$relpath = substr($filepath, strlen($homepath));
				if($item == '.' || $item == '..' || $item == '.svn' || @is_link($filepath))
				{ 
					continue; 
				}
				if(is_dir($filepath))
				{
					if(in_array(($filepath), $ignore_dirs))
					{
						continue;
					}
					$subdirs[] = $filepath;
					$return['dirs'][] = $relpath;
					$return['subdirs'][$i + 1][] = $relpath;
				}
				else
				{
					if(in_array(($filepath), $ignore_files))
					{
						continue;
					}
					$return['files'][$i + 1][] = $relpath;
				}
			}
			unset($subdirs[$i]);
			$i++;
		}
		return ($return !== $default) ? $return : NO_FILES_FOUND;
	}

	/**
	* Obtains a cached version of the filelist, or will cache new if no cached set is present/invalid.  Will cache filelist for a specified time (or 3 days by default).
	*		Updated, a bit less clutter, more efficient and logical layout now.  Using referencing inputs, no more returned values.
	*/
	function get_filelist(&$dirs, &$files, &$subdirs, $cache_days = 3)
	{
		global $cache;
		if (($filelist = $cache->get('_crs_files')) === false)
		{
			$ignore_files = array('.htaccess');
			$this->build_ignore_list($ignore_files);
			//$ignore_dirs = $this->build_ignore_list(array('cgi_bin')); 
			//$this->build_ignore_list($ignore_dirs);
			// ^-- Uncomment and change the above four (4) lines at your own leisure.  :P
			$filelist = $this->crawldir($this->source_path, (isset($ignore_dirs) ? $ignore_dirs : false), (isset($ignore_files) ? $ignore_files : false));
			if($filelist === DIR_INVALID)
			{
				trigger_error('SOURCE_DIR_DOA');
			}
			if($filelist === NO_FILES_FOUND)
			{
				trigger_error('NO_FILES_FOUND_IN_CRS');
			}
			$cache_length = 86400 * (int) $cache_days;
			
			$cache->put('_crs_files', $filelist, $cache_length);
		}
		$dirs = isset($filelist['dirs']) ? $filelist['dirs'] : false;
		$files = isset($filelist['files']) ? $filelist['files'] : false;
		$subdirs = isset($filelist['subdirs']) ? $filelist['subdirs'] : false;
	}
	
/**
* Main Code Repository methods
*/
	/**
	* Source code obtainment method.
	* Used to provide a clean framework for GeSHi integration.
	*/
	function display_source($filepath, $mode)
	{
	//! Are we going to highlight?   Don't check auths here..
		switch($mode)
		{
			case 'highlight':
				global $geshi, $template, $config;
				if(!class_exists('GeSHi'))
				{
					trigger_error('GESHI_NOT_LOADED');
				}
				$this->init_highlighter(PHPBB_ROOT_PATH . 'includes/geshi/');
			//! Let's try to guess the file's language using the extension...
				if (!$this->geshi_check($this->info['lang']))
				{
					$this->info['lang'] = 'text';
				}
			//! Highlight using GeSHi (http://qbnz.com/highlighter/)
				$geshi = new GeSHi($this->load_file_source($filepath), $this->info['lang'], $this->geshi_dir);
				
				if($config['crs_dump_geshi_css'] == true)
				{
				//! Let's grab ourselves some CSS to speed things up.
					$geshi->enable_classes();
					$template->assign_var('GESHI_STYLE', $geshi->get_stylesheet());
				}
				
				$geshi->set_header_type(GESHI_HEADER_DIV);
				$geshi->set_tab_width(4);  //As per MODDB req's for PHP files...and it looks better anyways.
				$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS, 100);

				$source = $geshi->parse_code();
				if(empty($source))
				{
					trigger_error('SYNTAX_HIGHLIGHT_FAILED');
				}
				$template->assign_var('GESHI_ENABLED', true);
			break;
			
			case 'view':
			default:
				$source = $this->clean_code($this->load_file_source($filepath));
			break;
		}
		return $source;
	}
	
	/**
	* Load a file's source (without altering) and react to errors if necessary.  
	*/
	function load_file_source($filepath)
	{
		global $config;
		if(!@is_readable($filepath))
		{
			trigger_error('CANNOT_READ_SOURCE');
		}
		$source = @file_get_contents($filepath);
		return $source;
	}
	
	/**
	* Cleans out a file's code and prepares it for display.  Parses tabs, spaces, newlines, etc. etc.
	* 	@NOTE: May need modification later for compatibility with Mac formats, but meh.
	*/
	function clean_code($source)
	{
		$pcre_data = array(
				'/\t/'	=> '&nbsp;&nbsp;&nbsp;&nbsp;',
				'/ /'	=> '&nbsp;',
				'/\n/'	=> '<br />',
		);
		$patterns = array_keys($pcre_data);
		$replacements = array_values($pcre_data);
		ksort($patterns);
		ksort($replacements);
		return preg_replace($patterns, $replacements, hsc($source));
	}
	
	/**
	* Dynamically builds an array of parent directories from specified dir, grabs associated IDs, directory name, etc..  
	* Now safe to use with trailing slashes because of {@link file_fns::drop_trailing_slash(); }
	*/
	function dynamic_updir($base_href)
	{
		$return = false;
		$path = file_fns::drop_trailing_slash($this->dir);
		while($path !== $this->source_path || file_fns::updir($path) === $this->source_path)
		{
			$dir = (file_fns::updir($path) === $this->source_path) ? '/' : file_fns::filter_path(file_fns::updir($path), $this->source_path);
			$return[] = array(
				'NAME' 		=> basename(file_fns::drop_trailing_slash(file_fns::filter_path(file_fns::updir($path), $this->source_path))),
				'LINK'		=> append_sid($base_href, 'mode=browse&amp;d=' . $this->flip_dirs[$dir]),
			);
			$path = file_fns::updir($path);
		}
		return $return;
	}
	
	/**
	* Constructs a list of files/directories to ignore and prepends the source path to their location, if you're too lazy to do it yourself.
	* $ignored_items is now a referenced var, this function will now edit the entries.
	*/
	function build_ignore_list(&$ignored_items)
	{
		if(!is_array($ignored_items))
		{
			return false;
		}
		foreach($ignored_items as $key => $ignored_item)
		{
			$ignored_items[$key] = $this->source_path . '/' . $ignored_item;
		}
	}
	
/**
* GeSHi integration methods for the Code Repository.
* For the most part, these functions are copied and slightly altered from eviL<3's Pastebin MOD.
*/
	
	/**
	* Initiate highlighter stuff.
	*/
	function init_highlighter($geshi_dir)
	{
		$this->geshi_dir    = $geshi_dir;
		$this->geshi_list    = $this->geshi_list();
	}

	/**
	* Check if $needle is in one of geshi's supported languages
	*/
	function geshi_check($needle)
	{
		return in_array($needle, $this->geshi_list);
	}
    
	/**
	* List of all geshi langs using physical files
	*/
	function geshi_list()
	{   
		$geshi_list = array();
        
		$d = dir($this->geshi_dir);
		while (false !== ($file = $d->read()))
 		{
			if (in_array($file, array('.', '..')))
			{
				continue;
			}
            
			if (($substr_end = strpos($file, '.' . PHP_EXT)) !== false)
			{
				$geshi_list[] = substr($file, 0, $substr_end);
			}
		}
		$d->close();
        
		return $geshi_list;
	}
	
	/**
	* Play the guessing game with what language a file is written in...expand upon later.
	* Borrowed from GeSHi.
	*/
	function guess_lang($fileext)
	{
		$langs = array(
			'actionscript' 	=> array('as'),
			'ada' 			=> array('a', 'ada', 'adb', 'ads'),
			'apache_conf' 	=> array('conf'),
			'apache' 		=> array('htaccess'),
			'asm' 			=> array('ash', 'asm'),
			'asp' 			=> array('asp'),
			'bash' 			=> array('sh'),
			'c' 			=> array('c', 'h'),
			'c_mac' 		=> array('c', 'h'),
			'caddcl' 		=> array(),
			'cadlisp' 		=> array(),
			'cdfg' 			=> array('cdfg'),
			'cpp' 			=> array('cpp', 'h', 'hpp'),
			'csharp' 		=> array(),
			'css' 			=> array('css'),
			'delphi' 		=> array('dpk', 'dpr'),
			'gif' 			=> array('gif'),
			'html4strict' 	=> array('html', 'htm'),
			'java' 			=> array('java'),
			'javascript' 	=> array('js'),
			'jpeg' 			=> array('jpeg', 'jpg'),
			'lisp' 			=> array('lisp'),
			'lua' 			=> array('lua'),
			'mpasm' 		=> array(),
			'nsis' 			=> array(),
			'objc' 			=> array(),
			'oobas' 		=> array(),
			'oracle8' 		=> array(),
			'pascal' 		=> array('pas'),
			'perl' 			=> array('pl', 'pm'),
			'php' 			=> array('php', 'php5', 'phtml', 'phps', 'php4', 'php6'),
			'png'			=> array('png'),
			'python' 		=> array('py'),
			'qbasic' 		=> array('bi'),
			'sas' 			=> array('sas'),
			'smarty' 		=> array(),
			'vb' 			=> array('bas'),
			'vbnet' 		=> array(),
			'visualfoxpro' 	=> array(),
			'xml' 			=> array('xml'),
		);
		foreach ($langs as $lang => $extensions) 
		{
			foreach ($extensions as $ext) 
			{
				if ($ext == $fileext) 
				{
					return $lang;
				}
			}
		}
		return 'unk';
	}
	
/**
* Extra Code Repository methods.
* These methods are UNUSED currently, or their use within the code has been commented out.  
* They are experimental, untested, and no support will be given for their usage.
*/
	
	/**
	* Looks through the crawldir results for a specific entry (this must be provided as a straight path from the source_path!) and returns the file/dir ID if present.
	* If it's a file, pass @param $is_file as true so the file's name can be dropped for a faster search (search for parent dir first, then the specific file).
	*		@NOTE: Looking for a file is MUCH more strenuous than looking for a directory.
	*
	* Currently unimplemented, intended for later line, may be removed.  Needs rechecked to see if all the mess with slashes is right.
	* Suitable if you want to use the Code Repository as an extension to a bug tracker MOD, though, so it won't be commented out for now.  ;)
	*/
	function find_result($loc, $is_file = false)
	{
		if($is_file)
		{
			$file = $loc;
			$loc = dirname($loc);
		}
		$flip = flip_array($this->dirs);
		if(!isset($flip[$this->source_path . '/' . $loc]))
		{
			return false;
		}
		else
		{
			$return['dir_id'] = $this->source_path . '/' . $flip[$loc];
			if($is_file)
			{
				$flip = flip_array($this->files);
				if(!isset($flip[$this->source_path . '/' . $file]))
				{
					return false;
				}
				else
				{
					$return['file_id'] = $this->source_path . '/' . $flip[$file];
				}
			}
		}
		return $return;
	}
	
	/**
	* Chop up the list of the files and subdirectories into smaller chunks for pagination purposes.
	* Not using a for() loop since it's already failed me in this kinda thing. >_<
	*
	function paginate_listing($list, $start, $per_page = 10)
	{
		//Generate the maximum distance we can go..bit of math here.
		$max = (($start + $per_page) > @sizeof($list)) ? (@sizeof($list)) : $per_page;
		$i = $start;
		while($i < ($start + $max))
		{
			$return[$i] = $list[$i];
			$i++;
		}
		return $return;
	}
	*/
	
} //END crs_core class

?>