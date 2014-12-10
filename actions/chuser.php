<form style="display:inline;" method="POST" action="index.php">
<table><tr><td><label for="name">Nom</label></td><td><input type="text" name="name" id="name" value="<?php echo($u['name']); ?>"></td></tr>
       <tr><td><label for="email">e-mail</label></td><td><input type="text" name="email" id="email" value="<?php echo($u['email']); ?>"></td></tr></table>
<input type="hidden" name="action" value="chuser">
<input type="submit" value="Enregistrer">
</form>

<p><a href="index.php?page=change_password">Changer de mot de passe</a></p>
