function fetchCustomers() {
	$.ajax({
		type: "GET",
		url: apiUrl + "reseller/authorized",
		dataType: "json",
		success: (result) => {
			const customers = result;
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

fetchCustomers();

function renderCustomerRows(customers) {
	const rows = customers
		.map((customer) => {
			const afm = customer.afm ?? "";
			const company = customer.company ?? "";
			const createdAt = formatDateWithoutTime(customer.created_at ?? "");

			let actionCell = "";
			if (customer.fileurl && customer.signed === 0) {
				actionCell = `<button onclick="window.location='https://invoicing4all.com/reseller/edit-customer/${customer.id}'" class="btn btn-success">Επεξεργασία</button>`;
			} else if (customer.signed === 1) {
				actionCell = '<span class="badge badge-soft-info">Σε αναμονή</span>';
			} else {
				actionCell = '<span class="badge badge-soft-info">Σε αναμονή</span>';
			}

			return `
			<tr style="cursor:pointer;" class="align-middle">
				<td class="text-nowrap" onclick="window.location='https://invoicing4all.com/reseller/edit-customer/${customer.id}'">${afm}</td>
				<td class="text-nowrap" onclick="window.location='https://invoicing4all.com/reseller/edit-customer/${customer.id}'">${company}</td>
				<td class="text-nowrap">${createdAt}</td>
				<td class="text-nowrap">${actionCell}</td>
			</tr>
		`;
		})
		.join("");

	$("#customersBody").html(rows);
}

function sendContract(id) {
	$.ajax({
		type: "POST",
		url: `https://invoicing4all.com/reseller/ajaxSrv.php?op=sendContract&id=${id}`,
		dataType: "json",
		success: () => {
			console.log("Contract sent successfully");
			fetchCustomers();
		},
		error: handleAjaxError,
	});
}

function openContractModal(id, afm) {
	$("#edit-contract-modal").data("customerId", id).modal("show");
	$("#customer-afm").val(afm);
}

function handleAjaxError(xhr) {
	alert(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
}
