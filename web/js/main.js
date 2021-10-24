function getSearch() {
	var currentURL = window.location.href.split(/[?#]/)[0];
	arr = {
		"PartnerLocatorSearch":{
			"name" : $("#search-input").val(),
			"country" : $("#customer-country").val(),
			"state" : $("#customer-state").val(),
			"status" : $("#customer-type").val(),
		}
	};
	$("#customer_pjax").html('<div class="content-center">Please wait…</div>');
	$.pjax.defaults.timeout = false;
    $.pjax.reload({
    	container:"#customer_pjax", 
		history: false, 
		type: "POST", 
		data: arr,
		url: currentURL
    });
}

$(document).on("change", ".search-trigger", function() {
	getSearch();
});

$(document).on("change", ".search-trigger-country", function() {
	$("#customer-state").prop("disabled", true);
	$("#customer-state").empty();
	$.ajax({
	    url: "/get-state",
		data: {
			"country": $("#customer-country").val(),
		},
		type: "post",
		success: (response) => {
			if (response != "") {
				var newOption = new Option("", "", false, false);
				$("#customer-state").append(newOption);
				$.each(response, function(index, value) {
					var newOption = new Option(value, index);
					$("#customer-state").append(newOption);
				});
				$("#customer-state").removeAttr("disabled");
			}
		},
		error: (response) => {
	            
		}
	});
});

$(document).on("change", ".search-trigger-state", function() {
	getSearch();
});