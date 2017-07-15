<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>LinkeLeak - DB</title>
<link rel="stylesheet" type="text/css" href="table.css">
</head>
<body>

<form action="" method="post" name="search">
<input type="text" name="email" placeholder="Email (mongodb $regex)" required />
<input name="submit" type="submit" value="Search" /></form>


<?php

$filter = [ 'email' => '' ];

if (isset($_POST['email']) )
{
	$mail = preg_replace( "/[^a-zA-Z0-9\^\$\.]/", "", $_POST['email'] );
        //$mail = strip_tags($_REQUEST['email']);
        //echo "MAIL: $mail\nPOST:".$_POST['email']."\n";
	if ( $mail == $_REQUEST['email'] ) {
                $filter = ['email' => array('$regex' => $mail )];
	} else {
		echo "<p>WARNING: bad chars stripped out!</p><p><b>a-z A-Z 0-9 ^ $ .</b> allowed!</p>";
	}
}

if (isset($_POST['skip']) ) {
	$skip = preg_replace( "/[^0-9]/", "", $_POST['skip'] );
	$skip = intval($skip);
	$options = [
                        'limit' => 50,
                        'skip'  => $skip
	];
} else {
	$options = [
                        'limit' => 50,
                        'skip'  => 0
	];
}



// SKIPPED
if (isset($mail) && isset($skip)) {
	//if ( intval($skip) == 0 ) { unset($skip); }

	if ( intval($skip) >= 50 ) { 
		// BACK
		$bck = intval($skip) -50; 
		//echo "<p>SKIP back: ".$bck."</p>";
        	echo '<form style="float: left;" action="" method="post" name="back">';
        	echo '<input type="hidden" name="email" value="' . "$mail" . '" />';
        	echo '<input type="hidden" name="skip" value="' . "$bck" . '"/>'; 
        	echo '<input name="submit" type="submit" value="<<" /></form>';
	}
	// FORWARD
	$fwd = intval($skip) + 50;
	//echo "<p>SKIP fwd: ".$fwd."</p>";
	echo '<form style="float: left;" action="" method="post" name="forward">';
        echo '<input type="hidden" name="email" value="' . "$mail" . '" />';
        echo '<input type="hidden" name="skip" value="' . "$fwd" . '"/>';
        echo '<input name="submit" type="submit" value=">>" /></form>';


	// JUMP FORM
        echo '<form style="float: left;" action="" method="post" name="jump">';
        echo '<input type="text" name="skip" placeholder="jump to a number" required />';
	echo '<input type="hidden" name="email" value="' . "$mail" . '" />';
        echo '<input name="submit" type="submit" value="JUMP!" /></form>';


// FIRST SEARCH
} else if(isset($mail)) {
        echo '<form action="" method="post" name="skipped">';
        echo '<input type="hidden" name="email" value="' . "$mail" . '" />';
        echo '<input type="hidden" name="skip" value="50"/>';
        echo '<input name="submit" type="submit" value=">>" /></form>';
}

?>

<br/>
<table style="clear: left;">
<tr>
<th>EMAIL</th>
<th>PASSWORD</th>
<th>HASH (SHA1, unsalted)</th>
</tr>
<?php

// CONNECTION
try {

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017/linkeleak");

    // COUNT BUT MEMORY LIMIT CAUSE OF toArray in PHP
    //$count = new MongoDB\Driver\Query($filter, ['count' => 1]);
    //$numbers = $mng->executeQuery("linkeleak.merged", $count);
    //echo count($numbers->toArray());

    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $mng->executeQuery("linkeleak.merged", $query);

    foreach ($rows as $row) {
        echo "<tr><td>$row->email</td><td>$row->password</td><td>$row->hash</td></tr>";
	//var_dump($row);
    }

} catch (MongoDB\Driver\Exception\Exception $e) {

    $filename = basename(__FILE__);
    echo "The $filename script has experienced an error.\n"; 
    echo "It failed with the following exception:\n";
    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
}

?>
</table>
</body>
</html>
