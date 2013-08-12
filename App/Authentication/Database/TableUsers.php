<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Authentication\Database;

use Joomla\Database\DatabaseDriver;

use App\Table\DefaultDatabaseTable;

/**
 * Table class for interfacing with the #__users table
 *
 * @property   integer  $id             PK
 * @property   string   $name           The users name
 * @property   string   $username       The users username
 * @property   string   $registerDate   The register date
 * @property   string   $lastvisitDate  The last visit date
 *
 * @since  1.0
 */
class TableUsers extends DefaultDatabaseTable
{
	/**
	 * Constructor.
	 *
	 * @param   DatabaseDriver  $db  A database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__users', 'id', $db);
	}

	/**
	 * Load data by a given user name.
	 *
	 * @param   string  $userName  The user name
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 */
	public function loadByUserName($userName)
	{
		$check = $this->db->setQuery(
			$this->db->getQuery(true)
				->select('*')
				->from($this->tableName)
				->where($this->db->quoteName('username') . ' = ' . $this->db->quote($userName))
		)->loadObject();

		return ($check) ? $this->bind($check) : $this;
	}
}
