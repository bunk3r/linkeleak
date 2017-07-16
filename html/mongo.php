<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>LinkeLeak - DB</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="w3.css">
</head>
<body>

<!-- MAIN PAGE -->
<div class="w3-row">
	<!-- HEADER -->
	<div class="w3-container w3-blue w3-padding-16">
    		<h2>LinkeLeak</h2>
    		<div class="w3-third">
      			<!-- SEARCH FORM -->
				<form class="w3-container" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="search">
					<div class="w3-row">
						<div class="w3-col" style="width:70%">
							<input class="w3-input" type="text" name="email" placeholder="search email" required />
        					</div>
						<div class="w3-col" style="width:30%">
							<input class="w3-button w3-grey" name="submit" type="submit" value="Search" />
						</div>
					</div>
  					<div class="w3-row">
						<label class="w3-text-white">(mongodb $regex)</label>
					</div>
				</form>
      		</div>

<?php

$filter = [ 'email' => '' ];

if (isset($_POST['email']) )
{
	$mail = preg_replace( "/[^a-zA-Z0-9\^\$\.@]/", "", $_POST['email'] );
        //$mail = strip_tags($_REQUEST['email']);
        //echo "MAIL: $mail\nPOST:".$_POST['email']."\n";
	if ( $mail == $_REQUEST['email'] ) {
                $filter = ['email' => array('$regex' => $mail )];
	} else {
		exit('</div><p class="w3-container w3-red">WARNING: bad chars stripped out!</p><p><b>a-z A-Z 0-9 ^ $ @ .</b> allowed!');
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
if (isset($mail)) { 
	echo '	<div class="w3-third">';
	echo '		<div class="w3-row">';
        echo '                  <div class="w3-half">'; 

	if ( isset($skip) ) { 
                $bck = intval($skip) -50; 
		if ( intval($skip) >= 50 ) { 
			// BACK
			//echo "<p>SKIP back: ".$bck."</p>";
                	echo '		<form class="w3-container w3-center" action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post" name="back">';
			echo '			<input type="hidden" name="email" value="' . "$mail" . '" />';
                	echo '			<input type="hidden" name="skip" value="' . "$bck" . '"/>';
        		echo '			<input class="w3-button w3-grey" name="submit" type="submit" value="<<" />';
			echo '		</form>';
		} else {
			echo '          <form class="w3-container w3-center" action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post" name="back">';
                	echo '                  <input type="hidden" name="email" value="' . "$mail" . '" />';
                	echo '                  <input type="hidden" name="skip" value=""/>';
                	echo '                  <input class="w3-button w3-grey w3-disabled" name="submit" type="submit" value="<<" disabled/>';
                	echo '          </form>';
		}
		echo '		</div>';
		echo '		<div class="w3-half">';	
		// FORWARD
		$fwd = intval($skip) + 50;
		//echo "<p>SKIP fwd: ".$fwd."</p>";
		echo '			<form class="w3-container w3-center" action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post" name="forward">';
        	echo '				<input type="hidden" name="email" value="' . "$mail" . '" />';
        	echo '				<input type="hidden" name="skip" value="' . "$fwd" . '"/>';
        	echo '				<input class="w3-button w3-grey" name="submit" type="submit" value=">>" />';
		echo '			</form>';
		echo '		</div>';
		echo '		</div>';
		echo '	</div>';
	} else {
        	echo '                          <form class="w3-container w3-center" action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post" name="back">';
        	echo '                                  <input type="hidden" name="email" value="' . "$mail" . '" />';
        	echo '                                  <input type="hidden" name="skip" value=""/>';
        	echo '                                  <input class="w3-button w3-grey w3-disabled" name="submit" type="submit" value="<<" disabled/>';
        	echo '                          </form>';
        	echo '                  </div>';
        	echo '                  <div class="w3-half">';
        	echo '                          <form class="w3-container w3-center" action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post" name="skipped">';
        	echo '                                  <input type="hidden" name="email" value="' . "$mail" . '" />';
        	echo '                                  <input type="hidden" name="skip" value="50"/>';
        	echo '                                  <input class="w3-button w3-grey" name="submit" type="submit" value=">>" />';
        	echo '                          </form>';
        	echo '                  </div>';
        	echo '          </div>';
        	echo '  </div>';
	}
	// JUMP FORM
	echo '	<div class="w3-third">';
	echo '		<!-- JUMP FORM -->';
	echo '		<form class="w3-container" action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post" name="jump">';
	echo '			<div class="w3-row">';
	echo '				<div class="w3-col" style="width:70%">';
	echo '					<input class="w3-input" type="text" name="skip" placeholder="jump to a number" required />';
	echo '					<input type="hidden" name="email" value="' . "$mail" . '" />';
        echo '				</div>';
	echo ' 				<div class="w3-col" style="width:30%">';
	echo '					<input class="w3-button w3-grey" name="submit" type="submit" value="JUMP!" />';
	echo '				</div>';
	echo '			</div>';
        echo '		</form>';
	echo '	</div>';
}

?>
	</div>
</div>

	<!-- TABLE -->
	<div class="w3-container w3-white w3-padding-16">

		<div class="w3-row w3-white w3-responsive">

			<table class="w3-table-all w3-hoverable w3-small">
				<tr class="w3-blue">
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
		</div>
</div>

</body>
</html>
