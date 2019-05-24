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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<title>ClCo WebApp Team A</title>
<body>
<h1 style="padding:1em;">A Form in the Cloud</h1>
<p style="padding:1em;">This is the Simple Webapplication for the Arbeitsauftrag AA2-a und Abgabe Präsentation</p>

<div class="container">

<div class="row">

<div class="col">

<section style="padding:1em;">
    <h2 style="padding:1em;">Add your own address:</h2>
    <form method="POST">

		<div class="form-group" style="padding:1em;">
        <label for="first_name">First name</label>
        <input class="form-control" id="first_name" name="first_name" type="text"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <label for="last_name">Last name</label>
        <input class="form-control" id="last_name" name="last_name" type="text"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <label for="street">Street</label>
        <input class="form-control" id="street" name="street" type="text"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <label for="street_no">Street no.</label>
        <input class="form-control" id="street_no" name="street_no" type="text"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <label for="zip">ZIP</label>
        <input class="form-control" id="zip" name="zip" type="text"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <label for="city">City</label>
        <input class="form-control" id="city" name="city" type="text"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <label for="email">E-Mail</label>
        <input class="form-control" id="email" name="email" type="email"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <label for="key">Key</label>
        <input class="form-control" id="key" name="key" type="password"/>
		</div>

		<div class="form-group" style="padding:1em;">
        <button type="submit" value="Submit address" class="btn btn-primary">Submit</button>
		</div>

    </form>
</section>

</div>

<div class="col">
<section>
    <h2 style="padding:1em;">Existing entries:</h2>
    <ul class="list-group">
		<?php
		foreach ( $down_datastore->runQuery( $down_datastore->query()->kind( $kind ) ) as $entity ) {
			echo '<li><ul>';
			echo '<li class="list-group-item"><strong>First name: </strong>' . $entity["first_name"] . '</li>';
			echo '<li class="list-group-item"><strong>Last name: </strong>' . $entity["last_name"] . '</li>';
			echo '<li class="list-group-item"><strong>Street: </strong>' . $entity["street"] . '</li>';
			echo '<li class="list-group-item"><strong>Street No: </strong>' . $entity["street_no"] . '</li>';
			echo '<li class="list-group-item"><strong>ZIP: </strong>' . $entity["zip"] . '</li>';
			echo '<li class="list-group-item"><strong>City: </strong>' . $entity["city"] . '</li>';
			echo '<li class="list-group-item"><strong>Email: </strong>' . $entity["email"] . '</li>';
			echo '<li class="list-group-item"><strong>Created: </strong>' . $entity["created"] . '</li>';


			echo '</ul></li>';
		}
		?>
    </ul>
</section>
</div>

</div>

</div>



</body>
</html>





