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
<ul><?php
$usrs = list_user_killer($db, $k);
foreach($usrs as $u) {
?>
<li><?php if($u['admin']) { echo("[admin] "); } echo($u['name']); ?> : <?php echo(count_usr_kills($db, $k, $u['id'])); ?>
    <?php if($make_adm) { 
		if($u['admin']) { ?><a href="#" class="adminlink" onClick="set_admin_id(<?php echo($u['id']) ?>, 0)">[retirer le statut d'administrateur]</a>
    <?php       } else { ?><a href="#" class="adminlink" onClick="set_admin_id(<?php echo($u['id']) ?>, 1)">[rendre administrateur]</a>
    <?php } } ?>
</li>
<?php
}
?></ul>
<?php
}
?>
