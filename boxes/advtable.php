<?php
$usr_sorted = make_names($db, $k['id']);

function name($id) {
	global $usr_sorted;
	return $usr_sorted[$id]['name'];
}

function advtable($db, $killer_id, $all = false, $can_write = false) {
?>
<script language="javascript">
function doKill(to, writer, killer) {
	document.getElementById("forField").value=to;
	document.getElementById("writerField").value=writer;
	document.getElementById("killerField").value=killer;
	document.getElementById("doKillForm").action=document.URL;
	document.getElementById("doKillForm").submit();
}
</script>
<div style="display:none;">
<form action="." method="POST" id="doKillForm">
<input type="hidden" name="action" value="markKill">
<input type="hidden" name="for" id="forField" value="0">
<input type="hidden" name="writer" id="writerField" value="0">
<input type="hidden" name="killer" id="killerField" value="0">
</form>
</div>

<?php
	$note_id = 1;
	$notes = array();

	echo("<table width=\"100%\">\n");
	$kills = all_kills($db, $killer_id);
	$usrs = list_user_killer($db, $killer_id);

	echo('<tr><td>v--- Tue &lt;---</td>');
	foreach($usrs as $u_head)
		echo('<td>'.$u_head['name'].'</td>');
	echo("</tr>\n");

	foreach($usrs as $u_for) {
		echo("<tr>");
		echo('<td>'.$u_for['name'].'</td>');
		foreach($usrs as $u_assign) {
			if($u_assign[0] == $u_for[0]) { echo("<td></td>"); continue; }
				
?>
			<td><?php $k = $kills[$u_for[0]][$u_assign[0]];
			          if($k['done'] == 1) {
						echo('<img src="data/check.svg" class="txticon">Fait');
					  } elseif($can_write) {
						echo('<a href="#" style="font-size:8pt;" onClick="doKill('.$k['for'].','.$k['writer'].','.$k['killer'].')">Marquer comme fait</a>');
					  }
					  if($k['done'] == 1 or $all) {
					    $notes[$note_id] = $k['desc'].' (écrit par '.name($k['writer']).')';
						echo('<a class="noteref" href="#note_'.$note_id.'">'.$note_id.'</a>');
						$note_id++;
					  }?></td>
<?php
		}
		echo("</tr>\n");
	}
	echo("</table>\n<p>");
	
	foreach($notes as $i => $note) {
		echo($i.' : <a id="note_'.$i.'">'.$note.'</a><br>');
	}
	echo("</p>\n");
}
?>
