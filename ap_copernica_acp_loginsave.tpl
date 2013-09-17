<?php

echo "
<div class=\"wrap\">
	<div class=\"icon32\" id=\"icon-options-general\"><br /></div>
	<h2>Copernica Options</h2>
	
	<h3>Copernica Account Information</h3>
	
	<form name=\"ap_copernica_login\" method=\"post\" action=\"\">
		<table class=\"form-table\">
			<tbody>
				<tr>
					<th>Hostname</th>
					<td><input name=\"ap_cop_hn\" value=\"".$result["hostname"]."\" type=\"text\" /></td>
				</tr>
				<tr>
					<th>Username</th>
					<td><input name=\"ap_cop_un\" value=\"".$result["username"]."\" type=\"text\" /></td>
				</tr>
				<tr>
					<th>Accountname</th>
					<td><input name=\"ap_cop_an\" value=\"".$result["accountname"]."\" type=\"text\" /></td>
				</tr>
				<tr>
					<th>Password</th>
					<td><input name=\"ap_cop_pw\" value=\"".$result["password"]."\" type=\"password\" /></td>
				</tr>
				<tr>
					<th title=\"The specific Table in wich your WP Userdata should be Exported.\">Database ID</th>
					<td><input name=\"ap_cop_db\" value=\"".$result["datenbank"]."\" type=\"text\" /></td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td><input type=\"submit\" value=\"Speichern\" /></td>
				</tr>
			</tbody>
		</table>						
	</form>
	
</div>";

?>