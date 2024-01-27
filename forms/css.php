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

function css() {
		
        // General variables
        $document = Factory::getDocument();
        $joomla_version = JVERSION;
        
    	// Specific css style based on Joomla version
    	if (version_compare($joomla_version, "4.0.0", ">=")) {
    		$document->addStyleDeclaration('
    			#backup_form .btn {
					min-width: 100px;
				}
    		');
    	} else {
    		$document->addStyleDeclaration('
    			#backup_form .mt-4 {
					margin-top: 25px;
				}
    			#backup_form > .well {
					margin: 0;
					background-color: rgba(0, 0, 0, 0.1);
				}
    			#backup_form .input-group-text {
    				background-color: #31708f;
					color: #fff;
					display: inline-block;
					margin-right: 5px;
					margin-left: 5px;
					padding-left: 5px;
					padding-right: 5px;
					padding-top: 1px;
					padding-bottom: 1px;
					-webkit-border-radius: 3px;
					-moz-border-radius: 3px;
					border-radius: 3px;
				}
				#backup_form .bg-dark {
					background-color: rgb(33, 37, 41);
					color: #fff;
					padding: 5px 15px;
					border-radius: 5px;
					font-weight: bold;
				}
				#backup_form .badge.bg-dark {
					padding-left: 5px;
					padding-right: 5px;
					padding-top: 1px;
					padding-bottom: 1px;
					-webkit-border-radius: 3px;
					-moz-border-radius: 3px;
					border-radius: 3px;
					font-weight: bold;
				}
				#backup_form .card-header {
					margin-bottom: 5px;
				}
				#backup_form button > em {
					display: none;
				}
				@media (min-width: 769px) {
					#backup_form {
						width: 100%;
					}
				}
    		');
    	}
    	
    	// Add css style
		$document->addStyleDeclaration('
			#backup_form [class^=icon-] {
				margin-right: 2px;
			}
			#backup_form .crypto-wallet {
				font-size: .7rem;
			}
			#backup_form .lead {
				font-weight: 400;
			}
			@media (min-width: 769px) {
				#backup_form {
					max-width: unset;
					padding: 0;
				}
			}
		');
    
    }

?>