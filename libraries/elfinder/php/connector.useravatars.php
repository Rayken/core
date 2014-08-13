<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2010
 * Date:		$Date: 2013-01-29 17:35:08 +0100 (Di, 29 Jan 2013) $
 * -----------------------------------------------------------------------
 * @author		$Author: wallenium $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 12937 $
 * 
 * $Id: manage_auto_points.php 12937 2013-01-29 16:35:08Z wallenium $
 */
define('EQDKP_INC', true);
$eqdkp_root_path = './../../../';

include_once ($eqdkp_root_path . 'common.php');

if (!register('user')->is_signedin()){
	echo('You have no permission to see this page as you are not logged in'); exit;
}

include_once $eqdkp_root_path.'libraries/elfinder/php/elFinderConnector.class.php';
include_once $eqdkp_root_path.'libraries/elfinder/php/elFinder.class.php';
include_once $eqdkp_root_path.'libraries/elfinder/php/elFinderVolumeDriver.class.php';
include_once $eqdkp_root_path.'libraries/elfinder/php/elFinderVolumeLocalFileSystem.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

$opts = array(
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => register('pfh')->FolderPath('users/'.register('user')->id, 'files'),         // path to files (REQUIRED)
			'URL'           => register('pfh')->FileLink('users/'.register('user')->id.'/', 'files', 'absolute'), // URL to files (REQUIRED)
			'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
			'uploadAllow'	=> array('image/jpeg', 'image/png', 'image/gif'),
			'uploadDeny'	=> array('all'),
			//'uploadOrder'	=> array('allow', 'deny'),
			'disabled'		=> array('extract', 'archive','mkdir', 'mkfile','help','rename','download','edit'),
			'maxFilesInDir' => 10,
			'tmbPathMode'	=> get_chmod(),
		)
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

?>