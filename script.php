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
	  	
	  	// Donation box on first install
	  	
	  	// specific classes based on joomla version
    	if (version_compare(JVERSION, "4.0.0", ">=")) {
    		$row_class = 'row';
    		$col_class = 'col-12';
    		$badge_class = 'badge bg-success';
    	} else {
    		$row_class = 'row-fluid';
    		$col_class = 'span12';
    		$badge_class = 'label label-success';
    	}
		
		$version = $parent->getManifest()->version;
		
		$btc_address = "bc1qnuswkl7jhc2p8dm00ryqlu92ctgg58rzthquy6";
		$eth_address = "0xBe43A9F77a80AA064FedAE5B427fd5b3077D2cc0";
		$kofi_link = 'https://ko-fi.com/Z8Z4U0RY9';
    	$paypal_link = 'https://www.paypal.com/paypalme/gregorionuti';
    	$revolut_link = 'https://revolut.me/gregorionuti';
		
		$html = '';
        $html .= '<div class="'.$row_class.'">';
        $html .= '<div class="'.$col_class.'">';
        $html .= '<div id="digigreg_donation">';
        $html .= '<div id="digigreg_donation_text">';
        $html .= '<h2>Thanks for installing Params Backup</h2>';
        $html .= '<p>If you will find it useful, please, consider a small donation. You can help the developer to keep it up to date and tuned up over time. Even € 1 or whatever small amount can help. Thank you so much!<br /><small><i class="text-dark">Greg</i></small>'.'</p>';
        $html .= '<p>';
        $html .= '<span class="text-uppercase '.$badge_class.'">Bitcoin address</span> '.$btc_address.' <br /><br />';
        $html .= '<span class="text-uppercase  '.$badge_class.'">Ethereum address</span> '.$eth_address.' <br /><br />';
        $html .= '<span class="text-uppercase">Or you can donate via these links</span> <br />';
        $html .= '<a style="margin-top: 5px; margin-right: 10px;" class="btn btn-light" target="_blank" href="'.$kofi_link.'"><img style="width: 16px; height: 16px; margin-right: 2px; margin-top: -3px;" class="btn-img" src="'.URI::root().'plugins'.DS.'content'.DS.'paramsbackup'.DS.'assets'.DS.'images'.DS.'logo-ko-fi.png" alt="Ko-fi" />Ko-fi</a>';
        $html .= '<a style="margin-top: 5px; margin-right: 10px;" class="btn btn-light" target="_blank" href="'.$paypal_link.'"><img style="width: 16px; height: 16px; margin-right: 2px; margin-top: -3px;" class="btn-img" src="'.URI::root().'plugins'.DS.'content'.DS.'paramsbackup'.DS.'assets'.DS.'images'.DS.'logo-paypal.png" alt="PayPal" />PayPal</a>';
        $html .= '<a style="margin-top: 5px; margin-right: 10px;" class="btn btn-light" target="_blank" href="'.$revolut_link.'"><img style="width: 16px; height: 16px; margin-right: 2px; margin-top: -3px;" class="btn-img" src="'.URI::root().'plugins'.DS.'content'.DS.'paramsbackup'.DS.'assets'.DS.'images'.DS.'logo-revolut.png" alt="Revolut" />Revolut</a>';
        $html .= '</p>';
		$html .= '</div>';
		$html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        echo $html;
    }
    
    function update($parent) 
    {
    	// Donation box on update
    	
    	// specific classes based on joomla version
    	if (version_compare(JVERSION, "4.0.0", ">=")) {
    		$row_class = 'row';
    		$col_class = 'col-12';
    		$badge_class = 'badge bg-success';
    	} else {
    		$row_class = 'row-fluid';
    		$col_class = 'span12';
    		$badge_class = 'label label-success';
    	}
		
		$version = $parent->getManifest()->version;
		
		$btc_address = "bc1qnuswkl7jhc2p8dm00ryqlu92ctgg58rzthquy6";
		$eth_address = "0xBe43A9F77a80AA064FedAE5B427fd5b3077D2cc0";
		$kofi_link = 'https://ko-fi.com/Z8Z4U0RY9';
    	$paypal_link = 'https://www.paypal.com/paypalme/gregorionuti';
    	$revolut_link = 'https://revolut.me/gregorionuti';
		
		$html = '';
        $html .= '<div class="'.$row_class.'">';
        $html .= '<div class="'.$col_class.'">';
        $html .= '<div id="digigreg_donation">';
        $html .= '<div id="digigreg_donation_text">';
        $html .= '<h2>Do you like Params Backup?</h2>';
        $html .= '<p>The development of this extension and its conversion for Joomla 5 took many hours of work. With a small donation, you can help the developer to keep it up to date and tuned up over time. Even € 1 or whatever small amount can help. Thank you so much!<br /><small><i class="text-dark">Greg</i></small>'.'</p>';
        $html .= '<p>';
        $html .= '<span class="text-uppercase '.$badge_class.'">Bitcoin address</span> '.$btc_address.' <br /><br />';
        $html .= '<span class="text-uppercase  '.$badge_class.'">Ethereum address</span> '.$eth_address.' <br /><br />';
        $html .= '<span class="text-uppercase">Or you can donate via these links</span> <br />';
        $html .= '<a style="margin-top: 5px; margin-right: 10px;" class="btn btn-light" target="_blank" href="'.$kofi_link.'"><img style="width: 16px; height: 16px; margin-right: 2px; margin-top: -3px;" class="btn-img" src="'.URI::root().'plugins'.DS.'content'.DS.'paramsbackup'.DS.'assets'.DS.'images'.DS.'logo-ko-fi.png" alt="Ko-fi" />Ko-fi</a>';
        $html .= '<a style="margin-top: 5px; margin-right: 10px;" class="btn btn-light" target="_blank" href="'.$paypal_link.'"><img style="width: 16px; height: 16px; margin-right: 2px; margin-top: -3px;" class="btn-img" src="'.URI::root().'plugins'.DS.'content'.DS.'paramsbackup'.DS.'assets'.DS.'images'.DS.'logo-paypal.png" alt="PayPal" />PayPal</a>';
        $html .= '<a style="margin-top: 5px; margin-right: 10px;" class="btn btn-light" target="_blank" href="'.$revolut_link.'"><img style="width: 16px; height: 16px; margin-right: 2px; margin-top: -3px;" class="btn-img" src="'.URI::root().'plugins'.DS.'content'.DS.'paramsbackup'.DS.'assets'.DS.'images'.DS.'logo-revolut.png" alt="Revolut" />Revolut</a>';
        $html .= '</p>';
		$html .= '</div>';
		$html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        echo $html;
	}
	
	public function uninstall($parent)
	{
		
	}
	
}

?>