<?php
/**
* Class file for LG FirePHP.
* 
* This file must be placed in the
* /system/extensions/ folder in your ExpressionEngine installation.
*
* @package LgFirePHP
* @version 1.0.0
* @author Leevi Graham <http://leevigraham.com>
* @see http://leevigraham.com/cms-customisation/expressionengine/addon/lg-firePHP/
* @copyright Copyright (c) 2007-2009 Leevi Graham
* @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported
*/

if ( ! defined('EXT')) exit('Invalid file request');

define("LG_FPHP_version",			"1.0.0");
define("LG_FPHP_docs_url",			"http://leevigraham.com/cms-customisation/expressionengine/addon/lg-firePHP/");
define("LG_FPHP_addon_id",			"LG FirePHP");
define("LG_FPHP_extension_class",	"Lg_firephp_ext");
define("LG_FPHP_cache_name",		"lg_cache");


/**
* Adds the firePHP Library for FireBug Debugging
*
* @package LgFirePHP
* @version 1.0.0
* @author Leevi Graham <http://leevigraham.com>
* @see http://leevigraham.com/cms-customisation/expressionengine/addon/lg-firePHP/
* @copyright Copyright (c) 2007-2009 Leevi Graham
* @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 Unported
*/
class Lg_firephp_ext {

	/**
	* Extension settings
	* @var array
	*/
	var $settings			= array();

	/**
	* Extension name
	* @var string
	*/
	var $name				= LG_FPHP_addon_id;

	/**
	* Extension version
	* @var string
	*/
	var $version			= LG_FPHP_version;

	/**
	* Extension description
	* @var string
	*/
	var $description		= 'Adds the FirePHP Library for FireBug Debugging.';

	/**
	* If $settings_exist = 'y' then a settings page will be shown in the ExpressionEngine admin
	* @var string
	*/
	var $settings_exist		= 'y';

	/**
	* Link to extension documentation
	* @var string
	*/
	var $docs_url			= LG_FPHP_docs_url;



	/**
	* PHP4 Constructor
	*
	* @see __construct()
	*/
	function Lg_firephp_ext($settings='')
	{
		$this->__construct($settings);
	}



	/**
	* PHP 5 Constructor
	*
	* @param	array|string $settings Extension settings associative array or an empty string
	* @since	Version 1.1.0
	*/
	function __construct($settings='')
	{
		global $IN, $SESS;

		// get the settings from our helper class
		// this returns all the sites settings
		$this->settings = $this->_get_settings();

		if(isset($SESS->cache['lg']) === FALSE){
			$SESS->cache['lg'] = array();
		}
		$this->debug = $IN->GBL('debug');
	}



	/**
	* Configuration for the extension settings page
	**/
	function settings_form($current)
	{
		global $DB, $DSP, $LANG, $IN, $PREFS, $SESS;

		// create a local variable for the site settings
		$settings = $this->_get_settings();

		$DSP->crumbline = TRUE;

		$DSP->title  = $LANG->line('extension_settings');
		$DSP->crumb  = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'area=utilities', $LANG->line('utilities')).
		$DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=extensions_manager', $LANG->line('extensions_manager')));

		$DSP->crumb .= $DSP->crumb_item($LANG->line('lg_firephp_title') . " {$this->version}");

