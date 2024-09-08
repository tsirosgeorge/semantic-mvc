// Utility Functions

function handleAjaxError(xhr) {
	alert(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
}

function ajaxRequest(type, url, data = {}, successCallback, errorCallback = handleAjaxError) {
	$.ajax({
		type,
		url,
		dataType: "json",
		data,
		success: successCallback,
		error: errorCallback,
	});
}

// Fetch and Render Resellers

function fetchResellers() {
	if ($.fn.DataTable.isDataTable("#resellersTable")) {
		$("#resellersTable").DataTable().destroy();
	}
	ajaxRequest("GET", apiUrl + "admin/resellers/", {}, (result) => {
		const resellers = result.resellers || [];
		const totalCustomers = result.totalCustomersPerReseller || [];

		console.log(resellers);
		if (resellers.length > 0) {
			renderResellers(resellers, totalCustomers);
		} else {
			$("#resellersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
		}
	});
}

function renderResellers(resellers, totalCustomers) {
	const resellersWithCount = resellers.map((reseller) => {
		const customerData = totalCustomers.find((tc) => tc.resellerid === reseller.id) || {};
		return { ...reseller, count: customerData.customer_count || 0 };
	});

	// Sort resellers by customer count in descending order
	resellersWithCount.sort((a, b) => b.count - a.count);

	const rows = resellersWithCount
		.map(
			(reseller) => `
        <tr style="cursor:pointer;" class="align-middle">
            <td class="text-nowrap text-start">${reseller.afm == null ? "-" : reseller.afm}</td>
            <td class="text-nowrap">${reseller.name == null ? "-" : reseller.name}</td>
            <td class="text-nowrap">${reseller.email == null ? "-" : reseller.email}</td>
            <td class="text-nowrap text-start">${reseller.count}</td>
            <td class="text-nowrap">
                <button class="btn btn-danger btn-sm" onclick="deleteReseller(${reseller.id})">Διαγραφή</button>
            </td>
        </tr>`
		)
		.join("");

	$("#resellersBody").html(rows);

	var table = $("#resellersTable").DataTable({
		order: [],
		language: {
			url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json",
		},
	});
}

// Delete Reseller

function deleteReseller(id) {
	ajaxRequest("DELETE", apiUrl + "admin/resellers/delete/" + id, {}, (result) => {
		if (result.message === "Reseller was not deleted successfully") {
			Swal.fire({
				position: "center",
				icon: "error",
				title: "Ο συγκεκριμένος μεταπωλητής έχει πελάτες",
				showConfirmButton: true,
			});
		} else {
			fetchResellers();
		}
	});
}

// Initialize

$(document).ready(() => {
	fetchResellers();
});
