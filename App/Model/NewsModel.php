<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Model;

use Joomla\Filter\InputFilter;
use Joomla\Registry\Registry;
use Joomla\String\String;
use App\Table\NewsTable;
use App\Model\DefaultModel;

/**
 * Model to get data for the news articles
 *
 * @since  1.0
 */
class NewsModel extends DefaultModel
{

	public $model = 'news';
	public $table = 'news';

}
