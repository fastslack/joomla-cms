<?php
/**
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die( 'Restricted access' );

// Register component prefix
JLoader::registerPrefix('JOauth2', __DIR__);

/**
 * JOauth2ProtocolRequest class
 *
 * @package  Joomla.Platform
 * @since    1.0
 */
class JOauth2Server
{
	/**
	 * @var    JRegistry  Options for the JOauth2Client object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    JHttp  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $http;

	/**
	 * @var    JOauth2ProtocolRequest  The input object to use in retrieving GET/POST data.
	 * @since  1.0
	 */
	protected $request;

	/**
	 * @var    JOauth2ProtocolRequest  The input object to use in retrieving GET/POST data.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry               $options  The options object.
	 * @param   JHttp                   $http     The HTTP client object.
	 * @param   JOauth2ProtocolRequest  $request  The request object.
	 *
	 * @since   1.0
	 */
	public function __construct(JRegistry $options = null, JHttp $http = null, JOauth2ProtocolRequest $request = null)
	{
		// Setup the options object.
		$this->options = isset($options) ? $options : new JRegistry;

		// Setup the JHttp object.
		$this->http = isset($http) ? $http : new JHttp($this->options);

		// Setup the request object.
		$this->request = isset($request) ? $request : new JOauth2ProtocolRequest;

		// Setup the response object.
		$this->response = isset($response) ? $response : new JOauth2ProtocolResponse;

		// Getting application
		$this->_app = JFactory::getApplication();
	}

	/**
	 * Method to get the REST parameters for the current request. Parameters are retrieved from these locations
	 * in the order of precedence as follows:
	 *
	 *   - Authorization header
	 *   - POST variables
	 *   - GET query string variables
	 *
	 * @return  boolean  True if an REST message was found in the request.
	 *
	 * @since   1.0
	 */
	public function listen()
	{
		// Initialize variables.
		$found = false;

		// Get the OAuth 2.0 message from the request if there is one.
		$found = $this->request->fetchMessageFromRequest();

		if (!$found)
		{
			return false;
		}

		// If we found an REST message somewhere we need to set the URI and request method.
		if ($found && isset($this->request->response_type) && !isset($this->request->access_token) )
		{
			// Load the correct controller type
			switch ($this->request->response_type)
			{
				case 'temporary':

					$controller = new JOauth2ControllerInitialise($this->request);

					break;
				case 'authorise':

					$controller = new JOauth2ControllerAuthorise($this->request);

					break;
				case 'token':

					$controller = new JOauth2ControllerConvert($this->request);

					break;
				default:
					throw new InvalidArgumentException('No valid response type was found.');
					break;
			}

			// Execute the controller
			$controller->execute();

			// Exit
			exit;
		}

		// If we found an REST message somewhere we need to set the URI and request method.
		if ($found && isset($this->request->access_token) )
		{
			$controller = new JOauth2ControllerResource($this->request);
			$controller->execute();
		}

		return $found;
	}
}
