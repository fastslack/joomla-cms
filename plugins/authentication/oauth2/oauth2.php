<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Authentication.oauth2
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('cms.html.html');
jimport('joomla.user.helper');

/**
 * OAuth2 Authentication Plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Authentication.oauth2
 * @since       1.0
 */
class PlgAuthenticationOAuth2 extends JPlugin
{
	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @param   array   $credentials  Array holding the user credentials
	 * @param   array   $options      Array of extra options
	 * @param   object  $response     Authentication response object
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function onBeforeExecute ()
	{
		//if (!$this->isSSLConnection()) {
		//	exit;
		//}

		// Init the flag
		$request = false;
		// Load the Joomla! application
		$app = JFactory::getApplication();
		// Get the OAuth2 server instance
		$oauth_server = new JOAuth2Server;

		if ($oauth_server->listen())
		{
			$request = true;
		}
	}

	public function onUserAuthenticate() {}

	/**
	 * Determine if we are using a secure (SSL) connection.
	 *
	 * @return  boolean  True if using SSL, false if not.
	 *
	 * @since   1.0
	 */
	public function isSSLConnection()
	{
		return ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) || getenv('SSL_PROTOCOL_VERSION'));
	}
}
