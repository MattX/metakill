<?php
if(!access($db, $_SESSION['id'], $_GET['id'])) {
	die();
}

$k = get_killer($db, $_GET['id']);
make_title("Killer : ".$k['name']);
?>

<table class="table"><tr class="info"><td>Nom</td><td><?php echo($k['name']); ?></td></tr>
       <tr><td>Description</td><td><?php echo($k['desc']); ?></td></tr>
	   <tr><td>Phase</td><td><?php echo($phases_text[$k['phase']]); ?></td></tr>
</table>

<div class="panel panel-default">
  <div class="panel-heading">Participants (nombre de kills remplis)</div>
  <div class="panel-body">
    <?php include('boxes/listusers.php');
      listusersbox($db, $k['id'], false); ?>
  </div>
</div>

<?php if($k['phase'] >= 2) { ?>
<h4>Tableau d'avancement</h4>
<?php include('boxes/advtable.php');
      advtable($db, $k['id'], $k['phase']==3); ?>
<?php } ?>
