<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// Define required paths
define('JPATH_ROOT',          dirname(__DIR__));
define('JPATH_CONFIGURATION', JPATH_ROOT . '/App/Config');
define('JPATH_SETUP',         JPATH_ROOT . '/App/Setup');
define('JPATH_TEMPLATES',     JPATH_ROOT . '/App/Templates');

// Load the Composer autoloader
require JPATH_ROOT . '/vendor/autoload.php';

// Instantiate the application.
$application = new App\App;

// Execute the application.
$application->execute();
