function fetchCustomers() {
	if ($.fn.DataTable.isDataTable("#customersTable")) {
		$("#customersTable").DataTable().destroy();
	}

	$.ajax({
		type: "GET",
		url: apiUrl + "reseller/unauthorized",
		dataType: "json",
		success: (result) => {
			const customers = result;
			$("#customersBody").empty(); // Clear content instead of redundant html()
			console.log(customers);
			if (customers.length > 0) {
				renderCustomerRows(customers);
			} else {
				$("#customersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
			}
		},
		error: handleAjaxError,
	});
}

function renderCustomerRows(customers) {
	let rows = customers
		.map(
			(customer) => `
		<tr style="cursor:pointer;" class="align-middle">
			<td class="text-nowrap">
				<a href="#" onclick="copyAfm('${customer.afm}')">
					<i class="fa-solid fa-copy"></i> ${customer.afm}
				</a>
			</td>
			<td class="text-nowrap">${customer.company}</td>
			<td class="text-nowrap">${customer.email}</td>
			<td class="text-nowrap">${formatDateWithoutTime(customer.created_at)}</td>
			<td class="text-nowrap">
				<button onclick="deleteCustomer(${customer.id})" class="btn btn-danger">Διαγραφή</button>
			</td>
		</tr>
	`
		)
		.join("");
	$("#customersBody").html(rows);

	var table = $("#customersTable").DataTable({
		order: [],
		language: {
			url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json",
		},
	});
}

function deleteCustomer(id) {
	$.ajax({
		type: "DELETE",
		url: apiUrl + `reseller/deleteCustomer/${id}`,
		dataType: "json",
		success: () => {
			fetchCustomers();
		},
		error: handleAjaxError,
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

function handleAjaxError(xhr) {
	alert(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
}

fetchCustomers();