		$DSP->right_crumb($LANG->line('disable_extension'), BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=toggle_extension_confirm'.AMP.'which=disable'.AMP.'name='.$IN->GBL('name'));

		$DSP->body = '';

		if(isset($settings['show_donate']) === FALSE) {$settings['show_donate'] = 'y';}
		if($settings['show_donate'] == 'y')
		{
			$DSP->body .= "<style type='text/css' media='screen'>
				#donate{float:right; margin-top:0; padding-left:190px; position:relative; top:-2px}
				#donate .button{background:transparent url(http://leevigraham.com/themes/site_themes/default/img/btn_paypal-donation.png) no-repeat scroll left bottom; display:block; height:0; overflow:hidden; position:absolute; top:0; left:0; padding-top:27px; text-decoration:none; width:175px}
				#donate .button:hover{background-position:top right;}
			</style>";
			$DSP->body .= "<p id='donate'>
							" . $LANG->line('donation') ."
							<a rel='external' href='https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=sales%40newism%2ecom.au&amp;item_name=LG%20Expression%20Engine%20Development&amp;amount=%2e00&amp;no_shipping=1&amp;return=http%3a%2f%2fleevigraham%2ecom%2fdonate%2fthanks&amp;cancel_return=http%3a%2f%2fleevigraham%2ecom%2fdonate%2fno%2dthanks&amp;no_note=1&amp;tax=0&amp;currency_code=USD&amp;lc=US&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8' class='button' target='_blank'>Donate</a>
						</p>";
		}

		$DSP->body .= $DSP->heading($LANG->line('lg_firephp_title') . " <small>{$this->version}</small>");

		$DSP->body .= $DSP->form_open(
								array(
									'action' => 'C=admin'.AMP.'M=utilities'.AMP.'P=save_extension_settings'
								),
								// WHAT A M*THERF!@KING B!TCH THIS WAS
								// REMBER THE NAME ATTRIBUTE MUST ALWAYS MATCH THE FILENAME AND ITS CASE SENSITIVE
								// BUG??
								array('name' => strtolower(get_class($this)))
		);

		// EXTENSION ACCESS
		$DSP->body .=   $DSP->table_open(array('class' => 'tableBorder', 'border' => '0', 'style' => 'margin-top:18px; width:100%'));

		$DSP->body .=   $DSP->tr();
		$DSP->body .=   $DSP->td('tableHeading', '', '2');
		$DSP->body .=   $LANG->line("access_rights");
		$DSP->body .=   $DSP->td_c();
		$DSP->body .=   $DSP->tr_c();

		$DSP->body .=   $DSP->tr();
		$DSP->body .=   $DSP->td('tableCellOne', '40%');
		$DSP->body .=   $DSP->qdiv('defaultBold', $LANG->line('enable_extension_for_this_site'));
		$DSP->body .=   $DSP->td_c();

		$DSP->body .=   $DSP->td('tableCellOne');
		$DSP->body .=   "<select name='enable'>"
						. $DSP->input_select_option('y', "Yes", (($settings['enable'] == 'y') ? 'y' : '' ))
						. $DSP->input_select_option('n', "No", (($settings['enable'] == 'n') ? 'y' : '' ))
						. $DSP->input_select_footer();
		$DSP->body .=   $DSP->td_c();
		$DSP->body .=   $DSP->tr_c();

		$DSP->body .=   $DSP->tr();
		$DSP->body .=   $DSP->td('tableCellTwo', '40%');
		$DSP->body .=   $DSP->qdiv('defaultBold', $LANG->line('show_test_message'));
		$DSP->body .=   $DSP->td_c();

		$DSP->body .=   $DSP->td('tableCellTwo');
		$DSP->body .=   "<select name='show_test_message'>"
						. $DSP->input_select_option('y', "Yes", (($settings['enable'] == 'y') ? 'y' : '' ))
						. $DSP->input_select_option('n', "No", (($settings['enable'] == 'n') ? 'y' : '' ))
						. $DSP->input_select_footer();
		$DSP->body .=   $DSP->td_c();
		$DSP->body .=   $DSP->tr_c();

		$DSP->body .=   $DSP->table_c();


		// UPDATE SETTINGS
		$DSP->body .=   $DSP->table_open(array('class' => 'tableBorder', 'border' => '0', 'style' => 'margin-top:18px; width:100%'));

		$DSP->body .=   $DSP->tr();
		$DSP->body .=   $DSP->td('tableHeading', '', '2');
		$DSP->body .=   $LANG->line("check_for_updates_title");
		$DSP->body .=   $DSP->td_c();
		$DSP->body .=   $DSP->tr_c();

		$DSP->body .=   $DSP->tr();
		$DSP->body .=   $DSP->td('', '', '2');
		$DSP->body .=   "<div class='box' style='border-width:0 0 1px 0; margin:0; padding:10px 5px'><p>" . $LANG->line('check_for_updates_info'). "</p></div>";
		$DSP->body .=   $DSP->td_c();
		$DSP->body .=   $DSP->tr_c();

		$DSP->body .=   $DSP->tr();
		$DSP->body .=   $DSP->td('tableCellOne', '40%');
		$DSP->body .=   $DSP->qdiv('defaultBold', $LANG->line("check_for_updates_label"));
		$DSP->body .=   $DSP->td_c();


		$DSP->body .=   $DSP->td('tableCellOne');
		$DSP->body .=   "<select name='check_for_updates'>"
						. $DSP->input_select_option('y', "Yes", (($settings['check_for_updates'] == 'y') ? 'y' : '' ))
						. $DSP->input_select_option('n', "No", (($settings['check_for_updates'] == 'n') ? 'y' : '' ))
						. $DSP->input_select_footer();
		$DSP->body .=   $DSP->td_c();
		$DSP->body .=   $DSP->tr_c();

		$DSP->body .=   $DSP->table_c();

		if($IN->GBL('lg_admin') != 'y')
		{
			$DSP->body .= $DSP->table_c();
			$DSP->body .= "<input type='hidden' value='".$settings['show_donate']."' name='show_donate' />";
			$DSP->body .= "<input type='hidden' value='".$settings['show_promos']."' name='show_promos' />";
		}
		else
		{
			$DSP->body .= $DSP->table_open(array('class' => 'tableBorder', 'border' => '0', 'style' => 'margin-top:18px; width:100%'));
			$DSP->body .= $DSP->tr()
				. $DSP->td('tableHeading', '', '2')
				. $LANG->line("lg_admin_title")
				. $DSP->td_c()
				. $DSP->tr_c();

			$DSP->body .= $DSP->tr()
				. $DSP->td('tableCellOne', '40%')
				. $DSP->qdiv('defaultBold', $LANG->line("show_donate_label"))
				. $DSP->td_c();

			$DSP->body .= $DSP->td('tableCellOne')
				. "<select name='show_donate'>"
						. $DSP->input_select_option('y', "Yes", (($settings['show_donate'] == 'y') ? 'y' : '' ))
						. $DSP->input_select_option('n', "No", (($settings['show_donate'] == 'n') ? 'y' : '' ))
						. $DSP->input_select_footer()
				. $DSP->td_c()
				. $DSP->tr_c();

			$DSP->body .= $DSP->tr()
				. $DSP->td('tableCellTwo', '40%')
				. $DSP->qdiv('defaultBold', $LANG->line("show_promos_label"))
				. $DSP->td_c();

			$DSP->body .= $DSP->td('tableCellTwo')
				. "<select name='show_promos'>"
						. $DSP->input_select_option('y', "Yes", (($settings['show_promos'] == 'y') ? 'y' : '' ))
						. $DSP->input_select_option('n', "No", (($settings['show_promos'] == 'n') ? 'y' : '' ))
						. $DSP->input_select_footer()
				. $DSP->td_c()
				. $DSP->tr_c();

			$DSP->body .= $DSP->table_c();
		}

		$DSP->body .=   $DSP->qdiv('itemWrapperTop', $DSP->input_submit());
		$DSP->body .=   $DSP->form_c();
	}



