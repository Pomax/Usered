<?php
  date_default_timezone_set("America/Vancouver");

	$data = false;

	ini_set("display_errors", 1);
	ini_set("error_reporting", E_ALL | E_STRICT);

	// this is a demonstrator function, which gets called when new users register
	function registration_callback($username, $email, $userdir)
	{
		// all it does is bind registration data in a global array,
		// which is echoed on the page after a registration
		global $data;
		$data = array($username, $email, $userdir);
	}

	require_once("user.php");
	$USER = new User("registration_callback");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Simple PHP+SQLite user registration, log-in and operation authentication</title>
		<meta charset="utf-8"/>
		<script type="text/javascript" src="js/sha1.js"></script>
		<script type="text/javascript" src="js/user.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css"></link>
	</head>

	<body>
		<h1>Simple user registration, log-in and operation authentication framework</h1>

		<?php if($USER->error!="") { ?>
		<p class="error">Error: <?php echo $USER->error; ?></p>
		<?php } ?>

		<p>This page is a functional demonstrator, where you can test the various functions of the
		user framework. On the left you can register new users, log in, update your email address
		or password, log out, or request password resets.</p>

		<p>On the right you can see information pertaining to you, as current user. When
		registering or logging in it will show your session identifier and authentication status, POST
		information received by the user system, as well as the system's information and error log
		content. The code can be downloaded from <a href="http://github.com/Pomax/Usered">github</a>
		or for those who just want a zip file, that's <a href="https://github.com/Pomax/Usered/zipball/master">also
		available</a>.</p>


		<table style="width: 100%; margin-top: 1em;"><tr><td style="width: 24em; padding-top:1em;">
<?php		if(!$USER->authenticated) { ?>

			<!-- Allow a new user to register -->
			<form class="controlbox" name="new user registration" id="registration" action="index.php" method="POST">
				<input type="hidden" name="op" value="register"/>
				<input type="hidden" name="sha1" value=""/>
				<table>
					<tr><td>user name </td><td><input type="text" name="username" value="" /></td></tr>
					<tr><td>email address </td><td><input type="text" name="email" value="" /></td></tr>
					<tr><td>password </td><td><input type="password" name="password1" value="" /></td></tr>
					<tr><td>password (again) </td><td><input type="password" name="password2" value="" /></td></tr>
				</table>
				<input type="button" value="register" onclick="User.processRegistration()"/>
			</form>
<?php 		}

			if(!$USER->authenticated) { ?>

			<!-- Allow a user to log in -->
			<form class="controlbox" name="log in" id="login" action="index.php" method="POST">
				<input type="hidden" name="op" value="login"/>
				<input type="hidden" name="sha1" value=""/>
				<table>
					<tr><td>user name </td><td><input type="text" name="username" value="" /></td></tr>
					<tr><td>password </td><td><input type="password" name="password1" value="" /></td></tr>
				</table>
				<input type="button" value="log in" onclick="User.processLogin()"/>
			</form>
<?php 		}

			if(!$USER->authenticated) { ?>

			<!-- Request a new password from the system -->
			<form class="controlbox" name="forgotten passwords" id="reset" action="index.php" method="POST">
				<input type="hidden" name="op" value="reset"/>
				<table>
					<tr><td>email address </td><td><input type="text" name="email" value="<?php $USER->email; ?>" /></td></tr>
				</table>
				<input type="submit" value="reset password"/>
			</form>
<?php 		}

			if($USER->authenticated) { ?>

			<!-- Log out option -->
			<form class="controlbox" name="log out" id="logout" action="index.php" method="POST">
				<input type="hidden" name="op" value="logout"/>
				<input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
				<p>You are logged in as <?php echo $_SESSION["username"]; ?></p>
				<input type="submit" value="log out"/>
			</form>
<?php 		}

			if($USER->authenticated) { ?>

			<!-- If a user is logged in, her or she can modify their email and password -->
			<form class="controlbox" name="update" id="update" action="index.php" method="POST">
				<input type="hidden" name="op" value="update"/>
				<input type="hidden" name="sha1" value=""/>
				<p>Update your email address and/or password here</p>
				<table>
					<tr><td>email address </td><td><input type="text" name="email" value="<?php $USER->email; ?>" /></td></tr>
					<tr><td>new password </td><td><input type="password" name="password1" value="" /></td></tr>
					<tr><td>new password (again) </td><td><input type="password" name="password2" value="" /></td></tr>
				</table>
				<input type="button" value="update" onclick="User.processUpdate()"/>
			</form>
<?php 		}

			if($USER->authenticated) { ?>

			<!-- If a user is logged in, they can elect to unregister -->
			<form class="controlbox" name="unregister" id="unregister" action="index.php" method="POST">
				<input type="hidden" name="op" value="unregister"/>
				<input type="hidden" name="username"value="<?php echo $_SESSION["username"]; ?>" />
				<p>To unregister, press the button...</p>
				<input type="submit" value="unregister"/>
			</form>
<?php 		} ?>

		</td><td style="padding-left: 4em;">
			<p>current user: <?php echo $_SESSION["username"]; ?><br>
			   Session token for <?php echo $_SESSION["username"]; ?>: <?php echo $_SESSION["token"]; ?><br/>(authenticated: <?php echo ($USER->authenticated ? "yes" : "no");  echo ($USER->userdir? ", user data directory: $USER->userdir" : ""); ?>)</p>
			<hr/>
			<p>POST: <?php echo str_replace("\n", "<br/>\n\t\t\t", print_r($_POST, true)); ?></p>
			<hr/>
			<p>INFO LOG: <?php echo str_replace("\n", "<br/>\n\t\t\t", print_r($USER->info_log, true)); ?></p>
			<hr/>
			<p>ERROR LOG: <?php echo str_replace("\n", "<br/>\n\t\t\t", print_r($USER->error_log, true)); ?></p>

