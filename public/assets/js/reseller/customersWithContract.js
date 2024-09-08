function fetchCustomers() {
	$.ajax({
		type: "GET",
		url: apiUrl + "reseller/customerWithContract",
		dataType: "json",
		success: function (result) {
			if (result.length > 0) {
				makeLineFetchCustomers(result);
			} else {
				$("#customersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
			}
		},

		error: function (xhr) {
			alert("Request Status: " + xhr.status + " Status Text: " + xhr.statusText + " " + xhr.responseText);
		},
	});
}

fetchCustomers();

function makeLineFetchCustomers(customers) {
	s = "";
	for (var i = 0; i < customers.length; i++) {
		s += '<tr style="cursor:pointer;" class="align-middle">';
		s += '<td class="text-nowrap">' + (customers[i].afm == null ? "" : customers[i].afm) + "</td>";
		// s += '<td class="text-nowrap">' + (customers[i].fullname == null ? "" : customers[i].fullname) + "</td>";
		s += '<td class="text-nowrap">' + (customers[i].company == null ? "" : customers[i].company) + "</td>";
		s += '<td class="text-nowrap">' + formatDateWithoutTime(customers[i].updated_at == null ? "" : customers[i].updated_at) + "</td>";
		// s += '<td class="text-nowrap"><button onclick="window.location=\'https://invoicing4all.com/reseller/edit-customer/' + customers[i].id + '\'" class="btn btn-primary">Επεξεργασία</button></td>';
		s += '<td class="text-nowrap"><span class="badge badge-subtle-info">Αναμονή έγκρισης</span></td>';

		s += "</tr>";
	}
	$("#customersBody").html(s);
}

function sendContract($id) {
	$.ajax({
		type: "POST",
		url: "https://invoicing4all.com/reseller/ajaxSrv.php?op=sendContract&id=" + $id,
		dataType: "json",
		success: function (result) {
			console.log("success");
			fetchCustomers();
		},

		error: function (xhr) {
			alert("Request Status: " + xhr.status + " Status Text: " + xhr.statusText + " " + xhr.responseText);
		},
	});
}