	/**
	* Save Settings
	* @since	1.0.0
	**/
	function save_settings()
	{
		// make somethings global
		global $DB, $IN, $PREFS, $REGX, $SESS;

		// unset the name
		unset($_POST['name']);
		
		// load the settings from cache or DB
		// force a refresh and return the full site settings
		$settings = $this->_get_settings(TRUE, TRUE);

		// add the posted values to the settings
		$settings[$PREFS->ini('site_id')] = $_POST;

		// update the settings
		$query = $DB->query($sql = "UPDATE exp_extensions SET settings = '" . addslashes(serialize($settings)) . "' WHERE class = '" . LG_FPHP_extension_class . "'");
	}



	/**
	* Returns the default settings for this extension
	* This is used when the extension is activated or when a new site is installed
	*/
	function _build_default_settings(){
		// create a default settings array
		// create a blank settings array which will be filled later
		$default_settings = array(
			'enable' 							=> 'y',
			'show_test_message' 				=> 'y',
			'check_for_updates' 				=> 'n',
			'show_donate'						=> 'y'
		);

		return $default_settings;
	}



	/**
	* Activates the extension
	*
	* @return	bool Always TRUE
	*/
	function activate_extension()
	{
		global $DB;

		$default_settings = $this->_build_default_settings();

		// get the list of installed sites
		$query = $DB->query("SELECT * FROM exp_sites");

		// for each of the sites
		foreach($query->result as $row)
		{
			// build a multi dimensional array for the settings
			$settings[$row['site_id']] = $default_settings;
		}

		// our hooks for the extension
		$hooks = array(
			'sessions_start' 					=> 'sessions_start',
			'lg_addon_update_register_source'	=> 'lg_addon_update_register_source',
			'lg_addon_update_register_addon'	=> 'lg_addon_update_register_addon'
		);

		// for each hook
		foreach ($hooks as $hook => $method)
		{
			// build sql
			$sql[] = $DB->insert_string( 'exp_extensions', 
											array('extension_id' 	=> '',
												'class'				=> get_class($this),
												'method'			=> $method,
												'hook'				=> $hook,
												'settings'			=> addslashes(serialize($settings)),
												'priority'			=> 10,
												'version'			=> $this->version,
												'enabled'			=> "y"
											)
										);
		}

		// run all sql queries
		foreach ($sql as $query)
		{
			$DB->query($query);
		}
		return TRUE;
	}



