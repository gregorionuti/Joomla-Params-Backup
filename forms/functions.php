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

// Include files
include_once (__DIR__).'/js.php';
include_once (__DIR__).'/css.php';
include_once (__DIR__).'/donation.php';
include_once (__DIR__).'/support.php';

jimport('joomla.filesystem.file');
Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');

class JFormFieldFunctions extends JFormField {
    protected $type = 'Backup';
    
    protected function getInput() {
    	
        // General variables
        $uri = Uri::getInstance();
        $base_path = str_replace('forms', '', dirname(__FILE__)).'settings'.DS;
        $db = Factory::getDBO();
        
        // Plugin params
        $plugin = JPluginHelper::getPlugin('content', 'paramsbackup');
		$pluginParams = new JRegistry();
		$pluginParams->loadString($plugin->params);
		$donation = $pluginParams->get('donation', 0);
        
        $joomla_version = JVERSION;
    	
    	// Specific classes and styles based on Joomla version
    	if (version_compare($joomla_version, "4.0.0", ">=")) {
    		$row_class = 'row';
    		$col_class_6 = 'col-12 col-md-6';
    		$col_class_4 = 'col-12 col-md-4';
    		$col_class_8= 'col-12 col-md-8';
    		$card_class = 'card';
    		$card_title_class = 'card-title';
    		$card_body_class = 'card-body';
    		$card_text_class = 'card-text';
    		$bg_success_class = "bg-success";
    		$btn_success_class = "btn-outline-success";
    		$btn_dark_class = "btn-outline-dark";
    		$btn_warning_class = "btn-outline-warning";
    		$btn_info_class = "btn-outline-info";
    		$badge_success_class = "badge bg-success";
    		$badge_info_class = "badge bg-info";
    		$badge_danger_class = "badge bg-danger";
    	} else {
    		$row_class = 'row-fluid';
    		$col_class_6 = 'span6';
    		$col_class_4 = 'span4';
    		$col_class_8 = 'span8';
    		$card_class = 'well';
    		$card_title_class = 'title';
    		$card_body_class = 'well';
    		$card_text_class = 'text';
    		$bg_success_class = "alert alert-success";
    		$btn_success_class = "btn-success";
    		$btn_dark_class = "btn-default";
    		$btn_warning_class = "btn-warning";
    		$btn_info_class = "btn-info";
    		$badge_success_class = "label label-success";
    		$badge_info_class = "label label-info";
    		$badge_danger_class = "label label-important";
    	}
        
        // Include generated css code
        css();
        
        // Include generated javascript code
    	js();
        
        // Get correct table of database
        $db_table = $this->get_db_table();
        
        // Get variables from url and set the right database column to query
        $identificator = $this->get_identificator();
        $extension_id = $uri->getVar($identificator, 'none');
        $db_column = $this->get_db_table_column();
        
        // Get input variables to determine the operation to perform and the file to use
        $task = $uri->getVar('backup_task', 'none');
        $file = $uri->getVar('backup_file', 'none');
        
        // Check if url contains proper variables
        // DEPRECATED: is_numeric($extension_id) because the identificator of com_config is not a numeric ID but is the value of the param component
		if ($extension_id !== 'none' && $task !== 'none') {
            if ($task == 'load') {
                if (JFile::exists($base_path . $file)) {
                	
                	// Load file
                	$file_content = file_get_contents($base_path . $file);
                	
                	// Write file params into the database
                    $query = 'UPDATE '.$db_table.' SET params = '.$db->quote($file_content).' WHERE '.$db_column.' = '.$db->quote($extension_id).' LIMIT 1';
                    $db->setQuery($query);
                    $result = $db->execute();
                }
            } else if ($task == 'save') {
            	
            	// Get the current data from the database
            	// TODO: throw an error if no data is found by this query
                $query = 'SELECT params AS params FROM '.$db_table.' WHERE '.$db_column.' = '.$db->quote($extension_id).' LIMIT 1';
                $db->setQuery($query);
                $result = $db->loadObject();
                
                // Write file
                JFile::write($base_path.$file , $result->params);
                
            } else if ($task == 'delete') {
            	
                // Delete file
                JFile::delete($base_path.$file);	
            }
        }
        
        // Generate files list (dropdown for Load and Delete)
        $list = (array) $this->getFiles();
        
        // File lists variables
        $load_file = JHtml::_('select.genericlist', $list, 'load_list', 'class="form-select"', 'value', 'text', 'default', 'backup_load_filename');
        $delete_file = JHtml::_('select.genericlist', $list, 'delete_list', 'class="form-select"', 'value', 'text', 'default', 'backup_delete_filename');
        
        // File numbers variables
        $number_of_files = $this->countFiles();
        $number_of_files_of_this_extension = $this->count_this_extention_files();
        
        // IDs variables
        $current_extension_id = $this->get_current_id();
        $plugin_id = $this->get_plugin_id();
        
        // Create the html
        $html = '';
        $html .= '<div id="backup_form" class="container">';
        $html .= '<div class="'.$row_class.'">';
        $html .= '<div class="'.$col_class_6.'">';
        
        // Save, Load, Delete fields
        $html .= '<div class="input-group"><input type="text" id="backup_save_filename" class="form-control" placeholder="'.Text::_('PLG_CONTENT_PARAMSBACKUP_LABEL_PARAMSBACKUP_SAVE_PLACEHOLDER').'" /><span class="input-group-text">.json</span><button id="backup_save" class="btn btn-success"><em class="icon-apply"></em> '.Text::_('PLG_CONTENT_PARAMSBACKUP_LABEL_PARAMSBACKUP_SAVE').'</button></div>';
        $html .= '<div class="input-group mt-4">'.$load_file.'<span class="input-group-text">.json</span><button id="backup_load" class="btn btn-info"><em class="icon-edit"></em> '.Text::_('PLG_CONTENT_PARAMSBACKUP_LABEL_PARAMSBACKUP_LOAD').'</button></div>';
        $html .= '<div class="input-group mt-4">'.$delete_file.'<span class="input-group-text">.json</span><button id="backup_delete" class="btn btn-danger"><em class="icon-file-remove"></em> '.Text::_('PLG_CONTENT_PARAMSBACKUP_LABEL_PARAMSBACKUP_DELETE').'</button></div>';
        
        // Message and Plugin button
        if ($current_extension_id != $plugin_id) {
			$html .= '<div class="'.$row_class.'">';
			$html .= '<div class="'.$col_class_8.'">';
			$html .= '<div id="backup_message" class="system-message mt-4"></div>';
			$html .= '</div>';
			$html .= '<div class="'.$col_class_4.' text-right text-end">';
			$html .= '<div id="plugin_button" class="mt-4">'.strtoupper(Text::_('PLG_CONTENT_PARAMSBACKUP_OPEN_PLUGIN_SETTINGS_TEXT')).'<br /><a class="btn btn-primary mt-2" target="_blank" href="index.php?option=com_plugins&task=plugin.edit&extension_id='.$plugin_id.'">'.Text::_('PLG_CONTENT_PARAMSBACKUP_OPEN_PLUGIN_SETTINGS_BUTTON').'</a></div>';
			$html .= '</div>';
			$html .= '</div>';
        } else {
        	$html .= '<div id="backup_message" class="system-message mt-4"></div>';
        }
    	
        // Files card
        $html .= '<div class="card mt-4">';
        $html .= '<div class="card-header bg-dark text-white">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FILES_TITLE').'</div>';
        $html .= '<ul class="list-group list-group-flush">';
        $html .= '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FILES_PLACED_IN').': <span id="files_path" class="badge badge-default bg-dark">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FILES_PATH').'</span></li>';
        $html .= '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FIELD_NUMBER_OF_FILES_THIS_EXTENSION').': <span id="files_number_this" class="badge badge-default bg-dark">'.$number_of_files_of_this_extension.'</span></li>';
        $html .= '<li class="list-group-item">'.Text::_('PLG_CONTENT_PARAMSBACKUP_FIELD_NUMBER_OF_FILES_TOTAL').': <span id="files_number_total" class="badge badge-default bg-dark">'.$number_of_files.'</span></li>';
        $html .= '</ul>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '<div class="'.$col_class_6.'">';
        
        // Instructions
        $html .= '<h3>'.Text::_('PLG_CONTENT_PARAMSBACKUP_TEXT_INSTRUCTION_TITLE').'</h3>';
        $html .= '<p class="mb-0">'.Text::_('PLG_CONTENT_PARAMSBACKUP_TEXT_INSTRUCTION_DESC_1').'</p>';
        $html .= '<ul class="mb-0">';
        $html .= '<li>'.Text::_('PLG_CONTENT_PARAMSBACKUP_TEXT_INSTRUCTION_DESC_SAVE').'</li>';
        $html .= '<li>'.Text::_('PLG_CONTENT_PARAMSBACKUP_TEXT_INSTRUCTION_DESC_LOAD').'</li>';
        $html .= '<li>'.Text::_('PLG_CONTENT_PARAMSBACKUP_TEXT_INSTRUCTION_DESC_DELETE').'</li>';
        $html .= '</ul>';
        $html .= '<p>'.Text::_('PLG_CONTENT_PARAMSBACKUP_TEXT_INSTRUCTION_DESC_2').'</p>';
        
        // Donation card
        if ($donation && $number_of_files >= 5) {
        	$html .= donation();
        }
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
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
		$plugin_id = $db->loadResult();
		
		return $plugin_id;
	}
	
	// Get current extension ID
	protected function get_current_id() {
        $uri = Uri::getInstance();
        $extension_id = $uri->getVar('extension_id');
        
        if ($extension_id) {
        	return $extension_id;
        } else {
        	return 'Paramenter extension_id not found';
        }
    }
    
    // Get the database table
    protected function get_db_table() {
        $uri = Uri::getInstance();
        $option = $uri->getVar('option');
        $db_table = '';
        
        if ($option == 'com_modules' || $option == 'com_advancedmodules') {
            $db_table = '#__modules';
        } else if ($option == 'com_plugins') {
            $db_table = '#__extensions';
        } else if ($option == 'com_templates') {
            $db_table = '#__template_styles';
        } else if ($option == 'com_config') {
            $db_table = '#__extensions';
        }
        
        return $db_table;
    }
    
    // Get the database table column
    protected function get_db_table_column() {
        $uri = Uri::getInstance();
        $option = $uri->getVar('option');
        $db_column = '';
        
        if ($option == 'com_modules' || $option == 'com_advancedmodules') {
            $db_column = 'id';
        } else if ($option == 'com_plugins') {
            $db_column = 'extension_id';
        } else if ($option == 'com_templates') {
            $db_column = 'id';
        } else if ($option == 'com_config') {
            $db_column = 'name';
        } else {
            $db_column = 'id';
        }
        
        return $db_column;
    }
    
    // Get the identificator
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
        	// if com_config the identificator is not a numeric ID but is the value of the param component
            $identificator = 'component';
        } else {
            $identificator = 'id';
        }
        
        return $identificator;
    }
    
    // Get the total number of all saved files
    protected function countFiles() {
        jimport('joomla.filesystem.folder');
        
        $count = 0;
        $path = str_replace('forms', '', dirname(__FILE__)).'settings'.DS;
        
        if (!is_dir($path)) $path = JPATH_ROOT.'/'.$path;
            $files = JFolder::files($path, '.json');
            if (is_array($files)) {
                foreach($files as $file) {
                	$count++;
                }
            }
        
        return $count;
    }
    
    // Get the total number of saved files for the current extension
    protected function count_this_extention_files() {
        jimport('joomla.filesystem.folder');
        
        $uri = Uri::getInstance();
        $option = $uri->getVar('option');
        $current_id = $uri->getVar($this->get_identificator());
        $count = 0;
        $path = str_replace('forms', '', dirname(__FILE__)).'settings'.DS;
        
        if (!is_dir($path)) $path = JPATH_ROOT.'/'.$path;
            $files = JFolder::files($path, '.json');
            if (is_array($files)) {
                foreach($files as $file) {
                	$filename_arr = explode('-', $file);
                	if ($filename_arr[0] == $option && $filename_arr[1] == $current_id) {
                		$count++;
                	}
                }
            }
        
        return $count;
    }
    
    // Format the file name
    protected function getCleanFileName($file) {
        
        $filename_arr = explode('-', $file);
        $file_clean = str_replace($filename_arr[0].'-'.$filename_arr[1].'-', '', $file);
        $file_clean = str_replace('.json', '', $file_clean);
        
        return $file_clean;
    }
    
    // List the saved files
    protected function getFiles() {
        jimport('joomla.filesystem.folder');
        
        $uri = Uri::getInstance();
        $option = $uri->getVar('option');
        $current_id = $uri->getVar($this->get_identificator());
        if (!$current_id) {
        	$current_id = $uri->getVar('extension_id');
        }
        $list = array();
        $path = str_replace('forms', '', dirname(__FILE__)).'settings'.DS;
        
        if (!is_dir($path)) $path = JPATH_ROOT.'/'.$path;
            $files = JFolder::files($path, '.json');
            if (is_array($files)) {
                foreach($files as $file) {
                	$filename_arr = explode('-', $file);
                	if ($filename_arr[0] == $option && $filename_arr[1] == $current_id) {
                		$list[] = JHtml::_('select.option', $file, $this->getCleanFileName($file));
                	}
                }
            }
        
        return array_merge($list);
    }
}

?>