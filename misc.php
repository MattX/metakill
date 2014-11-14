<?php

function connect()
{
	$conn = new PDO('sqlite:ski.sqlite');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	return $conn;
}

function access($db, $user, $killer, $write)
{
	$query = $db->prepare('SELECT admin FROM kill_k_p WHERE user=? AND killer=?');
	$query->execute(array($user, $killer));
	
	$res = $query->fetch();
	if($res == false) {
		return false;
	}
	elseif($write == false){
		return true;
	}
	else {
		return ($res['admin'] == 1);
	}
}

function check_login($db, $email, $pass)
{
	$query = $db->prepare('SELECT id, passwd FROM kill_ppl WHERE email=?');
	$query->execute(array($email));
	$res = $query->fetch();

	$ret = array ( 'success' => false, 'id' => 0 );
		
	if($res == false) {
		return $ret;
	}
	elseif(password_verify($pass, $res['passwd'])) {
		$ret['success'] = true;
		$ret['id'] = $res['id'];
		return $ret;
	}
	else return $ret;
}

function set_pw($db, $user, $newpass)
{
	$query = $db->prepare('UPDATE kill_ppl SET passwd=? WHERE id=?');
	$query->execute(array(password_hash($newpass, PASSWORD_BCRYPT), $user));
}

function get_user($db, $id)
{
	$query = $db->prepare('SELECT id, name, email FROM kill_ppl WHERE id=?');
	$query->execute(array($id));
	return $query->fetch();
}

function set_user($db, $id, $user)
{
	$query = $db->prepare('UPDATE kill_ppl SET name=?, email=? WHERE id=?');
	$query->execute(array($user['name'], $user['email'], $id));
}

function list_killers($db, $user_id)
{
	$query = $db->prepare('SELECT name, desc, phase, killer FROM kill_k_p INNER JOIN kill_killers ON kill_k_p.killer = kill_killers.id WHERE kill_k_p.user = ?');
	$query->execute(array($user_id));
	return $query->fetchAll();
}

function add_killer($db, $user_id, $killer)
{
	$query = $db->prepare('INSERT INTO kill_killers(name, phase, email_text, desc) VALUES (?, 0, ?, ?)');
	$query->execute(array($killer['name'], $killer['emailtext'], $killer['desc']));
	$id = $db->lastInsertID();
	
	$query = $db->prepare('INSERT INTO kill_k_p(killer, user, admin) VALUES (?, ?, 1)');
	$query->execute(array($id, $user_id));
	
	return $id;
}

function change_killer($db, $killer_id, $killer_params)
{
	$query = $db->prepare('UPDATE kill_killers SET name=?, desc=?, email_text=?');
	$query->execute(array($killer_params['name'], $killer_params['desc'], $killer_params['email_text']));
}

/* TODO : phase management */
/* 0 : add/remove users
 * 0 -> 1 : send mail
 * 1 : fill in kills
 * 1 -> 2 : attribute, send kills
 * 2 : fill in what's done
 * 3 : ended */

$phases_text = array( 0 => "Sélection des participants", 1 => "Remplissage des kills", 2 => "Jeu en cours", 3 => "Terminé" );

function add_or_update_kill($db, $killer_id, $user_id, $target_id, $desc)
{
	$query = $db->prepare('REPLACE INTO kill_kills (writer, for, desc, killer) VALUES (?, ?, ?)');
	$query->execute($user_id, $target_id, $desc, $killer_id);
}

function killer_phase($db, $killer_id)
{
	$query = $db->prepare('SELECT phase FROM kill_killers WHERE id=?');
	$query->execute(array($killer_id));
	$f = $query->fetch();
	return $f['phase'];
}

function delete_killer($db, $killer_id, $clean = false)
{
	$query = $db->prepare('DELETE FROM kill_killers WHERE id=?');
	$query->execute(array($killer_id));
	
	if($clean) {
		$query = $db->prepare('DELETE FROM kill_k_p WHERE killer=?');
		$query->execute(array($killer_id));
		$query = $db->prepare('DELETE FROM kill_kills WHERE killer=?');
		$query->execute(array($killer_id));
	}
}

function add_user_killer($db, $killer_id, $user_id, $admin = false)
{
	$query = $db->prepare('INSERT INTO kill_k_p(killer, user, admin) VALUES (?, ?, ?)');
	$query->execute(array($killer_id, $user_id, $admin));
}

function set_admin_flag($db, $killer_id, $user_id, $admin)
{
	$query = $db->prepare('UPDATE kill_k_p SET admin=? WHERE killer=? AND user=?');
	$query->execute(array($admin, $killer_id, $user_id));
}

function remove_user_killer($db, $killer_id, $user_id)
{
	$query = $db->prepare('DELETE FROM kill_k_p WHERE killer=? AND user=?');
	$query->execute(array($killer_id, $user_id));
}

function act($db, $get, $post, $uid)
{
	if(!isset($post['action'])) {
		return;
	}
	switch($post['action']) {
	case 'chuser':
		set_user($db, $uid, array( 'name' => $post['name'], 'email' => $post['email']));
		break;
	}
}

?>
