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

// no direct access
defined('_JEXEC') or die;

// define ds variable for joomla 3 compatibility
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// namespaces
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {
    protected $type = 'Asset';
    
    protected function getLabel() {
        return;
    }
    
    protected function getInput() {
    
        // human defined variables
        $extension_family = 'plugins';
        $plugin_type = 'content';
        $extension_name = 'paramsbackup';
        
        // general variables
        $uri = Uri::getInstance();
        $document = Factory::getDocument();
    	
    	// load jquery in joomla 4
    	$joomlaVersion = JVERSION;
    	if ($joomlaVersion >= '4') {
			$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
			$wa->useScript('jquery');
    	}
        
        $document->addScript(URI::root().$extension_family.DS.$plugin_type.DS.$extension_name.DS.'extras'.DS.'asset.js');
        $document->addStyleSheet(URI::root().$extension_family.DS.$plugin_type.DS.$extension_name.DS.'extras'.DS.'asset.css');
        
        return;
    }
}

?>