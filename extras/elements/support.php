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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

jimport('joomla.form.formfield');

class JFormFieldSupport extends JFormField {
    protected $type = 'Support';
    
    protected function getInput() {
    	
    	// Human defined variables
		$support_question_link = 'https://www.digigreg.com/#contact-presale';
    	$support_one_hour_link = 'https://www.digigreg.com/en/products/tech-support/sign-up.html#hours-1';
    	$support_two_hours_link = 'https://www.digigreg.com/en/products/tech-support/sign-up.html#hours-2';
    	$support_three_hours_link = 'https://www.digigreg.com/en/products/tech-support/sign-up.html#hours-3';
    	$support_five_hours_link = 'https://www.digigreg.com/en/products/tech-support/sign-up.html#hours-5';
    	$support_ten_hours_link = 'https://www.digigreg.com/en/products/tech-support/sign-up.html#hours-10';
        $extension_family = 'plugins';
        $plugin_type = 'content';
        $extension_name = 'paramsbackup';
        $text_prefix = 'PLG_CONTENT_PARAMSBACKUP';
		
    	// General variables
    	$document = Factory::getDocument();
    	$joomlaVersion = JVERSION;
    	
    	// Specific classes and styles based on Joomla version
    	if (version_compare($joomlaVersion, "4.0.0", ">=")) {
    		$row_class = 'row';
    		$col_class = 'col-12 col-md-12';
    		$card_class = 'card';
    		$card_title_class = 'card-title';
    		$card_body_class = 'card-body';
    		$card_text_class = 'card-text';
    		$bg_success_class = "bg-success";
    		$btn_dark_class = "btn-outline-dark";
    		$document->addStyleDeclaration('
    			@media (max-width: 1300px) {
					#digigreg_support .card-text {
						word-wrap: anywhere;
					}
				}
    			@media (max-width: 767px) {
					#digigreg_support {
						margin: 0 1rem;
					}
				}
    		');
    	} else {
    		$row_class = 'row-fluid';
    		$col_class = 'span12';
    		$card_class = 'well';
    		$card_title_class = 'title';
    		$card_body_class = 'well';
    		$card_text_class = 'text';
    		$bg_success_class = "alert alert-success";
    		$btn_dark_class = "btn-warning";
    		$document->addStyleDeclaration('
    			#digigreg_support .well {
					margin: 0;
					background-color: rgba(255, 255, 255, 0.7);
				}
    			#digigreg_support .well .btn-group,
    			#digigreg_support .well .mt-1 {
					margin-top: 10px;
				}
				@media (max-width: 1300px) {
					#digigreg_support .btn {
						display: block;
						border-radius: 3px;
						margin-bottom: 10px;
					}
				}
    		');
    	}
    	
    	// Add css style
		$document->addStyleDeclaration('
			#digigreg_support {
				background-color: #f89406;
				background-image: url("'.URI::root().$extension_family.DS.$plugin_type.DS.$extension_name.DS.'assets'.DS.'images'.DS.'pattern-support.png");
				background-repeat: repeat;
				position: relative;
				z-index: 0;
				/*
				display: flex;
				align-items: center;
				justify-content: center;
				*/
				min-height: 276px;
				overflow: hidden;
			}
			#digigreg_support a[target="_blank"]::before {
				content: "";
			}
			#digigreg_support_text {
				text-align: left;
			}
		');
        
        // Create the html
        $html = '';
        $html .= '<div class="'.$row_class.'">';
        $html .= '<div class="'.$col_class.'">';
        $html .= '<div id="digigreg_support" class="'.$card_class.'">';
        $html .= '<div id="digigreg_support_text" class="'.$card_body_class.'">';
        $html .= '<h2 class="'.$card_title_class.' text-light">'.strToUpper(Text::_($text_prefix.'_SUPPORT_TITLE')).'</h2>';
        $html .= '<p class="'.$card_text_class.' bg-light text-dark rounded p-3">'.Text::_($text_prefix.'_SUPPORT_DESC').'</p>';
		$html .= '<p class="'.$card_text_class.' bg-warning text-dark rounded p-3">';
        $html .= '<span class="text-uppercase">'.Text::_($text_prefix.'_SUPPORT_ASK_QUESTION_BEFORE_TO_BUY').'</span> <br />';
        $html .= '<a class="btn '.$btn_dark_class.' btn-sm mt-1" href="'.$support_question_link.'" target="_blank">'.strToUpper(Text::_($text_prefix.'_SUPPORT_ASK_QUESTION')).'</a> <br /><br />';
        $html .= '<span class="text-uppercase">'.Text::_($text_prefix.'_SUPPORT_BUY_HOURS').'</span> <br />';
        $html .= '<span class="btn-group btn-group-sm mt-1 mb-1" role="group">';
        $html .= '<a class="btn '.$btn_dark_class.'" target="_blank" href="'.$support_one_hour_link .'">'.strToUpper(Text::_($text_prefix.'_SUPPORT_ONE_HOUR')).'</a>';
        $html .= '<a class="btn '.$btn_dark_class.'" target="_blank" href="'.$support_two_hours_link .'">'.strToUpper(Text::_($text_prefix.'_SUPPORT_TWO_HOURS')).'</a>';
        $html .= '<a class="btn '.$btn_dark_class.'" target="_blank" href="'.$support_three_hours_link .'">'.strToUpper(Text::_($text_prefix.'_SUPPORT_THREE_HOURS')).'</a>';
        $html .= '<a class="btn '.$btn_dark_class.'" target="_blank" href="'.$support_five_hours_link .'">'.strToUpper(Text::_($text_prefix.'_SUPPORT_FIVE_HOURS')).'</a>';
        $html .= '<a class="btn '.$btn_dark_class.'" target="_blank" href="'.$support_ten_hours_link .'">'.strToUpper(Text::_($text_prefix.'_SUPPORT_TEN_HOURS')).'</a>';
        $html .= '</span> <br />';
        $html .= '<span class="text-uppercase">'.Text::_($text_prefix.'_SUPPORT_WORKING_HOURS').'</span> <br /><br />';
        $html .= '<span class="text-uppercase">'.Text::_($text_prefix.'_SUPPORT_I_THINK_IS_A_BUG').'</span> <br />';
        $html .= '<a class="btn '.$btn_dark_class.' btn-sm mt-1" href="'.$support_question_link.'" target="_blank">'.strToUpper(Text::_($text_prefix.'_SUPPORT_REPORT_BUG')).'</a>';
        $html .= '</p>';
		$html .= '</div>';
		$html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}

?>