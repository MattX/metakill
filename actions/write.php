<?php
if(!access($db, $_SESSION['id'], $_GET['id'])) {
	die();
}

make_title('Liste des kills');

$rep = get_usr_kills($db, $_GET['id'], $_SESSION['id']);
$kills = $rep[0];
$users = $rep[1];

var_dump($users); echo('<br>');
var_dump($kills); echo('<br>');
?>
<form action="index.php?page=write_kills&id=<?php echo $_GET['id'] ?>" method="POST">
<table>
<?php
foreach($kills as $id => $desc) {
	if($id != $_SESSION['id']) {
?>
<tr><td><?php echo($users[$id]) ?></td><td><input type="text" size="100" name="kill_<?php echo($id) ?>" id="kill_<?php echo($id) ?>" value="<?php echo($desc) ?>"></td></tr>
<?php
	}
}
?>
</table>
<input type="hidden" name="action" value="write">
<input type="submit" value="Enregistrer">
</form>
