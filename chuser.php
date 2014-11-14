<form style="display:inline;" method="POST" action="index.php">
<table><tr><label for="name">Nom</label><input type="text" name="name" id="name" value="<?php echo($u['name']); ?>"></tr>
       <tr><label for="email">e-mail</label><input type="text" name="email" id="email" value="<?php echo($u['email']); ?>"></tr></table>
<input type="hidden" name="action" value="chuser">
<input type="submit" value="Enregistrer">
</form>

