<?php
/**
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die( 'Restricted access' );

/**
 * OAuth2 Client Table
 *
 * @package     Joomla.Platform
 * @subpackage  OAuth2
 * @since       1.0
 */
class JOauth2TableCredentials extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since   1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__oauth_credentials', 'credentials_id', $db);
	}

	/**
	 * Delete expired credentials.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function clean()
	{
		// Build the query to delete the rows from the database.
		$query = $this->_db->getQuery(true);
		$query->delete('#__oauth_credentials')
			->where(array('expiration_date < ' . time(), 'expiration_date > 0'), 'AND')
			->where(array('temporary_expiration_date < ' . time(), 'temporary_expiration_date > 0'), 'AND');

		// Set and execute the query.
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	/**
	 * Load the credentials by key.
	 *
	 * @param   string  $key  The key for which to load the credentials.
	 * @param   string  $uri  The uri from the request.
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function loadBySecretKey($key, $uri)
	{
		// Build the query to load the row from the database.
		$query = $this->_db->getQuery(true);
		$query->select('*')
		->from('#__oauth_credentials')
		->where($this->_db->quoteName('client_secret') . ' = ' . $this->_db->quote($key))
		->where($this->_db->quoteName('resource_uri') . ' = ' . $this->_db->quote($uri));

		// Set and execute the query.
		$this->_db->setQuery($query);
		$properties = $this->_db->loadAssoc();

		// Bind the result to the object
		$this->bind($properties);
	}

	/**
	 * Load the credentials by key.
	 *
	 * @param   string  $key  The key for which to load the credentials.
	 * @param   string  $uri  The uri from the request.
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function loadByAccessToken($key, $uri)
	{
		// Build the query to load the row from the database.
		$query = $this->_db->getQuery(true);
		$query->select('*')
		->from('#__oauth_credentials')
		->where($this->_db->quoteName('access_token') . ' = ' . $this->_db->quote($key))
		->where($this->_db->quoteName('resource_uri') . ' = ' . $this->_db->quote($uri));

		// Set and execute the query.
		$this->_db->setQuery($query);
		$properties = $this->_db->loadAssoc();

		// Bind the result to the object
		$this->bind($properties);
	}
}
