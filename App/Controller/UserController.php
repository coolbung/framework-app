<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Controller;

use App\Authentication\GitHub\GitHubLoginHelper;
use App\Authentication\GitHub\GitHubUser;

use Joomla\Date\Date;
use Joomla\Registry\Registry;
use Joomla\Github\Github;
use Joomla\Github\Http;
use Joomla\Http\HttpFactory;

/**
 * Login controller class for the users component
 *
 * @since  1.0
 */
class UserController extends DefaultController
{
	/**
	 * Method to log in the user
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   1.0
	 * @throws  \Exception
	 */
	public function login()
	{
		/* @type \App\App $app */
		$app = $this->getApplication();

		/* @type GitHubUser $user */
		$user = $app->getUser();

		if ($user->id)
		{
			// The user is already logged in.
			$app->enqueueMessage('User already logged in', 'message');
			$app->redirect($app->get('uri.base.path'));
		}

		$error = $app->input->get('error');

		if ($error)
		{
			// GitHub reported an error.
			$app->enqueueMessage('An error was encountered - ' . $error, 'error');
			$app->redirect($app->get('uri.base.path'));
		}

		$code = $app->input->get('code');

		if (!$code)
		{
			// No auth code supplied.
			$app->enqueueMessage('Missing login code', 'error');
			$app->redirect($app->get('uri.base.path'));
		}

		// Do login
		$loginHelper = new GitHubLoginHelper($app->get('github.client_id'), $app->get('github.client_secret'));

		$accessToken = $loginHelper->requestToken($code);

		// Store the token into the session
		$app->getSession()->set('gh_oauth_access_token', $accessToken);

		// Get the current logged in GitHub user
		$options = new Registry;
		$options->set('gh.token', $accessToken);

		// GitHub API works best with cURL
		$transport = HttpFactory::getAvailableDriver($options, array('curl'));

		$http = new Http($options, $transport);

		// Instantiate Github
		$gitHub = new Github($options, $http);

		$gitHubUser = $gitHub->users->getAuthenticatedUser();

		$user = new GithubUser;

		$user->loadGitHubData($gitHubUser)->loadByUserName($user->username);

		// Set the last visit time
		GitHubLoginHelper::setLastVisitTime($user->id);

		// User login
		$app->setUser($user);

		$redirect = $app->input->getBase64('usr_redirect');

		$redirect = $redirect ? base64_decode($redirect) : '';

		$app->enqueueMessage(sprintf('User %s logged in', $user->name), 'success');
		$app->redirect($redirect);
	}

	/**
	 * Logout the user
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function logout()
	{
		$app = $this->getApplication();

		// Logout the user.
		$app->setUser();

		$app->enqueueMessage('User logged out', 'success');
		$app->redirect($app->get('uri.base.path'));
	}
}
