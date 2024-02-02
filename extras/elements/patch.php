<?php
/**
 * 
 * @version             See field version manifest file
 * @package             See field name manifest file
 * @author				Gregorio Nuti
 * @copyright			See field copyright manifest file
 * @license             GNU General Public License version 2 or later
 * 
 */

// No direct access
defined('_JEXEC') or die;

// Define DS
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Namespaces
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

jimport('joomla.filesystem.file');

class JFormFieldPatch extends JFormField {
    
    protected $type = 'Patch';
    
    protected function getInput() {
    	
    	// Human defined variables
        $extension_family = 'plugin';
        $extension_family_short = 'plg';
        $plugin_type = 'content';
        $extension_name = 'paramsbackup';
        
        // General variables
        $joomlaVersion = JVERSION;
        $uri = Uri::getInstance();
        
        // Get the parameter from the current url to apply Joomla 3 patch or not
        $execute = $uri->getVar('execute');
        
        // Apply the patch upon url check
        if ($execute == 'applyj3patch') {
    	
			if (version_compare($joomlaVersion, "3.99.99", "<=")) {
				
				// Extension folder
				$basePath = str_replace('extras'.DS.'elements', '', dirname(__FILE__));
		
				// Get manifest file content
				$xmlFile = file_get_contents($basePath.'paramsbackup.xml');
		
				// Change manifest data accordinig to Joomla 3 format
				if ($xmlFile) {
					$xmlFile = str_replace('layout="joomla.form.field.radio.switcher"', 'class="btn-group" layout="joomla.form.field.radio"', $xmlFile);
					
					// Write manifest data
					JFile::write($basePath . 'paramsbackup.xml' , $xmlFile);
					
					// Mark patch as applied
					$xmlPatchStatus = true;
				}
				
				// Language folder
				// TODO: cycle every language listed in manifest file (not only en-GB)
				$langPath = str_replace('plugins'.DS.'content'.DS.'paramsbackup', 'administrator'.DS.'language'.DS.'en-GB', $basePath);
				
				// Language files
				$langMainFile = $extension_family_short.'_'.$plugin_type.'_'.$extension_name.'.ini';
				$langSystemFile = $extension_family_short.'_'.$plugin_type.'_'.$extension_name.'.sys.ini';
				
				// Rename language files
				rename($langPath.$langMainFile,$langPath.'en-GB.'.$langMainFile);
				rename($langPath.$langSystemFile,$langPath.'en-GB.'.$langSystemFile);
				
				if (file_exists($langPath.'en-GB.'.$langMainFile) && file_exists($langPath.'en-GB.'.$langSystemFile)) {
					// Mark patch as applied
					$langPatchStatus = true;
				}
				
				// Possible patch results
				if ($xmlFile && $xmlPatchStatus && $langPatchStatus) {
					
					// Redirect to the final url
					$finalUrl = str_replace('&execute=applyj3patch','&execute=done', $uri);
					header("Location: ".$finalUrl);
				} else {
					
					// Print the error message
					$message = '<p class="text-center alert alert-danger" style="margin-bottom: 25px; margin-top: 25px; padding: 50px;">';
					$message .= 'A problem occurred and the patch has not been applied. You may want to consider updating your website to Joomla 4.';
					$message .= '</p>';
					return $message;
				}
				
			}
		
		} else if ($execute == 'done') {
				
				// Print the success message
				$message = '<p class="text-center alert alert-success" style="margin-bottom: 25px; margin-top: 25px; padding: 50px;">';
				$message .= 'Update completed. When you will update your website to Joomla 4, remember to install again this extension to make it work correctly on Joomla 4.<br /><br />';
				$message .= '<a class="btn btn-success btn-large" href="'.str_replace('&execute=done', '', $uri).'">Refresh the page to finish</a>';
				$message .= '</p>';
				return $message;
			
		} else {
			
			// Print the alert regarding the need of the patch to be applied for Joomla 3 websites
			if (version_compare($joomlaVersion, "3.99.99", "<=")) {
				
				// Extension folder
				$basePath = str_replace('extras'.DS.'elements', '', dirname(__FILE__)).DS;
		
				// Get manifest file content
				$xmlFile = file_get_contents($basePath.'paramsbackup.xml');
				
				// Print the warning message
				if (strpos($xmlFile, 'layout="joomla.form.field.radio.switcher"') !== false) {
					$message = '<p class="text-center alert alert-warning" style="margin-bottom: 25px; margin-top: 25px; padding: 50px;">';
					$message .= 'READ CAREFULLY: this extension is compatible with Joomla 3 only through a patch which will alter the manifest file and the language files. <br />';
					$message .= 'Click on the button to apply the patch, thanks. Remember to update your website to Joomla 4 as soon as possible.<br /><br />';
					$message .= '<a class="btn btn-warning btn-large" href="'.$uri.'&execute=applyj3patch">Apply the patch now</a>';
					$message .= '</p>';
					return $message;
				} else {
					return;
				}
				
			}
			
		}
        
    }
}

?>