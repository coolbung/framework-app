<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Model;

use Joomla\Input\Input;
use Joomla\Model\AbstractDatabaseModel;
use Joomla\Database\DatabaseDriver;

/**
 * Default model for the application.
 *
 * @since  1.0
 */
class DefaultModel extends AbstractDatabaseModel
{
	/**
	 * Model name
	 *
	 * @var    Container
	 * @since  1.0
	 */
	public $model = 'default';

	/**
	 * Table object
	 *
	 * @var    Container
	 * @since  1.0
	 */
	public $table;

	/**
	 * Input object
	 *
	 * @var    Input
	 * @since  1.0
	 */
	protected $input;

	/**
	 * Instantiate the model.
	 *
	 * @param   Input           $input  Input object.
	 * @param   DatabaseDriver  $db     The database adapter.
	 * @param   Registry        $state  The model state.
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input, DatabaseDriver $db, Registry $state = null)
	{
		parent::__construct($db);

		$this->input		= $input;
		$this->db			= $db;
		$this->tableclass	= '\\App\Table\\' . ucfirst($this->model) . 'Table';
	}
	
	public function save(array $src) {
		$ignore_fields = array ('_rawRoute','task');
		if (!class_exists($this->tableclass)) {
			return false;
		}
		$table = new $this->tableclass($this->db);
		$table->save($src, $ignore_fields);
		return $this;
	}	

	public function load($keys = null, $reset = true) {
		if (!class_exists($this->tableclass)) {
			return false;
		}
		$table = new $this->tableclass($this->db);
		$table->load($keys);
		
	}

	function buildQuery($count = false)
	{
		/* for filter by name and type*/
		$search = $this->input->get('search', array(), 'array');

		$query = $this->db->getQuery(true)
			->select($count ? 'COUNT(-1)' : 'a.*')
			->from($this->db->quoteName('#__'.$this->table,'a'));

		/* query condition for filter by name and type*/
		if ($search)
		{
			foreach($search as $k=>$val)
			{
				$query->where("a.".$k . " LIKE '%". $val."%'");
			}
		}
		return $query;
	}
	
	/**
	 * Get total
	 *
	 * @return  int total
	 *
	 * @since   1.0
	 */
	function getTotal()
	{
		$query = $this->buildQuery(true);
		$total = $this->db->setQuery($query)->loadResult();
		return $total;
	}

	/**
	 * Retrieve a single item
	 *
	 * @return  object item
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function getItem()
	{
		$id    = $this->input->getUint('id');

		if (!$id)
		{
			throw new \UnexpectedValueException('No '.$this->model.' identifier provided.');
		}

		$query = $this->buildQuery();

		if ($id)
		{
			$query->where($this->db->quoteName('a.'.$this->model.'_id') . ' = ' . (int) $id);
		}

		return $this->db->setQuery($query)->loadObject();
	}

	/**
	 * Retrieve all items
	 *
	 * @return  object  Container with items
	 *
	 * @since   1.0
	 */
	public function getItems()
	{
		$start 	= $this->input->getUint('start', 0);
		$end   	= $this->input->getUint('end', 20);
		$data 	= array();
		$query 	= $this->buildQuery();

		$data = $this->db->setQuery($query, $start, $end)->loadObjectList();
		return $data;
	}

}
