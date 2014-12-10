<?php

function connect()
{
	$conn = new PDO('sqlite:ski.sqlite');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	return $conn;
}

function access($db, $user, $killer, $write = false)
{
	if(isset($_SESSION['superuser'])) 
		return true;
		
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

function add_user($db, $user, $pass)
{
	$query = $db->prepare('INSERT INTO kill_ppl(name, email) VALUES(?, ?)');
	$query->execute(array($user['name'], $user['email']));
	$id = $db->lastInsertID();
	set_pw($db, $id, $pass);
}

function list_users($db)
{
	$query = $db->prepare('SELECT * FROM kill_ppl ORDER BY name');
	$query->execute();
	return $query->fetchAll();
}

function list_all_killers($db)
{
	$query = $db->prepare('SELECT name, desc, phase, id AS killer FROM kill_killers');
	$query->execute(array($user_id));
	return $query->fetchAll();
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
	$query->execute(array($killer['name'], $killer['email_text'], $killer['desc']));
	$id = $db->lastInsertID();
	
	$query = $db->prepare('INSERT INTO kill_k_p(killer, user, admin) VALUES (?, ?, 1)');
	$query->execute(array($id, $user_id));
	
	return $id;
}

function change_killer($db, $uid, $killer_id, $killer_params)
{
	if(!access($db, $uid, $killer_id, true)) {
		die();
	}
	$query = $db->prepare('UPDATE kill_killers SET name=?, desc=?, email_text=? WHERE id=?');
	$query->execute(array($killer_params['name'], $killer_params['desc'], $killer_params['email_text'], $killer_id));
}

/* TODO : phase management */
/* 0 : add/remove users
 * 0 -> 1 : send mail
 * 1 : fill in kills
 * 1 -> 2 : attribute, send kills
 * 2 : fill in what's done
 * 3 : ended */

$phases_text = array( 0 => "Sélection des participants", 1 => "Remplissage des kills", 2 => "Jeu en cours", 3 => "Terminé" );

function get_killer($db, $killer_id)
{
	$query = $db->prepare('SELECT * FROM kill_killers WHERE id=?');
	$query->execute(array($killer_id));
	return $query->fetch();
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

function add_or_remove_users($db, $uid, $kid, $post)
{
	if(!access($db, $uid, $kid, true)) {
		die();
	}
	if(get_killer($db, $kid)['phase'] != 0) {
		return;
	}

	$usrs = array_map(function ($x) { return $x[0]; }, list_user_killer($db, $kid));
	
	//Remove unchecked users
	foreach($usrs as $id) {
		if(!isset($post["select_$id"])) {
			remove_user_killer($db, $kid, $id);
		}
	}

	//Add checked users
	foreach($post as $name => $value) {
		if(substr($name, 0, 7) == "select_" && !in_array($value, $usrs)) {
			add_user_killer($db, $kid, $value);
		}
	}
}

function all_kills($db, $killer_id)
{
	$query = $db->prepare('SELECT * FROM kill_kills WHERE killer=?');
	$query->execute(array($killer_id));
	$ans = $query->fetchAll();
	$arr = array();

	foreach($ans as $line) {
		$arr[$line['for']][$line['assigned_to']] = $line;
	}

	return $arr;
}

function list_user_killer($db, $killer_id)
{
	$query = $db->prepare('SELECT * FROM kill_ppl INNER JOIN kill_k_p ON kill_k_p.user = kill_ppl.id WHERE kill_k_p.killer=? ORDER BY name');
	$query->execute(array($killer_id));
	return $query->fetchAll();
}

function count_usr_kills($db, $killer_id, $user_id)
{
	$query = $db->prepare('SELECT COUNT(desc) FROM kill_kills WHERE killer=? AND writer=?');
	$query->execute(array($killer_id, $user_id));
	return $query->fetchColumn();
}

function usr_kills($db, $killer_id, $user_id, $writer)
{
	$query = $db->prepare('SELECT * FROM kill_kills WHERE killer=? AND '.($writer?'writer':'assigned_to').'=?');
	$query->execute(array($killer_id, $user_id));
	return $query->fetchAll();
}


function get_usr_kills($db, $killer_id, $user_id)
{
# Why u[0] and not u['id'] ?
	$kills = usr_kills($db, $killer_id, $user_id, true);
	$users = list_user_killer($db, $killer_id);
	$res = array();
	$names = array();
	
	foreach($users as $u) {
		$res[$u[0]] = "";
	}
	foreach($kills as $k) {
		$res[$k['for']] = $k['desc'];
	}
	
	foreach($users as $u) {
		$names[$u[0]] = $u['name'];
	}
	
	return array($res, $names);
}

function write($db, $uid, $kid, $kills)
{
	if(!access($db, $uid, $kid) or get_killer($db, $kid)['phase'] != 1) {
		die();
	}
	
	foreach($kills as $id => $k) {
		if($k == "") {
			$query = $db->prepare('DELETE FROM kill_kills WHERE writer=? AND for=? AND killer=?');
			$query->execute(array($uid, $id, $kid));
		} else {
			$query = $db->prepare('REPLACE INTO kill_kills(writer, for, desc, killer) VALUES (?, ?, ?, ?)');
			$query->execute(array($uid, $id, $k, $kid));
		}
	}
}

function kills_list($db, $uid, $kid, $post)
{
	$usrs = list_user_killer($db, $kid);
	$res = array();
	foreach($usrs as $u) {
		if($u[0] == $uid) {
			continue;
		}
		$res[$u[0]] = trim($post['kill_'.$u[0]]);
	}

	return $res;
}


function shuffle_array($arr) {
	$randkeys = array_keys($arr);
	shuffle($randkeys);
	$keys = array_keys($arr);
	
	for($i = 0; $i < count($arr); $i++)
		$res[$randkeys[$i]] = $arr[$keys[$i]];

	return $res;
}

//TODO in phase functions : check current phase

function phase_to_1($db, $uid, $kid)
{
    $query = $db->prepare('UPDATE kill_killers SET phase=? WHERE id=?');
    $query->execute(array(1, $kid));
}

function phase_to_3($db, $uid, $kid)
{
    $query = $db->prepare('UPDATE kill_killers SET phase=? WHERE id=?');
    $query->execute(array(3, $kid));
}

function phase_to_2($db, $uid, $kid)
{
	if(!access($db, $uid, $kid, true)) {
		die();
	}

	$usrs = array_map(function($x) { return $x[0]; }, list_user_killer($db, $kid));
	$nb = count($usrs);

	//Check that all kills are filled
	foreach($usrs as $u) {
		if(count_usr_kills($db, $kid, $u) != $nb - 1) {
			$err = "Some kills are not filled";
			return;
		}
	}

	//Randomize each matrix row
	foreach($usrs as $u) {
		$query = $db->prepare('SELECT * FROM kill_kills WHERE for=?');
		$query->execute(array($u));
		$res = $query->fetchAll();
		$attr = array();
		foreach($res as $row)
			$attr[$row['writer']] = $row;
		$attr = shuffle_array($attr);

		foreach($attr as $a => $kill) {
			echo('Assigning kill '.$kill['desc'].' (by '.$kill['writer'].' for '.$kill['for'].') to '.$a.'<br>');
			$query = $db->prepare('UPDATE kill_kills SET assigned_to=? WHERE for=? AND writer=? AND killer=?');
			$query->execute(array($a, $kill['for'], $kill['writer'], $kid));
		}
	}

	//Set new phase
	$query = $db->prepare('UPDATE kill_killers SET phase=? WHERE id=?');
	$query->execute(array(2, $kid));
}

function mark_kill($db, $uid, $kid, $for, $writer)
{
	$query = $db->prepare('UPDATE kill_kills SET done=1 WHERE for=? AND writer=? AND killer=?');
	$query->execute(array($for, $writer, $kid));
}

function act($db, $get, $post, $uid, $u)
{
	global $err;

	if(!isset($post['action'])) {
		return;
	}
	switch($post['action']) {
	case 'chuser':
		set_user($db, $uid, array( 'name' => $post['name'], 'email' => $post['email']));
		break;
	case 'adduser':
		add_user($db, array( 'name' => $post['name'], 'email' => $post['email']), $post['pw']);
		break;
	case 'chpw':
		if(!(check_login($db, $u['email'], $post['curpw'])['success'])) {
			$err = "Mot de passe actuel incorrect";
			$_GET['page'] = 'change_password';
		} else {
			set_pw($db, $uid, $post['newpw']);
		}
		break;
	case 'addkiller':
		$kid = add_killer($db, $uid, array('name' => $post['name'], 'desc' => $post['desc'], 'email_text' => ""));
		add_or_remove_users($db, $uid, $kid, $post);
		break;
	case 'chkiller':
		change_killer($db, $uid, $post['id'], array('name' => $post['name'], 'desc' => $post['desc'], 'email_text' => ""));
		add_or_remove_users($db, $uid, $post['id'], $post);
		break;
	case 'write':
		write($db, $uid, $get['id'], kills_list($db, $uid, $get['id'], $post));
		break;
	case 'phase1':
		phase_to_1($db, $uid, $get['id']);
		break;
	case 'phase2':
		phase_to_2($db, $uid, $get['id']);
		break;
	case 'phase3':
		phase_to_3($db, $uid, $get['id']);
		break;
	case 'markKill':
		mark_kill($db, $uid, $get['id'], $post['for'], $post['writer']);
		break;
	case 'setAdmin':
		if(access($db, $uid, $get['id'], true))
			set_admin_flag($db, $get['id'], $post['id'], $post['admin']);
		break;
	case 'delete':
		if(access($db, $uid, $post['id'], true))
			delete_killer($db, $post['id'], true);
	}
}

function make_title($str) {
	echo("<div class=\"page_header\"><h1>$str</h1></div>\n");
}

function make_names($db, $kid) {
	$usr = list_user_killer($db, $kid);

	$usr_sorted = array();
	foreach($usr as $u) {
		$usr_sorted[$u[0]] = $u;
	}
	
	return $usr_sorted;
}

function get_name($usr_sorted, $id) {
	return $usr_sorted[$id]['name'];
}

?>
