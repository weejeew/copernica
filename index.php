<?php
 
/*
Plugin Name: Copernica Marketing Software Plugin
Plugin URI: http://crc-online.nl
Description: A WordPress plug-in to write user(meta) data to a Copernica database.
Author: Mario
Version: 1.0
License: GPL
Author URI: http://crc-online.nl
Min WP Version: 3.3.2
Max WP Version: 3.3.2
*/
 



function ap_copernica_add_menu() {
	add_options_page("Copernica", "Copernica", 9, __FILE__, "ap_copernica_node");
}

function ap_copernica_node() {

	global $wpdb;

	if (!$_POST) {
		ap_copernica_create_db($wpdb -> prefix);
		ap_copernica_include_form($wpdb -> prefix);
	} elseif ($_POST["ap_cop_un"]) {
		ap_copernica_save_login($_POST, $wpdb -> prefix);
	}

	ap_copernica_profiles();
}

function ap_copernica_profiles() {

                $query = mysql_query("SELECT u.ID AS id, u.user_login AS login, u.user_email AS email, u.user_registered AS registered, um1.meta_value AS bedrijfsnaam, um2.meta_value AS plaats, um3.meta_value AS straatnaam, um4.meta_value AS huisnummer, um5.meta_value AS postcode, um6.meta_value AS tel, um7.meta_value AS telmob, um8.meta_value AS geslacht, um9.meta_value AS firstname, um10.meta_value AS lastname
                FROM wp_users u
                JOIN wp_usermeta um1 ON u.ID = um1.user_id
                JOIN wp_usermeta um2 ON u.ID = um2.user_id
                JOIN wp_usermeta um3 ON u.ID = um3.user_id
                JOIN wp_usermeta um4 ON u.ID = um4.user_id
                JOIN wp_usermeta um5 ON u.ID = um5.user_id
                JOIN wp_usermeta um6 ON u.ID = um6.user_id
                JOIN wp_usermeta um7 ON u.ID = um7.user_id
                JOIN wp_usermeta um8 ON u.ID = um8.user_id
                JOIN wp_usermeta um9 ON u.ID = um9.user_id
                JOIN wp_usermeta um10 ON u.ID = um10.user_id
                WHERE um1.meta_key = 'bedrijfsnaam'
                AND um2.meta_key = 'plaats' 
                AND um3.meta_key = 'straatnaam'
                AND um4.meta_key = 'huisnummer'
                AND um5.meta_key = 'postcode'
                AND um6.meta_key = 'tel'
                AND um7.meta_key = 'telmob'
                AND um8.meta_key = 'geslacht'
                AND um9.meta_key = 'first_name'
                AND um10.meta_key = 'last_name'");
 
                while ($row = mysql_fetch_assoc($query)) {
                               $data[] = $row;
                }
 
                require_once ("ap_copernica_acp_action.tpl");
 
                if ($_POST["ap_cop_export"]) {
                               ap_copernica_transfer($data);
                }
}
 
function ap_copernica_transfer($data) {
 
                require_once ('soapclient.php');
 
                $query = mysql_query("SELECT wpc.hostname AS host, wpc.username AS user, wpc.accountname AS account, wpc.password AS pw, wpc.datenbank AS dbID
                               FROM wp_copernica wpc
                               WHERE wpc.id = 1");
 
                while ($row = mysql_fetch_assoc($query)) {
                               $coptab[] = $row;
                }
 
                $soapclient = new PomSoapClient($coptab[0]["host"], $coptab[0]["user"], $coptab[0]["account"], $coptab[0]["pw"]);
 
                $query = mysql_query("SELECT `datenbank` AS db FROM `wp_copernica` WHERE `id` = 1");
                while ($row = mysql_fetch_assoc($query)) {
                               $db = $row;
                }
 
                echo "<br /><br/><table width=\"50%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
                foreach ($data as $key => $value) {
                               $param = array("id" => $db["db"], "fields" => array("login" => $value["login"], "email" => 'other@address.nl', "registered" => $value["registered"], "bedrijfsnaam" => $value["bedrijfsnaam"], "plaats" => $value["plaats"], "straatnaam" => $value["straatnaam"], "huisnummer" => $value["huisnummer"], "postcode" => $value["postcode"], "tel" => $value["tel"], "telmob" => $value["telmob"], "geslacht" => $value["geslacht"], "firstname" => $value["firstname"], "lastname" => $value["lastname"]));

		$result = $soapclient -> Database_createProfile ($param);
		
		echo "<tr><td>$value[email]</td><td>";
		echo (!empty($result)) ? "Success" : "Error";
		echo "</td></tr>";
		
	}
	echo "</table>";

}

function ap_copernica_include_form($pre) {

	$query = mysql_query("SELECT `hostname`, `username`, `accountname`, `password`, `datenbank`
		FROM `" . $pre . "copernica`
		WHERE `id` = 1");

	while ($row = mysql_fetch_assoc($query)) {
		$result = $row;
	}

	require_once ("ap_copernica_acp_loginsave.tpl");
}

function ap_copernica_save_login($data, $pre) {
	$query = mysql_query("UPDATE `" . DB_NAME . "`.`" . $pre . "copernica` 
		SET `hostname` = '$data[ap_cop_hn]',
		`username` = '$data[ap_cop_un]',
		`accountname` = '$data[ap_cop_an]',
		`password` = '$data[ap_cop_pw]' , 
		`datenbank` = '$data[ap_cop_db]' 
		WHERE `wp_copernica`.`id` = 1;");

	if ($query == (bool)true) {
		echo "<div class=\"updated settings-error\" id=\"setting-error-settings_updated\"><p><strong>Settings saved.</strong></p></div>";
	} else {
		echo "<div class=\"updated settings-error\" id=\"setting-error-settings_updated\"><p><strong>Error: Settings have not being saved.</strong></p></div>";
	}
}

function ap_copernica_create_db($pre) {

	$query = mysql_query("SELECT c.id AS id FROM wp_copernica c WHERE c.id = 1");

	while ($row = mysql_fetch_assoc($query)) {
		$result = $row;
	}

	if ($result["id"] != 1) {
		$create = mysql_query("CREATE TABLE IF NOT EXISTS `" . $pre . "copernica` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`hostname` varchar(50) NOT NULL,
			`username` varchar(50) NOT NULL,
			`accountname` varchar(50) NOT NULL,
			`password` varchar(50) NOT NULL,
			`datenbank` int(11) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

		$filler = mysql_query("INSERT INTO `" . DB_NAME . "`.`" . $pre . "copernica` (`id`, `hostname`, `username`, `accountname`, `password`, `datenbank`) 
			VALUES (NULL, 'Hostname', 'Username', 'Accountname', 'Password', 0);");
	}
}

function ap_write_new_db () {

}

function ap_copernica_content($content) {
	return strtolower($content);
}

add_action("admin_menu", "ap_copernica_add_menu");
?>