<?php
if(!access($db, $_SESSION['id'], $_GET['id'], true)) {
        die();
}

$k = get_killer($db, $_GET['id']);
make_title("Modifier un killer : ".$k['name']);
?>

<script language="javascript">
function nextphase() {
	if(confirm("Attention, le passage à la phase suivante est irréversible ! Continuer ?")) {
		document.getElementById("nextphaseform").submit();
	}
}
function del() {
	if(confirm("Attention, la suppression du killer est irréversible ! Continuer ?")) {
		document.getElementById("deleteform").submit();
	}
}
</script>

<form style="display:inline;" method="POST" action="index.php">
<table><tr><td><label for="name">Nom</label></td><td><input type="text" name="name" id="name" value="<?php echo($k['name']); ?>"></td></tr>
       <tr><td><label for="desc">Description</label></td><td><textarea name="desc" id="desc"><?php echo($k['desc']); ?></textarea></td></tr>
       <tr><td>Phase</td><td><?php echo($phases_text[$k['phase']].' : '); if($k['phase'] != 3) { ?><button type="button" onClick="nextphase();">Phase suivante</button><?php } ?></td></tr>
           </table>

<input type="hidden" name="id" value="<?php echo($k['id']); ?>">
<input type="hidden" name="action" value="chkiller">


<h4>Sélection des participants</h4>
<?php if($k['phase'] == 0) {
	include('boxes/selectuser.php');
	$usrs = list_user_killer($db, $k['id']);
	$ids = array_map(function ($item) { return $item[0]; }, $usrs);
	selectuser($ids);
	echo('<br>');
}
?>

<input type="submit" value="Enregistrer les modifications">
</form>

<h4>Participants</h4>
<?php include('boxes/listusers.php'); listusersbox($db, $k['id'], true); ?>


<?php if($k['phase'] >= 2) { ?>
<h4>Tableau d'avancement</h4>
<?php include('boxes/advtable.php');
      advtable($db, $k['id'], $k['phase']==3, $k['phase']==2); ?>
<?php } ?>

<?php if($k['phase'] != 3) { ?>
	<div style="display:none;">
	<form method="POST" action="index.php?page=admin_killer&id=<?php echo($k['id']); ?>" id="nextphaseform">
	<input type="hidden" name="action" value="phase<?php echo($k['phase'] + 1); ?>">
	</form>
	</div>
<?php } ?>

<div style="display:none;"><form action="index.php" method="POST" id="deleteform">
<input type="hidden" name="id" value="<?php echo($k['id']); ?>">
<input type="hidden" name="action" value="delete">
</form></div>

<a href="#" onClick="del();" style="dellink"><img src="data/rem.svg" class="txticon">Supprimer le killer</a>
