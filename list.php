<table>

<?php

foreach(list_killers($db, $_SESSION['id']) as $k) {
?>
<tr><td><p><?php echo($k['name']); ?></p><p><?php echo($k['desc']); ?></p></td>
<td>État : <?php echo($phases_text[$k['phase']]); ?></td>
<td><a href="index.php?page=killer_details&id=<?php echo($k['killer']); ?>">Voir</a></td>
<td><?php if(access($db, $_SESSION['id'], $k['killer'], true)) { ?><a href="index.php?page=killer_admin&id=<?php echo($k['killer']); ?>">Modifier</a><?php } ?></td></tr>
<?php
}

?>

</table>

<a href="index.php?page=addkiller">Créer un killer</a>

