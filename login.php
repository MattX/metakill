<?php
make_title("Killers du ski");

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
?>


<?php if($pass_warn) { print('<p class="warn">Login incorrect</p>'); } ?>

<form style="display:inline;" method="POST" action="index.php">
<table><tr><td class="sep">
<label for="user">Adresse email : </label><input type="text" name="user" id="user" <?php if(isset($_POST['user'])) echo('value="'.$_POST['user'].'"'); ?> autofocus></td><td class="sep">
<label for="passwd">Code ultra-secret : </label><input type="password" name="passwd" id="passwd"></td><td class="sep">
                                  <input type="submit" value="Connexion"></td></tr></table></form>

<?php
	$skip = true;
} else {
	$skip = false;
}

?>
