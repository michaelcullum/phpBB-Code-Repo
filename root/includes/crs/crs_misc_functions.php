<?php
/**
*
*===================================================================
*
*  phpBB Code Repository -- Functions File
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
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

/**
* Get mimetype. Utilize mime_content_type if the function exists.
* (Function copied from download/file.php, phpBB 3.0.0)
* Altered in phpBB Code Repository 1.0.2 to use guess_mimetype function if mime_content_type fails or does not exist.
*/
function get_mimetype($filename)
{
	$mimetype = '';

	if(function_exists('mime_content_type'))
	{
		$mimetype = mime_content_type($filename);
	}
	
	// Check if mime_content_type failed, or doesn't exist, and if so, use guess_mimetype
	if(!$mimetype || $mimetype == '')
	{
		$mimetype = guess_mimetype($filename);
	}
	
	// Some browsers choke on a mimetype of application/octet-stream
	if(!$mimetype || $mimetype == 'application/octet-stream')
	{
		$mimetype = 'application/octetstream';
	}

	return $mimetype;
}

/**
* Play the guessing game with what mimetype we should use...
* Function guesses mimetype VIA file's extension, so beware.
*/
function guess_mimetype($filename)
{
	// Get extension...
	$fileext = file_fns::get_fileext($filename);
	
	$mimetypes = array(
		'application/envoy'							=> array('evy'),
		'application/fractals'						=> array('fif'),
		'application/futuresplash'					=> array('spl'),
		'application/hta'							=> array('hta'),
		'application/internet-property-stream'		=> array('acx'),
		'application/mac-binhex40'					=> array('hqx'),
		'application/msword'						=> array('doc', 'dot'),
		'application/octet-stream'					=> array('bin', 'class', 'dms', 'exe', 'lha', 'lzh'),
		'application/oda'							=> array('oda'),
		'application/olescript'						=> array('axs'),
		'application/pdf'							=> array('pdf'),
		'application/pics-rules'					=> array('prf'),
		'application/pkcs10'						=> array('p10'),
		'application/pkix-crl'						=> array('crl'),
		'application/postscript'					=> array('ai', 'eps', 'ps'),
		'application/rtf'							=> array('rtf'),
		'application/set-payment-initiation'		=> array('setpay'),
		'application/set-registration-initiation'	=> array('setreg'),
		'application/vnd.ms-excel'					=> array('xla', 'xlc', 'xlm', 'xls', 'xlt', 'xlw'),
		'application/vnd.ms-outlook'				=> array('msg'),
		'application/vnd.ms-pkicertstore'			=> array('sst'),
		'application/vnd.ms-pkiseccat'				=> array('cat'),
		'application/vnd.ms-pkistl'					=> array('stl'),
		'application/vnd.ms-powerpoint'				=> array('pot', 'pps', 'ppt'),
		'application/vnd.ms-project'				=> array('mpp'),
		'application/vnd.ms-works'					=> array('wcm', 'wdb', 'wks', 'wps'),
		'application/winhlp'						=> array('hlp'),
		'application/x-bcpio'						=> array('bcpio'),
		'application/x-cdf'							=> array('cdf'),
		'application/x-compress'					=> array('z'),
		'application/x-compressed'					=> array('tgz'),
		'application/x-cpio'						=> array('cpio'),
		'application/x-csh'							=> array('csh'),
		'application/x-director'					=> array('dcr', 'dir', 'dxr'),
		'application/x-dvi'							=> array('dvi'),
		'application/x-gtar'						=> array('gtar'),
		'application/x-gzip'						=> array('gz'),
		'application/x-hdf'							=> array('hdf'),
		'application/x-internet-signup'				=> array('ins', 'isp'),
		'application/x-iphone'						=> array('iii'),
		'application/x-javascript'					=> array('js'),
		'application/x-latex'						=> array('latex'),
		'application/x-msaccess'					=> array('mdb'),
		'application/x-mscardfile'					=> array('crd'),
		'application/x-msclip'						=> array('clp'),
		'application/x-msdownload'					=> array('dll'),
		'application/x-msmediaview'					=> array('m13', 'm14', 'mvb'),
		'application/x-msmetafile'					=> array('wmf'),
		'application/x-msmoney'						=> array('mny'),
		'application/x-mspublisher'					=> array('pub'),
		'application/x-msschedule'					=> array('scd'),
		'application/x-msterminal'					=> array('trm'),
		'application/x-mswrite'						=> array('wri'),
		'application/x-netcdf'						=> array('cdf', 'nc'),
		'application/x-perfmon'						=> array('pma', 'pmc', 'pml', 'pmr', 'pmw'),
		'application/x-pkcs12'						=> array('p12', 'pfx'),
		'application/x-pkcs7-certificates'			=> array('p7b', 'spc'),
		'application/x-pkcs7-certreqresp'			=> array('p7r'),
		'application/x-pkcs7-mime'					=> array('p7c', 'p7m'),
		'application/x-pkcs7-signature'				=> array('p7s'),
		'application/x-sh'							=> array('sh'),
		'application/x-shar'						=> array('shar'),
		'application/x-shockwave-flash'				=> array('swf'),
		'application/x-stuffit'						=> array('sit'),
		'application/x-sv4cpio'						=> array('sv4cpio'),
		'application/x-sv4crc'						=> array('sv4crc'),
		'application/x-tar'							=> array('tar'),
		'application/x-tcl'							=> array('tcl'),
		'application/x-tex'							=> array('tex'),
		'application/x-texinfo'						=> array('texi', 'texinfo'),
		'application/x-troff'						=> array('roff', 't', 'tr'),
		'application/x-troff-man'					=> array('man'),
		'application/x-troff-me'					=> array('me'),
		'application/x-troff-ms'					=> array('ms'),
		'application/x-ustar'						=> array('ustar'),
		'application/x-wais-source'					=> array('src'),
		'application/x-x509-ca-cert'				=> array('cer', 'crt', 'der'),
		'application/ynd.ms-pkipko'					=> array('pko'),
		'application/zip'							=> array('zip'),	
		'audio/basic'								=> array('au', 'snd'),
		'audio/mid'									=> array('mid', 'rmi'),	
		'audio/mpeg'								=> array('mp3'),	
		'audio/x-aiff'								=> array('aif', 'aifc', 'aiff'),
		'audio/x-mpegurl'							=> array('m3u'),
		'audio/x-pn-realaudio'						=> array('ra', 'ram'),	
		'audio/x-wav'								=> array('wav'),	
		'image/bmp'									=> array('bmp'),
		'image/cis-cod'								=> array('cod'),
		'image/gif'									=> array('gif'),	
		'image/ief'									=> array('ief'),
		'image/jpeg'								=> array('jpe', 'jpeg', 'jpg'),
		'image/pipeg'								=> array('jfif'),
		'image/svg+xml'								=> array('svg'),
		'image/tiff'								=> array('tif', 'tiff'),
		'image/x-cmu-raster'						=> array('ras'),
		'image/x-cmx'								=> array('cmx'),
		'image/x-icon'								=> array('ico'),	
		'image/x-portable-anymap'					=> array('pnm'),
		'image/x-portable-bitmap'					=> array('pbm'),
		'image/x-portable-graymap'					=> array('pgm'),
		'image/x-portable-pixmap'					=> array('ppm'),
		'image/x-rgb'								=> array('rgb'),
		'image/x-xbitmap'							=> array('xbm'),
		'image/x-xpixmap'							=> array('xpm'),
		'image/x-xwindowdump'						=> array('xwd'),
		'message/rfc822'							=> array('mht', 'mhtml', 'nws'),
		'text/css'									=> array('css'),
		'text/h323'									=> array('323'),
		'text/html'									=> array('htm', 'html', 'stm'),
		'text/iuls'									=> array('uls'),
		'text/plain'								=> array('bas', 'c', 'h', 'txt'),
		'text/richtext'								=> array('rtx'),
		'text/scriptlet'							=> array('sct'),
		'text/tab-separated-values'					=> array('tsv'),
		'text/webviewhtml'							=> array('htt'),
		'text/x-component'							=> array('htc'),
		'text/x-setext'								=> array('etx'),
		'text/x-vcard'								=> array('vcf'),
		'video/mpeg'								=> array('mp2', 'mpa', 'mpe', 'mpeg', 'mpg', 'mpv2'),
		'video/quicktime'							=> array('mov', 'qt'),
		'video/x-la-asf'							=> array('lsf', 'lsx'),
		'video/x-ms-asf'							=> array('asf', 'asr', 'asx'),
		'video/x-msvideo'							=> array('avi'),
		'video/x-sgi-movie'							=> array('movie'),
		'x-world/x-vrml'							=> array('flr', 'vrml', 'wrl', 'wrz', 'xaf', 'xof'),
	);
	foreach ($mimetypes as $mimetype => $extensions) 
	{
		foreach ($extensions as $ext) 
		{
			if ($ext == $fileext) 
			{
				return $mimetype;
			}
		}
	}
	return '';
}

