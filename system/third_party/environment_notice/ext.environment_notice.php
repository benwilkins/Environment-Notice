<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Environment Notice Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Ben Wilkins
 * @link		http://benwilkins.me/
 */

class Environment_notice_ext
{
	public $settings 		= array();
	public $description		= 'Provides an alert to make sure the user is on the correct environment.';
	public $docs_url		= 'http://benwilkins.me/';
	public $name			= 'Environment Notice';
	public $settings_exist	= 'y';
	public $version			= '1.0';

	private $EE;

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}

	// ----------------------------------------------------------------------

	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();

		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'init_notice_modal',
			'hook'		=> 'cp_js_end',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$this->EE->db->insert('extensions', $data);
	}

	// ----------------------------------------------------------------------

	/**
	 * Extension Hook
	 *
	 * Returns the functionality to the extension output hook.
	 *
	 * @return string
	 */
	public function init_notice_modal()
	{
		$dialog_html = $this->set_dialog_html();
		// var_dump($dialog_html); exit;

		$str = '$(function() {' .
			'if( $(".publishPageContents").length > 0 ) { ' .
				'$("html").append(\''.$dialog_html.'\');' .
				'$("#notice-dialog").dialog({ modal: true });' .
			'}' .
		'});';

		return !$this->EE->extensions->last_call ? $str : $this->EE->extensions->last_call . $str;
	}

	// ----------------------------------------------------------------------

	/**
	 * @return string
	 */
	private function set_dialog_html()
	{
		$html  = '';
		$html .= '<div id="notice-dialog" title="Environment Notice!">';
		$html .= '<p>Did you know that you are currently making edits to the ';
		$html .= '<strong style="background-color: #ffc;">'.$this->settings['environment_name'].' environment</strong>?	 ';
		$html .= 'If this is the wrong environment, be sure you are accessing the correct URL. ';
		$html .= ' Production and development environments <strong>do not</strong> sync automatically.</p>';
		$html .= '</div>';
		return $html;
	}

	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}

	// ----------------------------------------------------------------------

	/**
	 * Settings
	 * 
	 * @return array
	 */
	function settings()
	{
		$settings = array();
		$settings['environment_name'] = array('i', '', "Development");
		return $settings;
	}

	// ----------------------------------------------------------------------
}

/* End of file ext.environment_notice.php */
/* Location: /system/expressionengine/third_party/environment_notice/ext.environment_notice.php */