/*
 * This file is part of the Eannounce plugin for MantisBT.
 *
 * Eannounce is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * The Eannounce plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with the Eannounce plugin.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * Scripts of group mail sending page
 * 
 * Handles selection of profiles
 */

$(document).ready(function() {
	
	$(document).on('click', 'input[name="to[]"]', function($this){
		displayRecipients($this.currentTarget.value, $this.currentTarget.checked);
	});
	
	$(document).on('change', 'select[name="project"]', function($this){
		var value = $this.currentTarget.value;
		document.getElementById('eannounce-send-message-form').reset();
		$this.currentTarget.value = value;
	})
	
});

function displayRecipients(profile, checked) {
	var profiles_checked = $("input[name='to[]']:checked");
	
	var selected_profile = [];
	
	for(var i = 0; i < profiles_checked.length; i++) {
		selected_profile[i] = profiles_checked[i].value;
	}
	
	var addresses = $("#eannounce_bcc")[0].value ? $("#eannounce_bcc")[0].value.split(";").map(function(item){
		return item.trim();
	}) : [];
	var project = $("#eannounce_project")[0].value;
	
	$.ajax({
		url : document.getElementById("recipient_page").value,
		type : "POST",
		data : {
			selected_profile : profile, 
			checked : checked, 
			addresses : addresses,
			project : project
		},
		success: function(response) {
			$("#eannounce_bcc")[0].value = response;
		}
	})
}