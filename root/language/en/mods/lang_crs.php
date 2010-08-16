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

//ONOZ IT BROKED, HOMGBBQ! messages.
	'INVALID_DIR'				=> 'Invalid directory specified',
	'INVALID_FILE'				=> 'Invalid file specified',
	'NO_SUCH_FILE_IN_REPO'		=> "Could not load the file's source because the file could not be found on the server.",
	'NO_SUCH_DIR_IN_REPO'		=> "Could not load the directory's contents because the directory could not be found on the server.",
	'SOURCE_DIR_DOA'			=> 'The directory containing the files for the Repository either could not be accessed or was not found.',
	'CANNOT_READ_SOURCE'		=> "The file's source code could not be accessed.",
	'NO_FILES_FOUND_IN_CRS'		=> 'No files were found in the Code Repository.',
	'CRS_DISABLED'				=> 'The Code Repository is currently disabled.',
	'CRS_INSTALL_DIR_PRESENT'	=> 'The Code Repository cannot be accessed while the install directory is present.  Please delete or remove it to continue.',
	'VIEW_FILE_NOT_IMAGE'		=> 'Viewing a file in the image mode is not allowed.',
	'NO_HIGHLIGHT_IMAGE'		=> 'Viewing an image in the highlight mode is not allowed.',
	'GESHI_NOT_LOADED'			=> 'The GeSHi Syntax Highlighter could not be loaded.',

//Authorization failure messages
	'NO_AUTHS_CRS_VIEW'			=> 'You are not permitted to view the Code Repository.',
	'NO_AUTHS_CRS_FILE'			=> 'You are not permitted to view the contents of files in the Code Repository.',
	'NO_AUTHS_CRS_IMAGE'		=> 'You are not permitted to view images in the Code Repository.',
	'NO_AUTHS_CRS_HIGHLIGHT'	=> 'You are not permitted to use source code highlighting in the Code Repository.',
	'NO_AUTHS_CRS_DOWNLOAD'		=> 'You are not permitted to download files in the Code Repository.',

//The Download bar.  No access for those under 21.  :P
	'DOWNLOAD_MD5'		=> 'MD5',
	'DOWNLOAD_NAME'		=> 'Filename',
	'DOWNLOAD_TYPE'		=> 'Filetype',
	'DOWNLOAD_SIZE'		=> 'Filesize',
	'DOWNLOAD_ME'		=> 'DOWNLOAD FILE',

//View directory, view source code, and misc.
	'CODE_REPO_ROOT'			=> 'Code Repository Root Directory',
	'GO_UPDIR'					=> 'Go up a directory',
	'GO_BROWSE_DIR'				=> 'Browse parent directory',
	
	'QUICK_DOWNLOAD'			=> 'Download File',
	'VIEW_CODE'					=> 'View code',
	'VIEW_SOURCE_CODE'			=> 'Viewing Source Code',
	'VIEW_REPO_IMAGE'			=> 'Viewing Image',
	'VIEW_HIGHLIGHTED_CODE'		=> 'Viewing Highlighted Source Code',
	
	'VIEW_CODE_REPO'			=> 'Code Repository',
	'SUBDIRS'					=> 'Subdirectories',
	'FILES'						=> 'Files',
	
	'DIR_CONTENTS'				=> 'Directory Contents',
	'FILETYPE_DIR'				=> 'Directory',
	'NO_CONTENTS'				=> 'There are currently no subdirectories or files within the current directory.',
	
	'NO_SUBDIRS'				=> 'There are currently no subdirectories within the current directory.',
	'NO_FILES'					=> 'There are currently no files within the current directory.',
	
	'CRS_TITLE'					=> 'Code Repository',
	'VIEWING_CRS'				=> 'Viewing Code Repository',
	'BACK_CODE_REPO_MAIN'		=> 'Back to the Code Repository Main Page',
	
	'CRS_ABOUT'					=> 'About phpBB Code Repository',
	'ENABLE_HIGHLIGHTING'		=> 'Enable GeSHi Highlighting',
	'DISABLE_HIGHLIGHTING'		=> 'Disable GeSHi Highlighting',
	
	'POST_LANG_FILE'			=> ' file',
	
	'HIGHLIGHT_POWER'			=> 'Syntax Highlighting powered by <a href="http://qbnz.com/highlighter/">GeSHi</a> %s',
	
	'CODE_REPO_ABOUT'			=> "The phpBB Code Repository was developed by <a href=\"http://www.infinityhouse.org/\" style='font-weight: bold;'>Obsidian</a>. 
									It is now developed by <a href=\"http://www.unknownbliss.co.uk/\" style='font-weight: bold;'>Unknown Bliss</a>. Thanks go to
									the developers and creatores of GeSHi Highlighting and Evil<3 for his pastebin mod which inspired the orginal author to make 
									this modification.",
	
