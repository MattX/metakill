<form style="display:inline;" method="POST" action="index.php">
<table><tr><td><label for="name">Nom</label></td><td><input type="text" name="name" id="name"></td></tr>
       <tr><td><label for="email">e-mail</label></td><td><input type="text" name="email" id="email"></td></tr>
	   <tr><td><label for="pw">Mot de passe (provisoire)</label></td><td><input type="text" name="pw" id="pw"></td></tr>
	   </table>
	   
<input type="hidden" name="action" value="adduser">
<input type="submit" value="Enregistrer">
</form>
