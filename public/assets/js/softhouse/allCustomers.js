function fetchCustomers() {
	//Destroy the DataTable
	if ($.fn.DataTable.isDataTable("#customersTable")) {
		$("#customersTable").DataTable().destroy();
	}

	$.ajax({
		type: "GET",
		url: apiUrl + "softhouse/",
		dataType: "json",
		success: function (result) {
			if (result.length > 0) {
				makeLineFetchCustomers(result);
			} else {
				$("#customersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
			}
		},
		error: function (xhr) {
			console.error("Request Status:", xhr.status, "Status Text:", xhr.statusText, "Response Text:", xhr.responseText);
		},
	});
}

fetchCustomers();

function makeLineFetchCustomers(customers) {
	let s = "";
	let uniqueResellers = new Set(); // Use a Set to keep track of unique reseller names

	for (let i = 0; i < customers.length; i++) {
		const customer = customers[i];
		const customerId = customer.id;
		const editUrl = `${apiUrl}edit-customer/${customerId}`;

		// Add reseller name to the Set
		if (customer.rfullname) {
			uniqueResellers.add(customer.rfullname);
		}

		s += `<tr style="cursor:pointer;" class="align-middle">`;
		s += `<td class="text-nowrap afm-excel text-start">${customer.afm || ""}</td>`;
		s += `<td class="text-nowrap text-ellipsis">${truncateString(customer.company || "", 40)}</td>`;
		s += `<td class="text-nowrap">${customer.rfullname || ""}</td>`;
		s += `<td class="text-nowrap date-excel">${formatDateWithoutTime(customer.created_at || "")}</td>`;
		s += `<td class="text-nowrap text-end"><button onclick="editCustomer(${customerId})" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button></td>`;
		s += `</tr>`;
	}
	$("#customersBody").html(s);

	// Populate the select dropdown with unique values from the rfullname column
	const filterSelect = $("#filterRfullname");
	filterSelect.html('<option value="">All</option>'); // Clear existing options and add the "All" option

	uniqueResellers.forEach((reseller) => {
		filterSelect.append('<option value="' + reseller + '">' + reseller + "</option>");
	});

	// Initialize the DataTable
	var table = $("#customersTable").DataTable({
		order: [],
		language: {
			url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json",
		},
	});

	// Custom filtering function
	$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
		var selectedReseller = $("#filterRfullname").val();
		var resellerName = data[2]; // Use the correct index for the rfullname column

		if (selectedReseller === "" || resellerName === selectedReseller) {
			return true;
		}
		return false;
	});

	// Event listener for the select dropdown
	$("#filterRfullname")
		.off("change")
		.on("change", function () {
			table.draw();
		});
}

function editCustomer(id) {
	$("#editCustomer").modal("show");

	const myModal = document.getElementById("editCustomer");

	// Clear the fields
	$("#customerIdModal").val("");
	$("#emailModal").val("");
	$("#passModal").val("");
	$("#afmModal").val("");
	$("#cityModal").val("");
	$("#addressModal").val("");
	$("#postalcodeModal").val("");
	$("#companyModal").val("");
	$("#resellerModal").val("");

	// Remove any existing event handlers to prevent multiple bindings
	$(myModal)
		.off("shown.bs.modal")
		.on("shown.bs.modal", function () {
			$.ajax({
				type: "GET",
				url: apiUrl + "softhouse/" + id,
				dataType: "json",
				success: function (result) {
					const customer = result[0];
					$("#customerIdModal").val(customer.id);
					$("#emailModal").val(customer.email);
					$("#passModal").val(customer.password);
					$("#afmModal").val(customer.afm);
					$("#cityModal").val(customer.city);
					$("#addressModal").val(customer.address);
					$("#postalcodeModal").val(customer.postalcode);
					$("#companyModal").val(customer.company);
					$("#resellerModal").val(customer.rfullname);
				},
				error: function (xhr) {
					console.error("Request Status:", xhr.status, "Status Text:", xhr.statusText, "Response Text:", xhr.responseText);
				},
			});
		});
}

function saveCustomer() {
	const ar = {
		id: $("#customerIdModal").val(),
		email: $("#emailModal").val(),
		password: $("#passModal").val(),
		afm: $("#afmModal").val(),
		city: $("#cityModal").val(),
		address: $("#addressModal").val(),
		postalcode: $("#postalcodeModal").val(),
		company: $("#companyModal").val(),
	};

	$.ajax({
		type: "PUT",
		url: apiUrl + "softhouse/" + $("#customerIdModal").val(),
		data: JSON.stringify(ar),
		dataType: "json",
		success: function (result) {
			if (result.success == false) {
				Swal.fire({
					position: "center",
					icon: "error",
					title: "Δεν βρέθηκε ο συγκεκριμένος πελάτης",
					showConfirmButton: false,
					timer: 1500,
				}).then(() => {
					// window.location.href = apiUrl + "";
				});
			} else {
				Swal.fire({
					position: "center",
					icon: "success",
					title: "Επιτυχής ενημέρωση",
					showConfirmButton: false,
					timer: 1500,
				}).then(() => {
					$("#editCustomer").modal("hide");
					fetchCustomers();
				});
			}
		},
		error: function (xhr) {
			console.error("Request Status:", xhr.status, "Status Text:", xhr.statusText, "Response Text:", xhr.responseText);
		},
	});
}
