<?php

$pass_warn = false;

if(isset($_POST['user']) && isset($_POST['passwd'])) {
	$res = check_login($db, $_POST['user'], $_POST['passwd']);
	if($res['success'] == true) {
		$_SESSION['id'] = $res['id'];
	} else {
		$pass_warn = true;
	}
}

if(!isset($_SESSION['id'])) {
	make_title("Killers du ski");
?>


<?php if($pass_warn) { print('<div class="alert alert-danger">Login incorrect</div>'); } ?>

<form class="form-horizontal" method="POST" action="index.php">
<div class="form-group">
<div class="col-xs-5"><input name="user" id="user" type="email" class="form-control" placeholder="Adresse email"></div>
<div class="col-xs-5"><input name="passwd" id="password" type="password" class="form-control" placeholder="Mot de passe"></div>
<div class="col-xs-2"><button type="submit" class="btn btn-primary">Connexion</button></div>
</div>
</form>

<?php
	$skip = true;
} else {
	$skip = false;
}

?>
