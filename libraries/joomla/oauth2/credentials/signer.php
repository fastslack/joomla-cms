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
 * OAuth message signer interface for the Joomla.Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 * @since       1.0
 */
class JOauth2CredentialsSigner
{
	/**
	 * Method to get a message signer object based on the message's oauth_signature_method parameter.
	 *
	 * @param   string  $method  The method of the signer (HMAC-SHA1 || RSA-SHA1 || PLAINTEXT)
	 *
	 * @return  JOauth2CredentialsSigner  The OAuth message signer object for the message.
	 *
	 * @since   1.0
	 * @throws  InvalidArgumentException
	 */
	public static function getInstance($method)
	{
		switch ($method)
		{
			case 'HMAC-SHA1':
				$signer = new JOauth2CredentialsSignerHMAC;
				break;
			case 'RSA-SHA1':
				// @TODO We don't support RSA because we don't yet have a way to inject the private key.
				throw new InvalidArgumentException('RSA signatures are not supported');
				break;
			case 'PLAINTEXT':
				$signer = new JOauth2CredentialsSignerPlaintext;
				break;
			default:
				throw new InvalidArgumentException('No valid signature method was found.');
				break;
		}

		return $signer;
	}

	/**
	 * Perform a password authentication challenge.
	 *
	 * @param   JOauth2Client  $client   The client object
	 * @param   string         $request  The request object.
	 *
	 * @return  boolean  True if authentication is ok, false if not
	 *
	 * @since   1.0
	 */
	public function doJoomlaAuthentication(JOauth2Client $client, $request)
	{
		// Build the response for the client.
		$types = array('PHP_AUTH_', 'PHP_HTTP_', 'PHP_');

		foreach ( $types as $type )
		{
			if (isset($request->_headers[$type . 'USER']))
			{
				$user_decode = base64_decode($request->_headers[$type . 'USER']);
			}

			if (isset($request->_headers[$type . 'PW']))
			{
				$password_decode = base64_decode($request->_headers[$type . 'PW']);
			}
		}

		// Check if the username and password are present
		if ( !isset($user_decode) || !isset($password_decode) )
		{
			if (isset($request->client_id))
			{
				$user_decode = explode(":", base64_decode($request->client_id));
				$user_decode = $user_decode[0];
			}

			if (isset($request->client_secret))
			{
				$password_decode = explode(":", base64_decode($request->client_secret));
				$password_decode = base64_decode($password_decode[1]);
				$password_decode = explode(":", $password_decode);
				$password_decode = $password_decode[0];
			}
		}

		// Check if the username and password are present
		if (!isset($user_decode) || !isset($password_decode))
		{
			throw new Exception('Username or password is not set');
			exit;
		}

		// Check the identity
		$match = false;

		if (substr($client->_identity->password, 0, 4) == '$2y$')
		{
			// BCrypt passwords are always 60 characters, but it is possible that salt is appended although non standard.
			$password60 = substr($client->_identity->password, 0, 60);

			if (JCrypt::hasStrongPasswordSupport())
			{
				$match = password_verify($password_decode, $password60);
			}
		}
		elseif (substr($client->_identity->password, 0, 8) == '{SHA256}')
		{
			// Check the password
			$parts	= explode(':', $client->_identity->password);
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			$testcrypt = JUserHelper::getCryptedPassword($password_decode, $salt, 'sha256', false);

			if ($crypt == $testcrypt)
			{
				$match = true;
			}
		}
		else
		{
			// Check the password
			$parts	= explode(':', $client->_identity->password);
			$crypt	= $parts[0];
			$salt	= @$parts[1];

			$testcrypt = JUserHelper::getCryptedPassword($password_decode, $salt, 'md5-hex', false);

			if ($crypt == $testcrypt)
			{
				$match = true;
			}
		}

		return $match;
	}
}
