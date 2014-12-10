<?php
make_title("Liste des killers");
?>

<table width="100%">
<tr class="list"><td width="70%">Nom et description</td><td>Etat</td><td></td><td></td></tr>
<?php
$pages = array(0=>"killer_details", 1=>"write_kills", 2=>"my_kills", 3=>"my_kills");

foreach(list_killers($db, $_SESSION['id']) as $k) {
?>
<tr class="list"><td><p><a href="index.php?page=<?php echo($pages[$k['phase']]); ?>&id=<?php echo($k['killer']); ?>">
           <?php echo($k['name']); ?></a></p><p><?php echo($k['desc']); ?></p></td>
<td><?php echo($phases_text[$k['phase']]); ?></td>
<td><a href="index.php?page=killer_details&id=<?php echo($k['killer']); ?>">Détails</a></td>
<td><?php if(access($db, $_SESSION['id'], $k['killer'], true)) { ?><a href="index.php?page=killer_admin&id=<?php echo($k['killer']); ?>">Modifier</a><?php } ?></td></tr>
<?php
}
?>
</table>
<a href="index.php?page=add_killer"><img src="data/add.svg" class="txticon">Créer un killer</a>