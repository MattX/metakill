<?php
make_title("Liste des killers");
?>

<div class="list-group">
<?php
$pages = array(0=>"killer_details", 1=>"write_kills", 2=>"my_kills", 3=>"my_kills");

foreach(list_killers($db, $_SESSION['id']) as $k) {
?>
<span class="list-group-item">
<h4 class="list-group-item-heading"><a href="index.php?page=<?php echo($pages[$k['phase']]); ?>&id=<?php echo($k['killer']); ?>"><?php echo($k['name']); ?></a>
<span class="pull-right btn-group">
        <?php if(access($db, $_SESSION['id'], $k['killer'], true)) { ?><a href="index.php?page=killer_admin&id=<?php echo($k['killer']); ?>">
				<button class="btn btn-default btn-sm">Modifier</button></a><?php } ?>
	<a href="index.php?page=killer_details&id=<?php echo($k['killer']); ?>"><button type="submit" class="btn btn-default btn-sm">Détails</button></a>
</span> <small><span class="label label-default"><?php echo($phases_text[$k['phase']]); ?></span></small></h4>
<p class="list-group-item-text"><?php echo($k['desc']); ?></p>
</span>
<?php
}
?>
<a class="list-group-item active" href="index.php?page=add_killer"><span class="glyphicon glyphicon-plus"></span> Créer un killer</a>
</div>