<?php
			if($data !== false) { echo "data set: " . print_r($data,true); }
?>
		</td></tr><table>

		<h2>Using the system</h2>

		<div>
			<p>The authentication script is very simple in use. Simply have the user.php script sitting somewhere
			that your other scripts can access it, and then start any independent PHP script with these lines:</p>

			<pre>	require_once("user.php");
	$USER = new User();</pre>

			<p>From that point on, there will be a $USER variable available in your script, with the following properties:</p>

			<ul>
				<li>$USER->authenticated - a boolean value that is "true" if the user is logged in, and "false" otherwise.</li>
				<li>$USER->username - the user's user name, or "guest user" if not authenticated.</li>
				<li>$USER->email - the user's email address, or an empty string for guest users.</li>
				<li>$USER->role - the user's role. By default all new users have the role "user".</li>
				<li>$USER->userdir - the user's data directory, or false if not authenticated.</li>
			</ul>

			<p>There is a second way to create a new user, which allows you to pass the name of the function that should
			be called when a new user registers with the system. This is something you typically use only in the script that
			acts as main entry point:</p>

			<pre>	require_once("user.php");
	$USER = new User("registration_completed_function_name");</pre>

			<p>This will call your function as <i>registration_completed_function_name($username, $email, $userdir)</i>,
			providing it with the newly registered user's username, email address and data directory location. This lets
			you hook into the registration process to ensure that any tasks that you need performed when a new
			user joins (such as file or database manipulations for your own system) can be triggered.</p>

			<p>Note that it doesn't matter whether you use a single entry script, or several distinct scripts.
			If you have six scripts to perform various tasks as separate files (meaning they don't require
			or include each other), simply add the <i>require_once</i> and <i>new User()</i> lines at the
			start of each file, and each script will be able to deal with session-authenticated users. Simply
			make tasks conditional on $USER->authenticated, and things will just work. This includes scripts
			that you only call through GET and POST request via XHR ("ajax" requests).</p>


		</div>
	</body>
</html>