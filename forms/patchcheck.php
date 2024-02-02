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

class JFormFieldPatchcheck extends JFormField {
    
    protected $type = 'Patchcheck';
    
    protected function getInput() {
        
        // Human defined variables
        $extension_family = 'plugin';
        $extension_family_short = 'plg';
        $plugin_type = 'content';
        $extension_name = 'paramsbackup';
        
        // General variables
        $joomlaVersion = JVERSION;
        $uri = Uri::getInstance();
        
        // IDs variables
        $pluginID = $this->get_plugin_id();
        if (!$pluginID) {
        	return;
        }
        
        // Language folder
		// TODO: cycle every language listed in manifest file (not only en-GB)
		$langPath = JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS;
		
		// Language files
		$langMainFile = $langPath.'en-GB.'.$extension_family_short.'_'.$plugin_type.'_'.$extension_name.'.ini';
		$langSystemFile = $langPath.'en-GB.'.$extension_family_short.'_'.$plugin_type.'_'.$extension_name.'.sys.ini';
		
		if ( version_compare($joomlaVersion, "4.0", ">=") ) {
			// Stop because it is  Joomla 4+ and patch is not needed
        	return;
		} else if ( version_compare($joomlaVersion, "3.99.99", "<=") && file_exists($langMainFile) && file_exists($langSystemFile) ) {
			// Stop because it looks like the patch has been already applied
			// TODO: this method is not very efficent to check if the patch has been already applied
			return;
		} else if ( version_compare($joomlaVersion, "3.99.99", "<=") && !strpos($uri, 'index.php?option=com_plugins&view=plugin&layout=edit&extension_id='.$pluginID) !== false ) {
        	// Print the warning message but not if the user is into Params Backup plugin settings
			$message = '<p class="text-center alert alert-warning" style="margin-bottom: 25px; margin-top: 25px; padding: 50px;">';
			$message .= 'READ CAREFULLY: this extension is compatible with Joomla 3 only through a patch which will alter the manifest file and the language files. <br />';
			$message .= 'If you see this page being broken and ugly, most likely you still have to apply the patch.<br /><br />';
			$message .= '<a class="btn btn-warning btn-large" target="_blank" href="index.php?option=com_plugins&task=plugin.edit&extension_id='.$pluginID.'">Open plugin settings</a>';
			$message .= '</p>';
			return $message;
        } else {
        	// Stop because most likely it is  Joomla 4+
        	return;
        }
        
    }
    
    // Get plugin ID
    protected function get_plugin_id() {
    	$db = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('extension_id'));
		$query->from($db->quoteName('#__extensions'));
		$query->where($db->quoteName('element') . ' = '. $db->quote('paramsbackup'));
		$query->where($db->quoteName('type') . ' = '. $db->quote('plugin'));
		$query->setLimit(1);
		$db->setQuery($query);
		$pluginID = $db->loadResult();
		
		return $pluginID;
	}
}

?>