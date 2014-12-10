<?php
if(!access($db, $_SESSION['id'], $_GET['id'])) {
	die();
}

$k = get_killer($db, $_GET['id']);
make_title("Killer : ".$k['name']);
?>

<table><tr><td>Nom :</td><td><?php echo($k['name']); ?></td></tr>
       <tr><td>Description :</td><td><?php echo($k['desc']); ?></td></tr>
	   <tr><td>Phase :</td><td><?php echo($phases_text[$k['phase']]); ?></td></tr>
</table>

<h4>Participants (nombre de kills remplis)</h4>
<?php include('boxes/listusers.php');
      listusersbox($db, $k['id'], false); ?>

<?php if($k['phase'] >= 2) { ?>
<h4>Tableau d'avancement</h4>
<?php include('boxes/advtable.php');
      advtable($db, $k['id'], $k['phase']==3); ?>
<?php } ?>
