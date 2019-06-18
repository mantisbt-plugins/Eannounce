/**
 * Scripts of group mail sending page
 * 
 * Handles selection of profiles
 */

$(document).ready(function() {
	
	$(document).on('click', 'input[name="to[]"]', function($this){
		displayRecipients($this.currentTarget.value, $this.currentTarget.checked);
	});
	
});

function displayRecipients(profile, checked) {
	var profiles_checked = $("input[name='to[]']:checked");
	
	var selected_profile = [];
	
	for(var i = 0; i < profiles_checked.length; i++) {
		selected_profile[i] = profiles_checked[i].value;
	}
	
	var addresses = $("#eannounce_to")[0].value ? $("#eannounce_to")[0].value.split(";").map(function(item){
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
			$("#eannounce_to")[0].value = response;
		}
	})
}