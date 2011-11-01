<?php

/*
=====================================================
 Title Case Plugin for ExpressionEngine
=====================================================
 File: pi.title_case.php
-----------------------------------------------------
 Version: 1.1
-----------------------------------------------------
 Purpose: Convert string to "smart uppercase"
-----------------------------------------------------
 Thanks to Ardamis for the original function...
 http://www.ardamis.com
=====================================================
*/

$plugin_info = array(
	'pi_name'			=> 'Title Case',
	'pi_version'		=> '1.1',
	'pi_author'			=> 'Sean Trane',
	'pi_author_url'		=> 'http://seantrane.com/',
	'pi_description'	=> 'Convert string to "Title Case is a Smart Uppercase"',
	'pi_usage'			=> Title_case::usage()
);

class Title_case {
	
	var $return_data;
    // ----------------------------------------
    //  Title Case
    // ----------------------------------------
	
	function Title_case($str = '') {
		global $TMPL, $FNS;
		$fn_title = ($str == '') ? $TMPL->tagdata : $str;
		$fn_title = ' '.$fn_title.' '; // append/prepend spaces to workaround "in-word" capitalization
		$fn_title = str_replace("_", ' ', $fn_title); // find underscores and convert to spaces
		$fn_title = preg_replace("/\"\s/", '&#8221; ', $fn_title); // find default double quotes and convert to XHTML double quotes
		$fn_title = preg_replace("/\s\"/", ' &#8220;', $fn_title); // find default double quotes and convert to XHTML double quotes
		$fn_title = preg_replace("/'\s/", '&#8217; ', $fn_title); // find default single quotes and convert to XHTML single quotes
		$fn_title = preg_replace("/\s'/", ' &#8216;', $fn_title); // find default single quotes and convert to XHTML single quotes
		$fn_title = preg_replace("/&#8220;/", '&#8220; ', $fn_title); // find double quotes and add a space behind each instance
		$fn_title = preg_replace("/&#8216;/", '&#8216; ', $fn_title); // find single quotes and add a space behind each instance
		$fn_title = preg_replace("/(?<=(?<!:|;)\W)(A|An|And|At|But|By|Else|For|From|If|In|Into|Nor|Of|On|Or|The|To|With)(?=\W)/e", 'strtolower("$1")', ucwords($fn_title));  // de-capitalize certain words unless they follow a colon or semicolon
		$specialwords = array("iPod", "iMovie", "iTunes", "iMac", "iPhone", "HTML", ".html", "PHP", ".php", "RSS", ".rss", "XML", ".xml", "CSS", ".css", "PDF", ".pdf", "PNG", ".png", "JPG", ".jpg", "GIF", ".gif", " EE ", " GUI ", "eProducts", "eBooks", "eReader"); // form a list of specially treated words
		$fn_title = str_ireplace($specialwords, $specialwords, $fn_title); // replace the specially treated words
		$fn_title = preg_replace("/&#8220; /", '&#8220;', $fn_title); // remove the space behind double quotes
		$fn_title = preg_replace("/&#8216; /", '&#8216;', $fn_title); // remove the space behind single quotes
		$fn_title = trim($fn_title); // trim spaces
		$this->return_data = $fn_title;
	}
	
	// END
	
	// ----------------------------------------
	//  Plugin Usage
	// ----------------------------------------
	
	// This function describes how the plugin is used.
	//  Make sure and use output buffering
	
	function usage() {
		ob_start(); 
?>
Wrap anything you want to be processed between the tag pairs.

{exp:title_case}

text you want processed

{/exp:title_case}

<?php
		$buffer = ob_get_contents();
		ob_end_clean(); 
		return $buffer;
	}
	// END
	
}
// END CLASS
?>