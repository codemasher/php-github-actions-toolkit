<?php
/**
 * @created      28.08.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\HTTP\Utils;

use TypeError;
use function array_map, is_array, is_scalar, pathinfo, rawurlencode, strtolower;
use const PATHINFO_EXTENSION;

const CHILLERLAN_PSR7_UTIL_INCLUDES = true;

/**
 * @link http://svn.apache.org/repos/asf/httpd/httpd/branches/1.3.x/conf/mime.types
 */
const MIMETYPES = [
	'3gp'     => 'video/3gpp',
	'7z'      => 'application/x-7z-compressed',
	'aac'     => 'audio/x-aac',
	'ai'      => 'application/postscript',
	'aif'     => 'audio/x-aiff',
	'asc'     => 'text/plain',
	'asf'     => 'video/x-ms-asf',
	'atom'    => 'application/atom+xml',
	'avi'     => 'video/x-msvideo',
	'bmp'     => 'image/bmp',
	'bz2'     => 'application/x-bzip2',
	'cer'     => 'application/pkix-cert',
	'crl'     => 'application/pkix-crl',
	'crt'     => 'application/x-x509-ca-cert',
	'css'     => 'text/css',
	'csv'     => 'text/csv',
	'cu'      => 'application/cu-seeme',
	'deb'     => 'application/x-debian-package',
	'doc'     => 'application/msword',
	'docx'    => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'dvi'     => 'application/x-dvi',
	'eot'     => 'application/vnd.ms-fontobject',
	'eps'     => 'application/postscript',
	'epub'    => 'application/epub+zip',
	'etx'     => 'text/x-setext',
	'flac'    => 'audio/flac',
	'flv'     => 'video/x-flv',
	'gif'     => 'image/gif',
	'gz'      => 'application/gzip',
	'htm'     => 'text/html',
	'html'    => 'text/html',
	'ico'     => 'image/x-icon',
	'ics'     => 'text/calendar',
	'ini'     => 'text/plain',
	'iso'     => 'application/x-iso9660-image',
	'jar'     => 'application/java-archive',
	'jpe'     => 'image/jpeg',
	'jpeg'    => 'image/jpeg',
	'jpg'     => 'image/jpeg',
	'js'      => 'text/javascript',
	'json'    => 'application/json',
	'latex'   => 'application/x-latex',
	'log'     => 'text/plain',
	'm4a'     => 'audio/mp4',
	'm4v'     => 'video/mp4',
	'mid'     => 'audio/midi',
	'midi'    => 'audio/midi',
	'mov'     => 'video/quicktime',
	'mkv'     => 'video/x-matroska',
	'mp3'     => 'audio/mpeg',
	'mp4'     => 'video/mp4',
	'mp4a'    => 'audio/mp4',
	'mp4v'    => 'video/mp4',
	'mpe'     => 'video/mpeg',
	'mpeg'    => 'video/mpeg',
	'mpg'     => 'video/mpeg',
	'mpg4'    => 'video/mp4',
	'oga'     => 'audio/ogg',
	'ogg'     => 'audio/ogg',
	'ogv'     => 'video/ogg',
	'ogx'     => 'application/ogg',
	'pbm'     => 'image/x-portable-bitmap',
	'pdf'     => 'application/pdf',
	'pgm'     => 'image/x-portable-graymap',
	'png'     => 'image/png',
	'pnm'     => 'image/x-portable-anymap',
	'ppm'     => 'image/x-portable-pixmap',
	'ppt'     => 'application/vnd.ms-powerpoint',
	'pptx'    => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'ps'      => 'application/postscript',
	'qt'      => 'video/quicktime',
	'rar'     => 'application/x-rar-compressed',
	'ras'     => 'image/x-cmu-raster',
	'rss'     => 'application/rss+xml',
	'rtf'     => 'application/rtf',
	'sgm'     => 'text/sgml',
	'sgml'    => 'text/sgml',
	'svg'     => 'image/svg+xml',
	'swf'     => 'application/x-shockwave-flash',
	'tar'     => 'application/x-tar',
	'tif'     => 'image/tiff',
	'tiff'    => 'image/tiff',
	'torrent' => 'application/x-bittorrent',
	'ttf'     => 'application/x-font-ttf',
	'txt'     => 'text/plain',
	'wav'     => 'audio/x-wav',
	'webm'    => 'video/webm',
	'wma'     => 'audio/x-ms-wma',
	'wmv'     => 'video/x-ms-wmv',
	'woff'    => 'application/x-font-woff',
	'wsdl'    => 'application/wsdl+xml',
	'xbm'     => 'image/x-xbitmap',
	'xls'     => 'application/vnd.ms-excel',
	'xlsx'    => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xml'     => 'application/xml',
	'xpm'     => 'image/x-xpixmap',
	'xwd'     => 'image/x-xwindowdump',
	'yaml'    => 'text/yaml',
	'yml'     => 'text/yaml',
	'zip'     => 'application/zip',
];

/**
 * Get the mime type for the given file extension
 */
function getMimetypeFromExtension(string $extension):?string{
	return MIMETYPES[strtolower($extension)] ?? null;
}

/**
 * Get the mime type from a file name
 */
function getMimetypeFromFilename(string $filename):?string{
	return getMimetypeFromExtension(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Recursive rawurlencode
 *
 * @param string|string[] $data
 *
 * @return string|string[]
 * @throws \TypeError
 */
function r_rawurlencode($data){

	if(is_array($data)){
		return array_map(__FUNCTION__, $data);
	}

	if(!is_scalar($data) && $data !== null){
		throw new TypeError('$data is neither scalar nor null');
	}

	return rawurlencode((string)$data);
}
