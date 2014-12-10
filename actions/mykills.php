<?php 
$k = get_killer($db, $_GET['id']);

make_title('Kills sur le killer '.$k['name']);

$written = usr_kills($db, $k['id'], $_SESSION['id'], true);
$todo = usr_kills($db, $k['id'], $_SESSION['id'], false);

$usr_sorted = make_names($db, $k['id']);

function name($id) {
	global $usr_sorted;
	return $usr_sorted[$id]['name'];
}
?>
<h2>Kills à faire</h2>
<ul>
<?php
foreach($todo as $kill) {
?>
<li><b><?php echo(name($kill['for'])); ?> :</b> <?php echo($kill['desc']); ?> 
(écrit par <?php echo(name($kill['writer'])); ?>)<?php if($kill['done']) { echo('<img src="data/check.svg" class="txticon">'); } ?></li>
<?php
}
?>
</ul>

<h2>Kills écrits</h2>
<ul>
<?php
foreach($written as $kill) {
?>
<li><b><?php echo(name($kill['for'])); ?> :</b> <?php echo($kill['desc']); ?> 
(assigné à <?php echo(name($kill['assigned_to'])); ?>)<?php if($kill['done']) { echo('<img src="data/check.svg" class="txticon">'); } ?></li>
<?php
}
?>
</ul>
