<?php

// ------------------- //
// --- Idiorm Demo --- //
// ------------------- //

// Note: This is just about the simplest database-driven webapp it's possible to create
// and is designed only for the purpose of demonstrating how Idiorm works.

// In case it's not obvious: this is not the correct way to build web applications!

// Require the idiorm file

require_once("../../config.php");

global $CFG;
//require_once($CFG->dirroot . "/vendor/j4mie/idiorm/idiorm.php");
require_once($CFG->dirroot . "/local/orm/vendor/j4mie/idiorm/idiorm.php");

// Connect to the moodle database file
//$CFG->prefix    = 'mdl_'; /*$CFG->dbtype*/
$configstr = 'mysql:host=' . $CFG->dbhost . ';dbname=' . $CFG->dbname;
ORM::configure($configstr);
ORM::configure('username', $CFG->dbuser);
ORM::configure('password', $CFG->dbpass);

// This grabs the raw database connection from the ORM
// class and creates the table if it doesn't already exist.
// Wouldn't normally be needed if the table is already there.
$db = ORM::get_db();
$table = 'mdl_user';
// Handle POST submission
if (!empty($_POST)) {

    // Create a new contact object
    $user = ORM::for_table($table)->create();

    // SHOULD BE MORE ERROR CHECKING HERE!

    // Set the properties of the object
    $user->username = $_POST['name'];
    $user->email = $_POST['email'];
    $user->firstname = ucfirst($_POST['name']);
    $user->lastname = ucfirst($_POST['name']);

    // Save the object to the database
    $user->save();

    // Redirect to self.
    header('Location: ' . basename(__FILE__));
    exit;
}

// Get a list of all contacts from the database
$count = ORM::for_table('mdl_user')->count();
$users = ORM::for_table('mdl_user')->find_many();
?>

<html>
    <head>
        <title>Idiorm Moodle Demo</title>
    </head>

    <body>
    
        <h1>Idiorm Moodle Demo</h1>

        <h2>Contact List (<?php echo $count; ?> contacts)</h2>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <strong><?php echo $user->username . ': ' . $user->firstname ?></strong>
                    <a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <form method="post" action="">
            <h2>Add Contact</h2>
            <p><label for="name">Name:</label> <input type="text" name="name" /></p>
            <p><label for="email">Email:</label> <input type="email" name="email" /></p>
            <input type="submit" value="Create" />
        </form>
    </body>
</html>
