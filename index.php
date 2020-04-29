<?php
session_start();

function loginForm(){
	echo '
		<div class="form-group">
			<div id="loginForm">
				<form action="index.php" method="POST">
					<h1>Simple Chat</h1>
					<label for="name">Please Enter Your Name</label>
					<input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name" />
					<input type="submit" class="btn btn-default" name="enter" id="enter" value="OK">
				</form>
			</div>
		</div>
	';
}

if(isset($_POST['enter'])){
	if($_POST['name'] != ""){
		$_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
		$c = fopen("home.html", "a");
		fwrite($c, "<div class='msgln'><i>User ".$_SESSION['name']." has joined the chat.</i><br></div>");
		fclose($c);
		header("location:index.php");
	}else{
		echo "<span class='error'>Please Enter Your Name</span>";
	}
}

// logout functionality
if(isset($_GET['logout'])){
	$c = fopen('home.html','a');
	fwrite($c, "<div class='msgln'><i>User ".$_SESSION['name']." has left the chat.</i><br></div>");
	fclose($c);
	session_destroy();
	header("location:index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Live Chat Using PHP and JS</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
<?php
	if(!isset($_SESSION['name'])){
		loginForm();
	}else{
?>
<div id="wrapper">
	<div id="menu">
	<h1>Simple Live Chat!</h1><hr/>
		<p class="welcome"><b>HI - <?php echo $_SESSION['name']; ?></b></p>
		<p class="logout"><a id="exit" href="#" class="btn btn-default">Exit Live Chat</a></p>
	<div style="clear: both"></div>
	</div>
	<div id="chatbox">
		<!-- main-chatbox -->
		<?php
			if(file_exists("home.html") && filesize('home.html')>0){
				$handle = fopen('home.html','r');
				$content = fread($handle, filesize('home.html'));
				fclose($handle);

				echo $content;
			}
		?>
	</div>
<form name="message" action="">
	<input name="usermsg" class="form-control" type="text" id="usermsg" placeholder="Create A Message" />
	<input name="submitmsg" class="btn btn-default" type="submit" id="submitmsg" value="Send" />
</form>
</div>
<script>
	$(document).ready(function(){
		$("#exit").click(function(){
			var exit = confirm("Are You Sure, TO Quit?");
			if(exit == true){
				window.location = 'index.php?logout=true';
			}
		});
	});
	
	$("#submitmsg").click(function(){
		var usermsg = $("#usermsg").val();
		$.post("post.php",{text: usermsg});
		$("#usermsg").val("");
		loadLog;
		return false;
	});

	function loadLog(){
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		$.ajax({
			url: "home.html",
			cache: false,
			success: function(html){
				$("#chatbox").html(html);
				var newscrollHeight = $("#chatbox").attr('scrollHeight') - 20;
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
				}
			},
		});
	}
	setInterval(loadLog, 2500);
</script>
<?php
	}
?>
</body>
</html>