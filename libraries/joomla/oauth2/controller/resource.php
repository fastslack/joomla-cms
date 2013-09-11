<?php
/**
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * OAuth Controller class for initiating temporary credentials for the Joomla.Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 * @since       1.0
 */
class JOauth2ControllerResource extends JOauth2ControllerBase
{
	/**
	 * Constructor.
	 *
	 * @param   JOauth2ProtocolRequest   $request   The request object
	 * @param   JOauth2ProtocolResponse  $response  The response object
	 *
	 * @since   1.0
	 */
	public function __construct(JOauth2ProtocolRequest $request = null, JOauth2ProtocolResponse $response = null)
	{
		// Call parent first
		parent::__construct();

		// Setup the request object.
		$this->request = isset($request) ? $request : new JOauth2ProtocolRequest;

		// Setup the response object.
		$this->response = isset($response) ? $response : new JOauth2ProtocolResponse;
	}

	/**
	 * Handle the request.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		// Verify that we have an OAuth 2.0 application.
		$this->initialise();

		// Generate temporary credentials for the client.
		$credentials = new JOauth2Credentials($this->request);
		$credentials->load();

		// Getting the client object
		$client = $this->fetchClient($this->request->client_id);

		// Ensure the credentials are authorised.
		if ($credentials->getType() !== JOauth2Credentials::TOKEN)
		{
			$this->respondError(400, 'invalid_request', 'The token is not for a valid credentials yet.');
		}

		// Load the JUser class on application for this client
		$this->app->loadIdentity($client->_identity);
	}
}
