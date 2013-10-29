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
 * OAuth Controller class for converting authorised credentials to token credentials for the Joomla.Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 * @since       12.3
 */
class JOauth2ControllerConvert extends JOauth2ControllerBase
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
	 * @since   12.3
	 */
	public function execute()
	{
		// Verify that we have an OAuth 2.0 application.
		$this->initialise();

		// Get the credentials for the request.
		$credentials = new JOauth2Credentials($this->request);
		$credentials->load();

		// Getting the client object
		$client = $this->fetchClient($this->request->client_id);

		// Doing authentication using Joomla! users
		if ($credentials->doJoomlaAuthentication($client) == false)
		{
			$this->respondError(400, 'unauthorized_client', 'The Joomla! credentials are not valid.');
		}

		// Load the JUser class on application for this client
		$this->app->loadIdentity($client->_identity);

		// Ensure the credentials are authorised.
		if ($credentials->getType() === JOauth2Credentials::TOKEN)
		{
			$this->respondError(400, 'invalid_request', 'The token is not for a temporary credentials set.');
		}

		// Ensure the credentials are authorised.
		if ($credentials->getType() !== JOauth2Credentials::AUTHORISED)
		{
			$this->respondError(400, 'invalid_request', 'The token has not been authorised by the resource owner.');
		}

		// Convert the credentials to valid Token credentials for requesting protected resources.
		$credentials->convert();

		// Build the response for the client.
		$response = array(
			'access_token' => $credentials->getAccessToken(),
			'expires_in' => 'P60M',
			'refresh_token' => $credentials->getRefreshToken()
		);

		// Check if the request is CORS ( Cross-origin resource sharing ) and change the body if true
 		$body = $this->prepareBody($response);

		// Set the response code and body.
		$this->response->setHeader('status', '200')
			->setBody($body)
			->respond();
	}
}
