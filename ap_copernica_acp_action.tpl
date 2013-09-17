<?php

echo "
<div class=\"wrap\">
	
	<h3>WordPress to Copernica Account Export</h3>
	
	<form name=\"ap_copernica_login\" method=\"post\" action=\"\">
		<table class=\"form-table\">
			<tbody>
				<tr>
					<th>Transfer WordPress Userdata/Accounts to your Copernica Database.</th>
					<td>
						<input type=\"submit\" value=\"WP Export\" />
						<input type=\"hidden\" name=\"ap_cop_export\" id=\"ap_cop_export\" value=\"go\" />
					</td>
				</tr>
			</tbody>
		</table>						
	</form>
	
</div>";

?>