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
 * OAuth PLAINTEXT Signature Method class for the Joomla.Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 * @since       1.0
 */
class JOauth2CredentialsSignerPlaintext extends JOauth2CredentialsSigner
{
	/**
	 * Calculate and return the OAuth message signature using PLAINTEXT
	 *
	 * @param   string  $baseString        The OAuth message as a normalized base string.
	 * @param   string  $clientSecret      The OAuth client's secret.
	 * @param   string  $credentialSecret  The OAuth credentials' secret.
	 *
	 * @return  string  The OAuth message signature.
	 *
	 * @since   1.0
	 * @throws  InvalidArgumentException
	 */
	public function sign($baseString, $clientSecret, $credentialSecret)
	{
		return $clientSecret . '&' . $credentialSecret;
	}

	/**
	 * Decode the client secret key
	 *
	 * @param   string  $clientSecret  The OAuth client's secret.
	 *
	 * @return  string  The decoded key
	 *
	 * @since   1.0
	 * @throws  InvalidArgumentException
	 */
	public function secretDecode($clientSecret)
	{
		$clientSecret = explode(":", base64_decode($clientSecret));
		$clientSecret = $clientSecret[1];

		return $clientSecret;
	}
}
