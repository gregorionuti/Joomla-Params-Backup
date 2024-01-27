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

function js() {
	
	// Human defined variables
	$extension_family = 'plugins';
    $plugin_type = 'content';
    $extension_name = 'paramsbackup';
        
	// General variables
    $base_path = str_replace('forms', '', dirname(__FILE__)).'settings'.DS;
    $db = Factory::getDBO();
    $document = Factory::getDocument();
    	
    // Add javascript code
    $document->addScriptDeclaration('
    	
    // detect if jquery is loaded
	window.onload = function() {
		if (!window.jQuery) {  
			alert("'.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_NO_JQUERY').'");
		}
	}

	// jquery no conflict declaration
	var $j = jQuery.noConflict();
	
	$j(document).ready(function(){
		
		// hide empty label container and remove margin - "#fieldset-backup" is Joomla 4 class - "#attrib-backup" and "#configContent #backup" are Joomla 3 classes
		$j("#fieldset-backup").add("#attrib-backup").add("#configContent #backup").find(".control-label").hide();
		$j("#fieldset-backup").add("#attrib-backup").add("#configContent #backup").find(".control-group").children(".controls").css("margin-left","0");
		
		var backup_message_container = $j("#backup_message");
		
		$j(function backup() {
			
			var loadButton = $j("#backup_load");
			var saveButton = $j("#backup_save");
			var deleteButton = $j("#backup_delete");
			
			reset_message();
			
			saveButton.click(function(e) {
				e.stopPropagation();
				e.preventDefault();
				backupOperation("save");
			});
			
			deleteButton.click(function(e) {
				e.stopPropagation();
				e.preventDefault();
				backupOperation("delete");
			});
			
			loadButton.click(function(e) {
				e.stopPropagation();
				e.preventDefault();
				backupOperation("load");
			});
			
		});
		
		function reset_message() {
			backup_message_container.html("<div class=\"alert alert-block alert-warning\"><h4 style=\"text-transform: uppercase;\">'.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_INFO').'</h4> '.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_WAITING').'...</div>");
		}
		
		function fileNameIfEmpty() {
			
			// get the current time in the proper format to use it if file name is not specified
			var now = new Date();
			var s = now.getSeconds();
			var m = now.getMinutes();
			var h = now.getHours();
			var dd = now.getDate();
			var mm = now.getMonth() + 1;
			var yyyy = now.getFullYear();
			
			// add a "0" if number is only one character
			if (s < 10) {
				s = "0" + s
			}
			if (m < 10) {
				m = "0" + m
			}
			if (h < 10) {
				h = "0" + h
			} 
			if (dd < 10) {
				dd = "0" + dd
			} 
			if (mm < 10) {
				mm = "0" + mm
			} 
			now = mm + "_" + dd + "_" + yyyy + "_" + h + "_" + m + "_" + s;
			
			return "settings_" + now;
		}
		
		function get_identificator() {
			var queryString = window.location.search;
			var urlParams = new URLSearchParams(queryString);
			var option = urlParams.get("option");
			var identificator = "";
		
			if (option == "com_modules" || option == "com_advancedmodules") {
				identificator = "id";
			} else if (option == "com_plugins") {
				identificator = "extension_id";
			} else if (option == "com_templates") {
				identificator = "id";
			} else if (option == "com_config") {
				identificator = "component";
			} else {
				identificator = "id";
			}
			
			return identificator;
		}
		
		function purify_filename(file) {
			// Replace all the special characters with underscore
			return file.replace(/[^a-zA-Z0-9 ]/g, "_");
		}
		
		function format_filename(file,size) {
			var queryString = window.location.search;
			var urlParams = new URLSearchParams(queryString);
			var current_component = urlParams.get("option");
			var current_id = urlParams.get(get_identificator());
			var file_name_array = file.split("-");
			if (Array.isArray(file_name_array)) {
				if (file_name_array[0] == current_component && file_name_array[1] == current_id) {
					if (size == 1) {
						return current_component + "-" + current_id + "-" + file_name_array[2];
					} else if (size == 0) {
						return file_name_array[2].replace(".json","");
					}
				} else {
					if (size == 1) {
						return current_component + "-" + current_id + "-" + purify_filename(file) + ".json";
					} else if (size == 0) {
						return file;
					}
				}
			} else {
				return file;
			}
		}
		
		function backupOperation(current_operation) {
			
			var current_file = $j("#backup_" + current_operation + "_filename").val();
			if (!current_file && current_operation != "save") {
				alert("'.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_SELECT_A_FILE').'");
				return;
			}
			var load_container = $j("#backup_load_filename");
			var delete_container = $j("#backup_delete_filename");
			var files_number_total = $j("#files_number_total");
			var files_number_this = $j("#files_number_this");
			
			if (current_file == "") {
				current_file = fileNameIfEmpty();
			}
			
			// Format file name
			current_file = format_filename(current_file,1);
			current_file_short = format_filename(current_file,0);
			
			var current_url = window.location + "&backup_task=" + current_operation + "&backup_file=" + current_file;
			backup_message_container.html("<div class=\"alert alert-block alert-warning\"><h4 style=\"text-transform: uppercase;\">'.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_PLEASE_WAIT').'</h4> <span id=\"loader\"><img style=\"margin-left: 28px;\" width=\"40\" src=\"'.URI::root().$extension_family.DS.$plugin_type.DS.$extension_name.DS.'assets'.DS.'images'.DS.'loader.gif\" alt=\"loading\"></span></div>");
			
			$j.ajax({
				url: current_url,
				success: function(){
					if (current_operation == "save") {
						
						// save function
						if (!(load_container.html().indexOf(current_file) >= 0)) {
							load_container.append("<option value=\"" + current_file + "\">" + current_file_short + "</option>");
							delete_container.append("<option value=\"" + current_file + "\">" + current_file_short + "</option>");
							files_number_this.html(parseInt(files_number_this.html()) + 1);
							files_number_total.html(parseInt(files_number_total.html()) + 1);
						}
						$j(load_container).val(current_file).trigger("liszt:updated");
						$j(delete_container).val(current_file).trigger("liszt:updated");
						backup_message_container.html("<div class=\"alert alert-block alert-success\"><h4 style=\"text-transform: uppercase;\">'.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_DONE').'</h4> '.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_CURRENT_SETTINGS_SAVED').' \"" + current_file + "\".</div>");
						setInterval(function() {
						   reset_message();
						}, 3000);
					
					} else if (current_operation == "delete") {
					
						// delete function
						$j(load_container).find("option[value=\"" + current_file + "\"]").remove();
						$j(delete_container).find("option[value=\"" + current_file + "\"]").remove();
						files_number_this.html(parseInt(files_number_this.html()) - 1);
						files_number_total.html(parseInt(files_number_total.html()) - 1);
						$j(load_container).val("").trigger("liszt:updated");
						$j(delete_container).val("").trigger("liszt:updated");
						backup_message_container.html("<div class=\"alert alert-block alert-success\"><h4 style=\"text-transform: uppercase;\">'.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_DONE').'</h4> '.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_FILE').' \"" + current_file + "\" '.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_FILE_DELETED').'.</div>");
						setInterval(function() {
						   reset_message();
						}, 3000);
						
					} else if (current_operation == "load") {
					
						// load function
						backup_message_container.html("<div class=\"alert alert-block alert-success\"><h4 style=\"text-transform: uppercase;\">'.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_DONE').'</h4> '.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_FILE').' \"" + current_file + "\" '.Text::_('PLG_CONTENT_PARAMSBACKUP_MESSAGE_FILE_LOADED').'.</div>");
						setInterval(function() {
							// reload the page to load loaded values into fields
							window.location = current_url.replace("#", "").replace("&backup_task=" + current_operation + "&backup_file=" + current_file, "");
						}, 500);
					}
				}
			});
		
		}
				
	});
	');

}

?>