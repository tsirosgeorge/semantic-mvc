$.ajax({
	url: apiUrl + "admin/dashboard",
	method: "GET",
	dataType: "json",
	success: function (data) {
		// Request was successful
		console.log(data);
		$("#totalCustomers").text(data.total_customers);
		$("#totalSofthouses").text(data.total_softhouse);
		$("#totalResellers").text(data.total_resellers);
		// Do something with the data
	},
	error: function (xhr, status, error) {
		// Request failed
		console.error("Request failed. Status code: " + xhr.status);
	},
});
