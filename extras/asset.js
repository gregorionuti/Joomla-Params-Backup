/**
 * 
 * @version             See field version manifest file
 * @package             See field name manifest file
 * @author				Gregorio Nuti
 * @copyright			See field copyright manifest file
 * @license             GNU General Public License version 2 or later
 * 
 */

// Detect if jQuery is loaded
window.onload = function() {
    if (!window.jQuery) {  
        alert("It appears that you have a misconfiguration in your javascript files. This extension needs jQuery to properly work, so you may experience some issues.");
    }
}

// jQuery no conflict declaration
var $j = jQuery.noConflict();

$j(document).ready(function() {
	
	// Hide empty label container and remove margin
    $j('label.hidden').add('label[id*=hidden]').parent('.control-label').hide();
    $j('label.hidden').add('label[id*=hidden]').parent('.control-label').parent('.control-group').children('.controls').css('margin-left','0');
    
});