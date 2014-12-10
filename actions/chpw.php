<script language="javascript">
function chksend() {
	if(document.getElementById("newpw").value != document.getElementById("check").value) {
		document.getElementById("diffwarn").style.display = "block";
	} else {
		document.getElementById("diffwarn").style.display = "none";
		document.getElementById("pwform").submit();
	}
}
</script>

<p class="warn" style="display:none;" id="diffwarn">Les mots de passe ne correspondent pas !</p>

<form style="display:inline;" method="POST" action="index.php" id="pwform">
<table><tr><td><label for="name">Mot de passe actuel :</label></td><td><input type="password" name="curpw" id="curpw"></td></tr>
       <tr><td><label for="email">Nouveau mot de passe :</label></td><td><input type="password" name="newpw" id="newpw"></td></tr>
	   <tr><td><label for="email">Confirmer le mot de passe :</label></td><td><input type="password" id="check"></td></tr>
	   </table>
<input type="hidden" name="action" value="chpw">
<input type="button" value="Enregistrer" onclick="chksend()">
</form>
