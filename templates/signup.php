<!DOCTYPE HTML>
<html>
	<head>

	</head>
	<body>
	<header>
	<div class="err">
			<?php
			if( isset( $_SESSION['err'] ) ) {
			var_dump ($_SESSION['err']);
			unset( $_SESSION['err'] );
			}
			?></div>
		</header>
	<h1>Registrations</h1>
		<div class = "signup-form">
			<form action = "signup" method="POST">
				<ul class="list">
					<li>Username:<input class="inp" type="text" name="username" value="<?php if(isset($_POST['username'])){echo $_POST['username'];}?>"></li>
					<li>Password: <input class="inp" type="password" name ="password" value="password"/></li>
					<li>Roll: <input class="inp" type="text" name ="roll" value="<?php if(isset($_POST['roll'])){echo $_POST['roll'];}?>"/></li>
					<li>Name: <input class="inp" type="text" name ="name" value="<?php if(isset($_POST['name'])){echo $_POST['name'];}?>"/></li>
					<li>Email: <input class="inp" type="email" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>"/></li>
					<li>Mobile: <input class="inp" type="number" name="number" value="<?php if(isset($_POST['number'])){echo $_POST['number'];}?>"/></li>
					<li><input type="submit" value="submit"/></li>
				</ul>
			</form>
		</div>
	</body>
</html>