/**
* Flips array's keys & values and if the values are arrays themselves, flip /their/ keys & values instead.
*/
function flip_array($array)
{
	foreach($array as $key => $val)
	{
		if(is_array($key))
		{
			foreach($key as $sub_k => $sub_v)
			{
				$flip[$key][$sub_v] = $sub_k; 
			}
		}
		else
		{
			$flip[$val] = $key;
		}
	}
	return $flip;
}

/**
* Adjust destination path (no trailing slash), and make it safe to use.
* Ripped from adm/index.php
*/
function sanitize_filepath($path)
{
	if (substr($path -1, 1) == '/' || substr($path, -1, 1) == '\\')
	{
		$path = substr($path, 0, -1);
	}

	$path = str_replace(array('../', '..\\', './', '.\\'), '', $path);
	if ($path && ($path[0] == '/' || $path[0] == "\\"))
	{
		$path = '';
	}

		$path = trim($path);
	
	// Make sure no NUL byte is present...
	if (strpos($path, "\0") !== false || strpos($path, '%00') !== false)
	{
		$path = '';
	}
	
	// Should be safe now. Return the value...
	return $path;
}

/**
* Checks to see if the installed version of the Code Repository is current.
*/
function check_crs_version(&$up_to_date, &$latest_version, &$announcement_url)
{
	global $user, $config;
	// Check the version, load out remote version check file!
	$errstr = '';
	$errno = 0;
	$info = get_remote_file('fail.infinityhouse.org', '/version', ((!defined('CRS_DEV_COPY')) ? 'crs.txt' : 'crs_dev.txt'), $errstr, $errno);
	if ($info === false)
	{
		trigger_error($errstr, E_USER_WARNING);
	}
	$info = explode("\n", $info);
	$latest_version = trim($info[0]);
	$announcement_url = htmlspecialchars(trim($info[1]));
	$up_to_date = (!version_compare(str_replace('rc', 'RC', strtolower($config['crs_version'])), str_replace('rc', 'RC', strtolower($latest_version)), '<'));
}

