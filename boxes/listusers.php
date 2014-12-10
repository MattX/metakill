<?php
function listusersbox($db, $k, $make_adm = false) {
?>
<script language="javascript">
function set_admin_id(id, admin) {
	document.getElementById("idField").value=id;
	document.getElementById("adminField").value=admin;
	document.getElementById("setAdminForm").action=document.URL;
	document.getElementById("setAdminForm").submit();
}
</script>
<div style="display:none;">
<form action="." method="POST" id="setAdminForm">
<input type="hidden" name="action" value="setAdmin">
<input type="hidden" name="id" id="idField" value="0">
<input type="hidden" name="admin" id="adminField" value="1">
</form>
</div>
<ul class="list-group"><?php
$usrs = list_user_killer($db, $k);
foreach($usrs as $u) {
?>
<li class="list-group-item"><?php if($u['admin']) { echo('<span class="label label-info">admin</span> '); } echo($u['name']); ?> : <?php echo(count_usr_kills($db, $k, $u['id'])); ?>
    <?php if($make_adm) { 
		if($u['admin']) { ?><a href="#" class="pull-right" onClick="set_admin_id(<?php echo($u['id']) ?>, 0)">
					<button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Retirer le statut d'administrateur</button></a>
    <?php       } else { ?><a href="#" class="pull-right" onClick="set_admin_id(<?php echo($u['id']) ?>, 1)">
					<button class="btn btn-success btn-xs"><span class="glyphicon glyphicon-star"></span> Rendre administrateur</button></a>
    <?php } } ?>
</li>
<?php
}
?></ul>
<?php
}
?>
