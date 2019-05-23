<?php

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

# Imports the Google Cloud client library
use Google\Cloud\Datastore\DatastoreClient;

# Your Google Cloud Platform project ID
$projectId = 'cico-web-app';

# The kind for the new entity
$kind = 'Address';


if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    # Instantiates a client
	$up_datastore = new DatastoreClient( [
		'projectId' => $projectId
	] );

	$formkey = $up_datastore->lookup($up_datastore->key('Form', 'theform'));
	if ($_POST['key'] !== $formkey['key']) {
	    echo 'Wrong key';
	    return;
    };

    # The Cloud Datastore key for the new entity
	$taskKey = $up_datastore->key( $kind );

    # Prepares the new entity
	$task = $up_datastore->entity( $taskKey, [
		'last_name'  => $_POST['last_name'],
		'first_name' => $_POST['first_name'],
		'street'     => $_POST['street'],
		'street_no'  => $_POST['street_no'],
		'zip'        => $_POST['zip'],
		'city'       => $_POST['city'],
		'email'      => $_POST['email'],
		'created'    => ( new DateTime() )->getTimestamp()
	] );

    # Saves the entity
	$up_datastore->upsert( $task );
}

$down_datastore = new DatastoreClient( [
	'projectId' => $projectId
] );


$query = $down_datastore->query()->kind( $kind );


?>

<html lang="en">
<title>ClCo WebApp Team A</title>
<body>
<h1>A Form in the Cloud</h1>
<p>This is the Simple Webapplication for the Arbeitsauftrag AA2-a und Abgabe Präsentation</p>

<section>
    <h2>Existing entries:</h2>
    <ul>
		<?php
		foreach ( $down_datastore->runQuery( $down_datastore->query()->kind( $kind ) ) as $entity ) {
			echo '<li><ul>';
			echo '<li><strong>First name: </strong>' . $entity["first_name"] . '</li>';
			echo '<li><strong>Last name: </strong>' . $entity["last_name"] . '</li>';
			echo '<li><strong>Street: </strong>' . $entity["street"] . '</li>';
			echo '<li><strong>Street No: </strong>' . $entity["street_no"] . '</li>';
			echo '<li><strong>ZIP: </strong>' . $entity["zip"] . '</li>';
			echo '<li><strong>City: </strong>' . $entity["city"] . '</li>';
			echo '<li><strong>Email: </strong>' . $entity["email"] . '</li>';
			echo '<li><strong>Created: </strong>' . $entity["created"] . '</li>';


			echo '</ul></li>';
		}
		?>
    </ul>
</section>

<section>
    <h2>Add your own address:</h2>
    <form method="POST">

        <label for="first_name">First name</label>
        <input id="first_name" name="first_name" type="text"/>

        <label for="last_name">Last name</label>
        <input id="last_name" name="last_name" type="text"/>

        <label for="street">Street</label>
        <input id="street" name="street" type="text"/>

        <label for="street_no">Street no.</label>
        <input id="street_no" name="street_no" type="text"/>

        <label for="zip">ZIP</label>
        <input id="zip" name="zip" type="text"/>

        <label for="city">City</label>
        <input id="city" name="city" type="text"/>

        <label for="email">E-Mail</label>
        <input id="email" name="email" type="email"/>

        <label for="key">Key</label>
        <input id="key" name="key" type="password"/>

        <input type="submit" value="Submit address"/>

    </form>
</section>
</body>
</html>





