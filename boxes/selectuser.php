<?php
function selectuser($checked) {
	global $db;
?>


<?php

$usrs = list_users($db);

foreach($usrs as $u) {
	$id = $u['id'];
	$name = $u['name'];
?>
<input type="checkbox" name="select_<?php echo($id) ?>" value="<?php echo($id) ?>" <?php if(in_array($id, $checked)) { echo("checked"); } ?>>
<label for="select_<?php echo($id) ?>"><?php echo($name) ?></label><br />
<?php
}

?>


<?php
}
?>
