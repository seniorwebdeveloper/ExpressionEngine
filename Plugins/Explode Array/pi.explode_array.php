<?php
/*
=====================================================
 Explode Array Plugin for ExpressionEngine
=====================================================
 File: pi.explode_array.php
-----------------------------------------------------
 Version: 1.2
-----------------------------------------------------
 Purpose: Explode Array
=====================================================
*/

$plugin_info = array(
						'pi_name'			=> 'Explode Array',
						'pi_version'		=> '1.2',
						'pi_author'			=> 'Sean Trane',
						'pi_author_url'		=> 'http://seantrane.com',
						'pi_description'	=> 'Explodes an array based on a given separator',
						'pi_usage'			=> explode_array::usage()
					);

Class Explode_array
{
  var $return_data = '';
    
  // ------------------------
	// Constructor
	// ------------------------
	
	function Explode_array()
	{
	    global $TMPL;
	    
      // fetch tagdata
      $str = $TMPL->tagdata;
			
      // prepare for cleaning up params
      $dirty = array(':SPACE:', ':QUOTE:', ':SLASH:', ':LD:', ':RD:');
      $clean = array(' ', '"', '/', '{', '}');
      
      // fetch tag params
      $separator_param = $TMPL->fetch_param('separator');
      $style_param = $TMPL->fetch_param('style');
      $name_param = $TMPL->fetch_param('name');
      $urlprefix_param = $TMPL->fetch_param('urlprefix');
      
      // clean up tag params
      $separator_param = str_replace($dirty, $clean, $separator_param);
      $style_param = str_replace($dirty, $clean, $style_param);
      $name_param = str_replace($dirty, $clean, $name_param);
      $urlprefix_param = str_replace($dirty, $clean, $urlprefix_param);
      
      // explode array
			$str2 = '';
			$iCounter = 1;
			$aOptionArray = explode($separator_param,$str);
      if ($style_param == 'option') {
      	foreach ($aOptionArray as $sOptionValue) {
        	$aOptionValue = explode(':', $sOptionValue);
        	$str2 .= '<option value="';
        	$str2 .= (!empty($aOptionValue[1])) ? $aOptionValue[1] : $aOptionValue[0];
        	$str2 .= '">'.$aOptionValue[0].'</option>'."\n";
        	//$sOptionValue = '';
      	}
			} elseif ($style_param == 'list') {
      	foreach ($aOptionArray as $sOptionValue) {
        	$str2 .= '<li>'.$sOptionValue.'</li>'."\n";
        	//$sOptionValue = '';
      	}
			} elseif ($style_param == 'linklist') {
      	foreach ($aOptionArray as $sOptionValue) {
        	$str2 .= '<li><a href="'.$urlprefix_param.strtr(trim($sOptionValue),array("'" => '',' ' => '_')).'">'.str_replace("'",'',trim($sOptionValue)).'</a></li>'."\n";
        	//$sOptionValue = '';
      	}
			} elseif ($style_param == 'checkboxlist') {
      	foreach ($aOptionArray as $sOptionValue) {
        	$aOptionValue = explode(':', $sOptionValue);
        	$str2 .= '<li><input type="checkbox" name="'.$name_param.$iCounter.'" id="'.$name_param.$iCounter.'" value="';
        	$str2 .= (!empty($aOptionValue[1])) ? $aOptionValue[1] : $aOptionValue[0];
        	$str2 .= '" /> <label for="'.$name_param.$iCounter.'">'.$aOptionValue[0].'</label></li>'."\n";
        	//$sOptionValue = '';
					$iCounter += 1;
      	}
			} elseif ($style_param == 'radiolist') {
      	foreach ($aOptionArray as $sOptionValue) {
        	$aOptionValue = explode(':', $sOptionValue);
        	$str2 .= '<li><input type="radio" name="'.$name_param.'" id="'.$name_param.$iCounter.'" value="';
        	$str2 .= (!empty($aOptionValue[1])) ? $aOptionValue[1] : $aOptionValue[0];
        	$str2 .= '" /> <label for="'.$name_param.$iCounter.'">'.$aOptionValue[0].'</label></li>'."\n";
        	//$sOptionValue = '';
					$iCounter += 1;
      	}
			} else {
      	foreach ($aOptionArray as $sOptionValue) {
        	$str2 .= $sOptionValue.'<br />'."\n";
        	//$sOptionValue = '';
      	}
			}
			
	    $this->return_data = trim($str2);
	}
  
  // ------------------------
	// Plugin Usage
	// ------------------------
  
	function usage()
	{
		ob_start(); 
		?>
This plugin explodes an array by a given separator value into a specified display style.

To use this plugin, wrap anything you want to be processed by it between these tag pairs:

- &lt;OPTION&gt; Tags using vertical bars -

{exp:explode_array separator="|" style="option"}{your_field_data}{/exp:explode_array}

- &lt;LI&gt; Tags using commas -

{exp:explode_array separator="," style="list"}{your_field_data}{/exp:explode_array}

		<?php
    $buffer = ob_get_contents();
        
    ob_end_clean(); 
    
    return $buffer;
  } // END
}

?>