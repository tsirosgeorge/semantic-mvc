function fetchCustomers() {
	$.ajax({
		type: "GET",
		url: apiUrl + "reseller/readyCustomers",
		dataType: "json",
		success: function (result) {
			$("#customersBody").html();
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

function makeLineFetchCustomers(customers) {
	s = "";
	for (var i = 0; i < customers.length; i++) {
		s += '<tr style="cursor:pointer;" class="align-middle">';
		s += '<td class="text-nowrap">' + customers[i].afm + "</td>";
		s += '<td class="text-nowrap">' + customers[i].company + "</td>";
		s += '<td class="text-nowrap">' + customers[i].email + "</td>";
		$eskapBtn = "";
		if (customers[i].eskap == 0) {
			$eskapBtn = "<button onclick=\"registerEskap('" + customers[i].id + "', '" + customers[i].company + "', '" + customers[i].email + "', '" + customers[i].password + "', '" + customers[i].fullname + "', '" + customers[i].afm + "', '" + customers[i].address + "', '" + customers[i].postalcode + "', '" + customers[i].city + '\')" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button>';
		} else {
			$eskapBtn = '<span class="badge badge-subtle-success">Εγγεγραμμένος</span>';
		}
		if (customers[i].activate == 1) {
			s += '<td class="text-nowrap"><span class="badge badge-subtle-success">Ενεργοποίηση</span> | ' + $eskapBtn + "</td>";
		} else {
			s += '<td class="text-nowrap"><span class="badge badge-subtle-success">Ολοκληρώθηκε</span> | ' + $eskapBtn + "</td>";
		}

		s += "</tr>";
	}
	$("#customersBody").html(s);
}

function authorizeCustomer($id) {
	$.ajax({
		type: "POST",
		url: "https://invoicing4all.com/reseller/ajaxSrv.php?op=authorizeCustomer&id=" + $id,
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

fetchCustomers();

function registerEskap($id, $company, $email, $password, $fullname, $afm, $address, $postalcode, $city) {
	var ar = {};
	ar.id = $id;
	ar.email = $email;
	ar.pass = $password;
	ar.company = $company;
	ar.firstname = $fullname ? $fullname.split(" ")[0] || "" : "";
	ar.lastname = $fullname ? $fullname.split(" ")[1] || "" : "";
	ar.afm = $afm;
	ar.address = $address;
	ar.postcode = $postalcode;
	ar.city = $city;

	$.ajax({
		type: "POST",
		url: apiUrl + "reseller/registerEskap",
		dataType: "json",
		data: JSON.stringify(ar),
		success: function (result) {
			try {
				var message = JSON.parse(result.message);
				console.log(message);
				if (message.error === "A customer with this email and afm already exists") {
					Swal.fire({
						position: "center",
						icon: "error",
						title: "Ο πελάτης υπάρχει ήδη",
						showConfirmButton: false,
						timer: 1500,
					});
					return;
				}
				if (message.error === "Reseller not found") {
					Swal.fire({
						position: "center",
						icon: "error",
						title: "Δεν βρέθηκε μεταπωλητής",
						showConfirmButton: false,
						timer: 1500,
					});
					return;
				}
				if (message.success === true) {
					Swal.fire({
						position: "center",
						icon: "success",
						title: "Ο πελάτης αποθηκεύτηκε με επιτυχία",
						showConfirmButton: false,
						timer: 1500,
					});
					fetchCustomers();
				}
			} catch (e) {
				console.error("Error parsing result.message:", e);
				Swal.fire({
					position: "center",
					icon: "error",
					title: "Unexpected error",
					text: "An unexpected error occurred. Please try again later.",
					showConfirmButton: true,
				});
			}
		},

		error: function (xhr) {
			alert("Request Status: " + xhr.status + " Status Text: " + xhr.statusText + " " + xhr.responseText);
		},
	});
}
