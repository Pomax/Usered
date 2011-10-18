=======================================================================
Simple user registration, log-in and operation authentication framework
                   (c) Mike "Pomax" Kamermans, 2011
=======================================================================

This package offers a three file, basic user management system for
websites that use PHP in the backend.

WHEN USING THIS, MAKE SURE TO MODIFY THE SECURITY SETTINGS IN USER.PHP!!!

The authentication script is very simple in use. Simply have the user.php
script sitting somewhere that your other scripts can access it, and then
start any independent PHP script with these lines:

	require_once("user.php");
	$USER = new User();

From that point on, there will be a $USER variable available in your script,
with the following properties:

  $USER->authenticated - a boolean value repesenting the user's login status.
  $USER->username - the user's user name, or "guest user" if not authenticated.
  $USER->email - the user's email address, or an empty string for guest users.
  $USER->role - the user's role. By default all new users have the role "user".
  $USER->userdir - the user's data directory, or false if not authenticated.

There is a second way to create a new user, which allows you to pass the
name of the function that should be called when a new user registers with
the system. This is something you typically use only in the script that
acts as main entry point:

	require_once("user.php");
	$USER = new User("registration_completed_function_name");

This will call your function as:

  registration_completed_function_name($username, $email, $userdir)

This provides your function with the newly registered user's username, email
address and data directory location. This lets you hook into the registration
process to ensure that any tasks that you need performed when a new user
joins (such as file or database manipulations for your own system) can be
triggered.

Note that it doesn't matter whether you use a single entry script, or several
distinct scripts. If you have six scripts to perform various tasks as separate
files (meaning they don't require or include each other), simply add the
require_once(...) and new User() lines at the start of each file, and each
script will be able to deal with session-authenticated users. Simply make tasks
conditional on $USER->authenticated, and things will just work. This includes
scripts that you only call through GET and POST request via XHR ("ajax" requests).


  This code is hosted on github, at http://github.com/Pomax/Usered

  A demonstration can be foudn at http://pomax.nihongoresources.com/pages/usered

=======================================================================
This project is licensed under the MIT ("Expat" version) license.
=======================================================================