/**
* Loads Code Repository installation information.
*/
function load_crs_install_info()
{
	global $user;
	$install_info = 'cGhwQkIgQ29kZSBSZXBvc2l0b3J5IDxzdHJvbmcgdGl0bGU9IiUx';
	$install_info .= 'JHMiPnYlMiRzPC9zdHJvbmc+ICZjb3B5OyAyMDA4LCAyMDA5IDxh';
	$install_info .= 'IGhyZWY9Imh0dHA6Ly93d3cuaW5maW5pdHlob3VzZS5vcmcvIiBz';
	$install_info .= 'dHlsZT0iZm9udC13ZWlnaHQ6IGJvbGQ7Ij5PYnNpZGlhbjwvYT4=';
	$install_info = base64_decode($install_info);
	if(!defined('CRS_INSTALL_CHECKSUM') || md5($install_info) !== CRS_INSTALL_CHECKSUM)
	{
		trigger_error(base64_decode(CRS_HASHFAIL_MESSAGE));
	}
	$user->lang['TRANSLATION_INFO'] = $user->lang['TRANSLATION_INFO'] . (($user->lang['TRANSLATION_INFO'] != '') ? '<br />' : '') . sprintf($install_info, CRS_VERSION_BIG, CRS_VERSION);
}

// This function copied from GeSHi.

