// Helper functions
function createTableRow(customer) {
	return `
        <tr style="cursor:pointer;" class="align-middle">
            <td class="text-nowrap afm-excel">
                <a href="#" onclick="copyAfm('${customer.afm}')">
                    <i class="fa-solid fa-copy"></i> ${customer.afm}
                </a>
            </td>
            <td class="text-nowrap text-ellipsis">${truncateString(customer.company, 40)}</td>
            <td class="text-nowrap">${customer.rfullname ? customer.rfullname : ""}</td>
            <td class="text-nowrap date-excel">${formatDateWithoutTime(customer.created_at)}</td>
            <td class="text-nowrap">
                <button onclick="authorizeCustomer(${customer.id})" class="btn btn-success">TaxisNet</button> / 
                <button onclick="deleteCustomer(${customer.id})" class="btn btn-danger">Διαγραφή</button>
            </td>
        </tr>`;
}

function populateResellerFilter(uniqueResellers) {
	const filterSelect = $("#filterRfullname");
	filterSelect.empty(); // Clear existing options
	filterSelect.append('<option value="">All</option>'); // Add the "All" option

	uniqueResellers.forEach((reseller) => {
		filterSelect.append(`<option value="${reseller}">${reseller}</option>`);
	});
}

function fetchCustomers() {
	if ($.fn.DataTable.isDataTable("#customersTable")) {
		$("#customersTable").DataTable().destroy();
	}

	$.ajax({
		type: "GET",
		url: apiUrl + "softhouse/unauthorized",
		dataType: "json",
		success: function (result) {
			$("#customersBody").empty(); // Clear existing rows

			if (result.length > 0) {
				makeLineFetchCustomers(result);
			} else {
				$("#customersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
			}
		},
		error: function (xhr) {
			console.error(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
		},
	});
}

function makeLineFetchCustomers(customers) {
	let s = "";
	const uniqueResellers = new Set(); // Set to keep track of unique reseller names

	customers.forEach((customer) => {
		if (customer.rfullname) {
			uniqueResellers.add(customer.rfullname);
		}
		s += createTableRow(customer);
	});

	$("#customersBody").html(s);
	populateResellerFilter(uniqueResellers);

	// Initialize the DataTable
	const table = $("#customersTable").DataTable({
		order: [],
		language: {
			url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json",
		},
	});

	// Custom filtering function
	$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
		const selectedReseller = $("#filterRfullname").val();
		const resellerName = data[2]; // Use the correct index for the rfullname column

		return selectedReseller === "" || resellerName === selectedReseller;
	});

	// Event listener for the select dropdown
	$("#filterRfullname")
		.off("change")
		.on("change", function () {
			table.draw();
		});
}

function authorizeCustomer(id) {
	$.ajax({
		type: "PUT",
		url: `${apiUrl}softhouse/authorize/${id}`,
		dataType: "json",
		success: function () {
			toastr.success("Ο πελάτης εξουσιοδοτήθηκε με επιτυχία");
			fetchCustomers();
		},
		error: function (xhr) {
			console.error(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
		},
	});
}

function deleteCustomer(id) {
	$.ajax({
		type: "DELETE",
		url: `${apiUrl}softhouse/delete/${id}`,
		dataType: "json",
		success: function () {
			toastr.success("Ο πελάτης διαγράφηκε με επιτυχία");
			fetchCustomers();
		},
		error: function (xhr) {
			console.error(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
		},
	});
}

function copyAfm(afm) {
	const tempInput = document.createElement("input");
	document.body.appendChild(tempInput);
	tempInput.value = afm;
	tempInput.select();
	document.execCommand("copy");
	document.body.removeChild(tempInput);
	toastr.success(`Το Α.Φ.Μ ${afm} αντιγράφτηκε με επιτυχία`);
}

fetchCustomers(); // Initial fetch