	/**
	* Loads the firePHP Library if available
	*
	* @param	object	$session	The Session Object
	* @since 	1.0.0
	* @see		http://expressionengine.com/developers/extension_hooks/sessions_start/
	*/
	function sessions_start()
	{
		if($this->settings['enable'] == 'y')
		{
			global $FB;
			ob_start();
			require_once(dirname(__FILE__).'/lg_firephp_ext/FirePHPCore/fb.php');
			if($this->settings['show_test_message'] == 'y')
			{
				FB::info('LG FirePHP is enabled', "Success");
			}
		}
	}



	/**
	* Updates the extension
	*
	* If the existing version is below 1.1.0 then the update process changes some
	* method names. This may cause an error which can be resolved by reloading
	* the page.
	*
	* @param	string $current If installed the current version of the extension otherwise an empty string
	* @return	bool FALSE if the extension is not installed or is the current version
	* @since	1.0.0
	*/
	function update_extension($current='')
	{
		global $DB;

		if ($current == '' OR $current == $this->version)
			return FALSE;

		// update the version
		$DB->query("UPDATE exp_extensions SET version = '" . $DB->escape_str($this->version) . "' WHERE class = '" . get_class($this) . "'");
	}



	/**
	* Disables the extension the extension and deletes settings from DB
	* @since	1.0.0
	*/
	function disable_extension()
	{
		global $DB;
		$DB->query("DELETE FROM exp_extensions WHERE class = '".$DB->escape_str(get_class($this))."'");
	}




	/**
	* Returns the extension settings from the DB
	*
	* @access	private
	* @param	bool	$force_refresh	Force a refresh
	* @param	bool	$return_all		Set the full array of settings rather than just the current site
	* @return	array					The settings array
	* @since	1.0.0
	*/
	function _get_settings($force_refresh = FALSE, $return_all = FALSE)
	{

		global $SESS, $DB, $REGX, $LANG, $PREFS;

		// assume there are no settings
		$settings = FALSE;

		// Get the settings for the extension
		if(isset($SESS->cache['lg'][LG_FPHP_addon_id]['settings']) === FALSE || $force_refresh === TRUE)
		{
			// check the db for extension settings
			$query = $DB->query("SELECT settings FROM exp_extensions WHERE enabled = 'y' AND class = '" . LG_FPHP_extension_class . "' LIMIT 1");

			// if there is a row and the row has settings
			if ($query->num_rows > 0 && $query->row['settings'] != '')
			{
				// save them to the cache
				$SESS->cache['lg'][LG_FPHP_addon_id]['settings'] = $REGX->array_stripslashes(unserialize($query->row['settings']));
			}
		}

		// check to see if the session has been set
		// if it has return the session
		// if not return false
		if(empty($SESS->cache['lg'][LG_FPHP_addon_id]['settings']) !== TRUE)
		{
			if($return_all === TRUE)
			{
				$settings = $SESS->cache['lg'][LG_FPHP_addon_id]['settings'];
			}
			else
			{
				if(isset($SESS->cache['lg'][LG_FPHP_addon_id]['settings'][$PREFS->ini('site_id')]) === TRUE)
				{
					$settings = $SESS->cache['lg'][LG_FPHP_addon_id]['settings'][$PREFS->ini('site_id')];
				}
				else
				{
					$settings = $this->_build_default_settings();
				}
			}
		}
		return $settings;

	}



	/**
	* Register a new Addon Source
	*
	* @param	array $sources The existing sources
	* @return	array The new source list
	* @since 	Version 2.0.0
	*/
	function lg_addon_update_register_source($sources)
	{
		global $EXT;
		// -- Check if we're not the only one using this hook
		if($EXT->last_call !== FALSE)
			$sources = $EXT->last_call;

		// add a new source
		// must be in the following format:
		/*
		<versions>
			<addon id='LG Addon Updater' version='2.0.0' last_updated="1218852797" docs_url="http://leevigraham.com/" />
		</versions>
		*/
		if($this->settings['check_for_updates'] == 'y')
		{
			$sources[] = 'http://leevigraham.com/version-check/versions.xml';
		}

		return $sources;

	}


	/**
	* Register a new Addon
	*
	* @param	array $addons The existing sources
	* @return	array The new addon list
	* @since 	Version 2.0.0
	*/
	function lg_addon_update_register_addon($addons)
	{
		global $EXT;
		// -- Check if we're not the only one using this hook
		if($EXT->last_call !== FALSE)
			$addons = $EXT->last_call;

		// add a new addon
		// the key must match the id attribute in the source xml
		// the value must be the addons current version
		if($this->settings['check_for_updates'] == 'y')
		{
			$addons[LG_FPHP_addon_id] = $this->version;
		}

		return $addons;
	}
}

?>