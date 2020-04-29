<?php
session_start();
if(isset($_SESSION['name'])){
	$text = $_POST['text'];

	$c = fopen('home.html', 'a');
	fwrite($c, '<div class="msgln">('.date("g:i A").') <b>'.$_SESSION['name'].'</b> :: '.stripslashes(htmlspecialchars($text)).'<br></div>');
	fclose($c);
}
?>