<?php make_title("Créer un killer"); ?>

<!-- <p class="info">Vous pourrez ajouter des utilisateurs après avoir validé cette étape</p> -->

<form style="display:inline;" method="POST" action="index.php">
<table><tr><td><label for="name">Nom</label></td><td><input type="text" name="name" id="name"></td></tr>
       <tr><td><label for="desc">Description</label></td><td><textarea name="desc" id="desc"></textarea></td></tr>
	   <tr><td>Participants</td><td><?php include('boxes/selectuser.php'); selectuser(array()); ?></td></tr>
	   </table>
	   
<input type="hidden" name="action" value="addkiller">
<input type="submit" value="Enregistrer">
</form>