/**
* Secure replacement for PHP built-in function htmlspecialchars().
*
* See ticket #427 (http://wush.net/trac/wikka/ticket/427) for the rationale
* for this replacement function.
*
* The INTERFACE for this function is almost the same as that for
* htmlspecialchars(), with the same default for quote style; however, there
* is no 'charset' parameter. The reason for this is as follows:
*
* The PHP docs say:
*	  "The third argument charset defines character set used in conversion."
*
* I suspect PHP's htmlspecialchars() is working at the byte-value level and
* thus _needs_ to know (or asssume) a character set because the special
* characters to be replaced could exist at different code points in
* different character sets. (If indeed htmlspecialchars() works at
* byte-value level that goes some  way towards explaining why the
* vulnerability would exist in this function, too, and not only in
* htmlentities() which certainly is working at byte-value level.)
*
* This replacement function however works at character level and should
* therefore be "immune" to character set differences - so no charset
* parameter is needed or provided. If a third parameter is passed, it will
* be silently ignored.
*
* In the OUTPUT there is a minor difference in that we use '&#39;' instead
* of PHP's '&#039;' for a single quote: this provides compatibility with
*	  get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES)
* (see comment by mikiwoz at yahoo dot co dot uk on
* http://php.net/htmlspecialchars); it also matches the entity definition
* for XML 1.0
* (http://www.w3.org/TR/xhtml1/dtds.html#a_dtd_Special_characters).
* Like PHP we use a numeric character reference instead of '&apos;' for the
* single quote. For the other special characters we use the named entity
* references, as PHP is doing.
*
* @author	  {@link http://wikkawiki.org/JavaWoman Marjolein Katsma}
*
* @license	 http://www.gnu.org/copyleft/lgpl.html
*			  GNU Lesser General Public License
* @copyright   Copyright 2007, {@link http://wikkawiki.org/CreditsPage
*			  Wikka Development Team}
*
* @access	  public
* @param	   string  $string string to be converted
* @param	   integer $quote_style
*					  - ENT_COMPAT:   escapes &, <, > and double quote (default)
*					  - ENT_NOQUOTES: escapes only &, < and >
*					  - ENT_QUOTES:   escapes &, <, >, double and single quotes
* @return	  string  converted string
*/
function hsc($string, $quote_style = ENT_COMPAT) 
{
	// init
	$aTransSpecchar = array(
		'&' => '&amp;',
		'"' => '&quot;',
		'<' => '&lt;',
		'>' => '&gt;'
		);					  // ENT_COMPAT set

	if (ENT_NOQUOTES == $quote_style)	   // don't convert double quotes
	{
		unset($aTransSpecchar['"']);
	}
	elseif (ENT_QUOTES == $quote_style)	 // convert single quotes as well
	{
		$aTransSpecchar["'"] = '&#39;'; // (apos) htmlspecialchars() uses '&#039;'
	}

	// return translated string
	return strtr($string,$aTransSpecchar);
}

/**
* PHP4 Scandir alternative function, altered for phpBB MODDB specs
* Pulled from "Scandir for PHP4" blog post by Cory S.N. LaViska, at http://abeautifulsite.net/notebook/59 
* No context parameter, however.  Oh well...
*/
if(!function_exists('scandir')) 
{
	function scandir($directory, $sorting_order = 0)
	{
		$dh = opendir($directory);
		while(($filename = readdir($dh)) !== false)
		{
			$files[] = $filename;
		}
		if($sorting_order == 0)
		{
			sort($files);
		}
		else
		{
			rsort($files);
		}
		return($files);
	}
}

/**
* PHP4 File_get_contents alternative function, altered for phpBB MODDB specs, and for more logical layout.
* Pulled from "file_get_contents function for PHP 4" blog post, at http://www.nutt.net/2006/07/08/file_get_contents-function-for-php-4/
* Function's author was not stated on blog post, however.
*/
if (!function_exists('file_get_contents'))
{
	function file_get_contents($filename)
	{
		$handle = fopen($filename, "r");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		return $contents;
	}
} 


/**
* PHP4 File_put_contents alternative function, altered for phpBB MODDB specs.
* Pulled from "file_put_contents" snippet post, at http://snipplr.com/view/2579/fileputcontents/
* Author is "mafro", posted on 05/02/07.   License for snippet not stated.
*
* Included for now just for the heck of it, may be used later.  O_o
*/

//Quick, let's define this constant if it isn't there.
if (!defined('FILE_APPEND'))
{
	define('FILE_APPEND', 1);
}

if (!function_exists('file_put_contents'))
{
	function file_put_contents($file, $data, $flag = false) 
	{
		$mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a+' : 'w+';
		$fp = fopen($file, $mode);
		if ($fp === false) 
		{
			return 0;
		} 
		else 
		{
			if (is_array($data)) 
			{
				$data = implode($data);
			}
			$bytes_written = fwrite($fp, $data);
			fclose($fp);
			return $bytes_written;
		}
	}
}
?>
