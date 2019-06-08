<?php
$errors=array();
$conn;

// function __construct($db){
// 	$conn = $db;
// }


function isSession(){
	if(isset($_SESSION['user'])){
		return true;
	}else{
		return false;
	}
}


function displayError(){
	global $errors;

	if(count($errors) > 0){
		echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'> ";
		foreach($errors as $error){
			echo $error."<br>";
		}
		echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		<span aria-hidden='true'>&times;</span>
		</button>
		</div>";
	}
}

function isSysAdmin(){
	if(isset($_SESSION['user']) && $_SESSION['access'] == 2){
		return true;
	}else{
		return false;
	}
}

function isCSAdmin(){
	if(isset($_SESSION['user']) && $_SESSION['access'] == 1){
		return true;
	}else{
		return false;
	}
}

function timeElapsed($datetime, $full = false) {
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}


?>