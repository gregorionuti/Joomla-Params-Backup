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

class plgContentParamsbackupInstallerScript {
	
	public function install($parent) 
    {
		// Enable plugin
		$db  = Factory::getDbo();
	  	$query = $db->getQuery(true);
	  	$query->update('#__extensions');
	  	$query->set($db->quoteName('enabled') . ' = 1');
	  	$query->where($db->quoteName('element') . ' = ' . $db->quote('paramsbackup'));
	  	$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
	  	$db->setQuery($query);
	  	$db->execute();
    }
    
    public function update($parent) 
    {
		
    }
	
	public function uninstall($parent)
	{
		
	}
	
}

?>