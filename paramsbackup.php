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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;

// Load plugin language file - https://docs.joomla.org/Loading_extra_language_files
$lang = Factory::getLanguage();
$extension = 'plg_content_paramsbackup';
$base_dir = JPATH_ADMINISTRATOR;
$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

// Import library dependencies
jimport('joomla.plugin.plugin');

class plgContentParamsbackup extends JPlugin
{

	function onContentPrepareForm($form, $data)
	{
		// General variables
		$app = Factory::getApplication();
		$document = Factory::getDocument();
		$uri = Uri::getInstance();
		$identificator = $this->get_identificator();
        $extension_id = $uri->getVar($identificator, 'none');
        $form_name = $form->getName();
		
		// Get plugin params
		$plugin = JPluginHelper::getPlugin('content', 'paramsbackup');
		$modules = $this->params->get('modules', 0);
		$plugins = $this->params->get('plugins', 0);
		$templates = $this->params->get('templates', 0);
		$configurations = $this->params->get('configurations', 0);
		$debug = $this->params->get('debug', 0);
		$inclusions = $this->params->get('inclusions');
		$exclusions = $this->params->get('exclusions');
		
		// Check if is in admin
		if ($app->isClient('site')) {
			return;
		}
		
		// Check if the form is and instance of JForm
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		
		// Avoid to load the plugin if another instance of the same backup function is already loaded in a Digigreg extension
		$backup_already_loaded = false;
		$xml_string = json_encode($data->xml);
		if (strpos($xml_string, 'backup') !== false && strpos($xml_string, 'backup_hidden') !== false && strpos($xml_string, 'digigreg') !== false) {
			$backup_already_loaded = true;
		}
		
		// Decide whenever to inject the backup tab
		$show_on = [];
		$show_no = [];
		if ($modules == 1) {
			$show_on[] = 'com_modules.module';
			$show_on[] = 'com_advancedmodules.module';
		}
		if ($plugins == 1) {
			$show_on[] = 'com_plugins.plugin';
		}
		if ($templates == 1) {
			$show_on[] = 'com_templates.style';
		}
		if ($configurations == 1) {
			$show_on[] = 'com_config.component';
		}
		if ($inclusions) {
			$inclusions_arr = explode(PHP_EOL, $inclusions);
			foreach ($inclusions_arr as $formX) {
				$show_on[] = trim($formX);
			}
		}
		if ($exclusions) {
			$forms_arr_2 = explode(PHP_EOL, $exclusions);
			foreach ($forms_arr_2 as $formX) {
				$show_no[] = trim($formX);
			}
		}
        
        // Print the debug window
        if ($debug == 1) {
			// TODO: Get the badge class from a file which generates all the needed classes depending by Joomla version
			echo '<div class="card mb-3">';
			echo '<div class="card-header bg-warning">';
			echo Text::_('PLG_CONTENT_PARAMSBACKUP_CLEAN_NAME').' '.Text::_('PLG_CONTENT_PARAMSBACKUP_DEBUG_TITLE');
			echo '</div>';
			echo '<ul class="list-group list-group-flush">';
			echo '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FIELD_FORM_NAME').': <span class="badge badge-info bg-info">'.$form_name.'</span></li>';
			echo '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FIELD_FORM_IDENTIFICATOR').': <span class="badge badge-info bg-info">'.$extension_id.'</span></li>';
			echo '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FIELD_FORM_EXCLUSION_RULE').': <span class="badge badge-info bg-info">'.$form_name.'.'.$extension_id.'</span></li>';
			if (in_array($form_name, $show_on) && $extension_id != 'none' && !in_array($form_name.'.'.$extension_id, $show_no) && Factory::getApplication()->isClient('administrator') && $backup_already_loaded === false) {
				echo '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_CLEAN_NAME').' '.Text::_('PLG_CONTENT_PARAMSBACKUP_DEBUG_PLUGIN').': <span class="badge badge-success bg-success">'.Text::_('PLG_CONTENT_PARAMSBACKUP_DEBUG_SHOWN_YES').'</span></li>';
			} else {
				echo '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_CLEAN_NAME').' '.Text::_('PLG_CONTENT_PARAMSBACKUP_DEBUG_PLUGIN').': <span class="badge badge-danger bg-danger">'.Text::_('PLG_CONTENT_PARAMSBACKUP_DEBUG_SHOWN_NO').'</span>';
				if ($backup_already_loaded === true) {
					echo ' '.Text::_('PLG_CONTENT_PARAMSBACKUP_DEBUG_IS_ALREADY_LOADED');
				}
				echo '</li>';
			}
			echo '</ul>';
			echo '</div>';
		}
		
		// Inject the backup tab
		if (in_array($form_name, $show_on) && $extension_id != 'none' && !in_array($form_name.'.'.$extension_id, $show_no) && Factory::getApplication()->isClient('administrator') && $backup_already_loaded === false) {
			Form::addFormPath(__DIR__ . '/forms');
			$form->loadFile('functions', false);
			return true;
		} else {
			return true;
		}
    	
	}
	
	// Get the identificator of the current extension (id or extension_id or component)
	protected function get_identificator() {
        $uri = Uri::getInstance();
        $option = $uri->getVar('option');
        $identificator = '';
        
        if ($option == 'com_modules' || $option == 'com_advancedmodules') {
            $identificator = 'id';
        } else if ($option == 'com_plugins') {
            $identificator = 'extension_id';
        } else if ($option == 'com_templates') {
            $identificator = 'id';
        } else if ($option == 'com_config') {
            $identificator = 'component';
        } else {
            $identificator = 'id';
        }
        
        return $identificator;
    }
	
}