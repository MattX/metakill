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

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Afficher la barre de navigation</span>
      </button>
      <a class="navbar-brand" href="#">Metakill</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Accueil</a></li>
      </ul>
<?php
if(isset($_SESSION['id'])) {
?>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span id="nav_user_name"></span> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?page=change_user"><span class="glyphicon glyphicon-user"></span> Profil</a></li>
            <li><a href="index.php?disconnect=1"><span class="glyphicon glyphicon-log-out"></span> Se déconnecter</a></li>
            <li class="divider"></li>
            <li><a href="index.php?page=add_user"><span class="glyphicon glyphicon-plus"></span> Ajouter un utilisateur</a></li>
          </ul>
        </li>
      </ul>
<?php
}
?>
    </div>
  </div>
</nav>

<div class="container">
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
<div class="alert alert-warning"><?php echo($err); ?></div>
<?php
	}
?>
<script language="javascript">
$(function() {
	$('#nav_user_name').text('<?php echo($u['name']); ?>');
});
</script>
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