//Identifying filetypes...
	'LANGUAGES'		=> array(
		'actionscript'		=> 'ActionScript',
		'ada'				=> 'Ada',
		'apache_conf'		=> 'Apache Configuration',
		'apache'			=> 'Apache HTACCESS',
		'applescript'		=> 'AppleScript',
		'asm'				=> 'x86 Assembler',
		'asp'				=> 'ASP',
		'autoit'			=> 'AutoIt',
		'bash'				=> 'Bash',
		'blitzbasic'		=> 'BlitzBasic',
		'bnf'				=> 'BNF',
		'c'					=> 'C',
		'c_mac'				=> 'C (Mac)',
		'caddcl'			=> 'CAD DCL',
		'cadlisp'			=> 'CAD Lisp',
		'cfdg'				=> 'CFDG',
		'cfm'				=> 'ColdFusion',
		'cpp-qt'			=> 'C++ (QT)',
		'cpp'				=> 'C++',
		'csharp'			=> 'C#',
		'css-gen.cfg'		=> 'C#',
		'css'				=> 'CSS',
		'c_mac'				=> 'C (Mac)',
		'd'					=> 'D',
		'delphi'			=> 'Delphi',
		'diff'				=> 'Diff',
		'div'				=> 'DIV',
		'dos'				=> 'DOS',
		'eiffel'			=> 'Eiffel',
		'fortran'			=> 'Fortran',
		'freebasic'			=> 'FreeBasic',
		'gif'				=> 'GIF Image',
		'gml'				=> 'GML',
		'groovy'			=> 'Groovy',
		'html4strict'		=> 'HTML',
		'idl'				=> 'Uno Idl',
		'ini'				=> 'INI',
		'inno'				=> 'Inno',
		'io'				=> 'Io',
		'java'				=> 'Java',
		'java5'				=> 'Java(TM) 2 Platform Standard Edition 5.0',
		'javascript'		=> 'Javascript',
		'jpeg'				=> 'JPEG Image',
		'latex'				=> 'LaTeX',
		'lisp'				=> 'Lisp',
		'lua'				=> 'Lua',
		'matlab'			=> 'Matlab M',
		'mirc'				=> 'mIRC Scripting',
		'mpasm'				=> 'Microchip Assembler',
		'mysql'				=> 'MySQL',
		'nsis'				=> 'NSIS',
		'objc'				=> 'Objective C',
		'ocaml-brief'		=> 'OCaml',
		'ocaml'				=> 'OCaml',
		'oobas'				=> 'OpenOffice.org Basic',
		'oracle8'			=> 'Oracle 8 SQL',
		'pascal'			=> 'Pascal',
		'perl'				=> 'Perl',
		'php-brief'			=> 'PHP (brief)',
		'php'				=> 'PHP',
		'plsql'				=> 'PL/SQL',
		'png'				=> 'PNG Image',
		'python'			=> 'Python',
		'qbasic'			=> 'QBasic/QuickBASIC',
		'rails'				=> 'Rails',
		'reg'				=> 'Microsoft Registry',
		'robots'			=> 'robots.txt',
		'ruby'				=> 'Ruby',
		'sas'				=> 'SAS',
		'scheme'			=> 'Scheme',
		'sdlbasic'			=> 'sdlBasic',
		'smalltalk'			=> 'Smalltalk',
		'smarty'			=> 'Smarty',
		'sql'				=> 'SQL',
		'tcl'				=> 'TCL',
		'text'				=> 'Text',
		'thinbasic'			=> 'thinBasic',
		'tsql'				=> 'T-SQL',
		'unk'				=> 'Unknown',
		'vb'				=> 'Visual Basic',
		'vbnet'				=> 'vb.net',
		'vhdl'				=> 'VHDL',
		'visualfoxpro'		=> 'Visual Fox Pro',
		'winbatch'			=> 'Winbatch',
		'xml'				=> 'XML',
		'xpp'				=> 'X++',
		'z80'				=> 'ZiLOG Z80 Assembler',
	),
));

?>
