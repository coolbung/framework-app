<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Authentication;

use App\Authentication\Database\TableUsers;

use Joomla\Database\DatabaseDriver;
use Joomla\Date\Date;
use Joomla\Factory;

/**
 * Abstract class containing the application user object
 *
 * @since  1.0
 */
abstract class User implements \Serializable
{
	/**
	 * @var    integer
	 * @since  1.0
	 */
	public $id = 0;

	/**
	 * @var    string
	 * @since  1.0
	 */
	public $username = '';

	/**
	 * @var    string
	 * @since  1.0
	 */
	public $name = '';

	/**
	 * @var    string
	 * @since  1.0
	 */
	public $registerDate = '';

	/**
	 * @var    string
	 * @since  1.0
	 */
	public $lastvisitDate = '';

	/**
	 * Constructor.
	 *
	 * @param   integer  $identifier  The primary key of the user to load..
	 *
	 * @since   1.0
	 */
	public function __construct($identifier = 0)
	{
		// Load the user if it exists
		if ($identifier)
		{
			$this->load($identifier);
		}
	}

	/**
	 * Load data by a given user name.
	 *
	 * @param   string  $userName  The user name
	 *
	 * @return  TableUsers
	 *
	 * @since   1.0
	 */
	public function loadByUserName($userName)
	{
		// @todo Decouple from J\Factory
		/* @type DatabaseDriver $db */
		$db = Factory::$application->getDatabase();

		$table = new TableUsers($db);

		$table->loadByUserName($userName);

		if (!$table->id)
		{
			// Register a new user
			$date               = new Date;
			$this->registerDate = $date->format($db->getDateFormat());

			$table->save($this);
		}

		$this->id = $table->id;

		return $this;
	}

	/**
	 * Method to load a User object by user id number.
	 *
	 * @param   mixed  $identifier  The user id of the user to load.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function load($identifier)
	{
		// @todo Decouple from J\Factory
		/* @type DatabaseDriver $db */
		$db = Factory::$application->getDatabase();

		// Create the user table object
		$table = new TableUsers($db);

		// Load the TableUsers object based on the user id or throw a warning.
		if (!$table->load($identifier))
		{
			throw new \RuntimeException('Unable to load the user with id: ' . $identifier);
		}

		foreach ($table->getFields() as $key => $vlaue)
		{
			if (isset($this->$key))
			{
				$this->$key = $table->$key;
			}
		}

		return $this;
	}

	/**
	 * Serialize the object
	 *
	 * @return  string  The string representation of the object or null
	 *
	 * @since   1.0
	 */
	public function serialize()
	{
		$props = array();

		foreach (get_object_vars($this) as $key => $value)
		{
			if (in_array($key, array('authModel', 'cleared', 'authId')))
			{
				continue;
			}

			$props[$key] = $value;
		}

		return serialize($props);
	}

	/**
	 * Unserialize the object
	 *
	 * @param   string  $serialized  The serialized string
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);

		foreach ($data as $key => $value)
		{
			$this->$key = $value;
		}
	}
}
