<?php
error_reporting(E_ALL);
ini_set( 'display_errors', 1 );

session_start();
if(isset($_GET['disconnect'])) {
	session_destroy();
	unset($_SESSION['id']);
}

include 'misc.php';
$db = connect();
$skip = false;
?>

<!DOCTYPE html>
<html>
<head>
<title>Killers du ski</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
<script language="javascript" src="js/jquery-1.11.1.min.js"></script>
<script language="javascript" src="js/bootstrap.min.js"></script>
</head>
<body>
<div id="container">

<?php

foreach($_POST as $key => $value) {
	$_POST[$key] = htmlspecialchars($value);
}
foreach($_GET as $key => $value) {
	$_GET[$key] = htmlspecialchars($value);
}

if(!isset($_SESSION['id'])) {
	include 'login.php';
}

if(!$skip) {
	$u = get_user($db, $_SESSION['id']);
	act($db, $_GET, $_POST, $_SESSION['id'], $u);
	if(isset($err)) {
?>
<p class="warn"><?php echo($err); ?></p>
<?php
	}
?>
<table width="100%"><tr><td><a href="index.php"><img src="data/home.svg" class="txticon">ACCUEIL</a></td>
<td style="text-align: right;"><a href="index.php?page=change_user"><img src="data/person.svg" class="txticon"><span class="uname"><?php echo($u['name']); ?></span></a>
| <a href="index.php?disconnect=1"><img src="data/logout.svg" class="txticon">Se déconnecter</a>
| <a href="index.php?page=add_user"><img src="data/add.svg" class="txticon">Ajouter un utilisateur</a></td></table>
<?php
	if(!isset($_GET['page'])) {
		include 'list.php';
	} else {
	switch($_GET['page']) {
	case 'change_password': # OK
		include 'actions/chpw.php';
		break;
	case 'change_user': # OK
		include 'actions/chuser.php';
		break;
	case 'add_user': # OK
		include 'actions/adduser.php';
		break;
	case 'add_killer': # OK
		include 'actions/addkiller.php';
		break;
	case 'killer_admin': # Partiel
		include 'actions/chkiller.php';
		break;
	case 'killer_details': # Partiel
		include 'actions/killer.php';
		break;
	case 'write_kills': # OK, TODO: vérifier phase
		include 'actions/write.php';
		break;
	case 'my_kills': # OK
		include 'actions/mykills.php';
		break;
	case 'main': default:
		include 'list.php';
		break;
	}
	}
}
?>

</div>
</body>
</html>
