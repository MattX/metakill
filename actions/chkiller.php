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

<form class="form-horizontal" method="POST" action="index.php">
<div class="form-group"><label for="name" class="control-label col-xs-2">Nom</label>
			<div class="col-xs-10"><input class="form-control" type="text" name="name" id="name" value="<?php echo($k['name']); ?>"></div></div>
<div class="form-group"><label for="desc" class="control-label col-xs-2">Description</label>
                        <div class="col-xs-10"><textarea class="form-control" name="desc" id="desc"><?php echo($k['desc']); ?></textarea></div></div>
<div class="form-group"><label class="control-label col-xs-2">Phase</label>
			<span class="col-xs-10"><?php echo($phases_text[$k['phase']].' '); if($k['phase'] != 3) { ?>
			<button type="button" class="btn btn-default" onClick="nextphase();"><span class="glyphicon glyphicon-step-forward"></span> Phase suivante</button><?php } ?></span></div>

<input type="hidden" name="id" value="<?php echo($k['id']); ?>">
<input type="hidden" name="action" value="chkiller">


<div class="panel panel-default">
  <div class="panel-heading">Sélection des participants</div>
  <div class="panel-body">
<?php if($k['phase'] == 0) {
	include('boxes/selectuser.php');
	$usrs = list_user_killer($db, $k['id']);
	$ids = array_map(function ($item) { return $item[0]; }, $usrs);
	selectuser($ids);
	echo('<br>');
}
?>
</div>
</div>

<input type="submit" value="Enregistrer les modifications">
</form>

<div class="panel panel-default">
  <div class="panel-heading">Participants (nombre de kills remplis)</div>
  <div class="panel-body">
    <?php include('boxes/listusers.php');
      listusersbox($db, $k['id'], true); ?>
  </div>
</div>



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

<a class="btn btn-danger btn-lg col-xs-12" href="#" onClick="del();" style="dellink"><span class="glyphicon glyphicon-remove"></span> Supprimer le killer</a>
