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

function fetchSofthouses() {
	if ($.fn.DataTable.isDataTable("#softhousesTable")) {
		$("#softhousesTable").DataTable().destroy();
	}
	ajaxRequest("GET", apiUrl + "admin/softhouses/", {}, (result) => {
		const softhouses = result.softhouses || [];
		const totalCustomers = result.totalCustomersPerSofthouse || [];

		if (softhouses.length > 0) {
			renderSofthouses(softhouses, totalCustomers);
		} else {
			$("#softhousesTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
		}
	});
}

function renderSofthouses(softhouses, totalCustomers) {
	const softhousesWithCount = softhouses.map((softhouse) => {
		const customerData = totalCustomers.find((tc) => tc.softhouseid === softhouse.id) || {};
		return { ...softhouse, count: customerData.customer_count || 0 };
	});

	// Sort softhouses by customer count in descending order
	softhousesWithCount.sort((a, b) => b.count - a.count);

	const rows = softhousesWithCount
		.map(
			(softhouse) => `
        <tr style="cursor:pointer;" class="align-middle">
            <td class="text-nowrap">${softhouse.afm == null ? "-" : softhouse.afm}</td>
            <td class="text-nowrap">${softhouse.name == null ? "-" : softhouse.name}</td>
            <td class="text-nowrap">${softhouse.email == null ? "-" : softhouse.email}</td>
            <td class="text-nowrap text-start">${softhouse.count}</td>
            <td class="text-nowrap text-start">
                <button class="btn btn-danger btn-sm" onclick="deleteSofthouse(${softhouse.id})">Διαγραφή</button>
            </td>
        </tr>`
		)
		.join("");

	$("#softhousesBody").html(rows);
	var table = $("#softhousesTable").DataTable({
		order: [],
		language: {
			url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json",
		},
	});
}

// Delete Reseller

function deleteSofthouse(id) {
	ajaxRequest("DELETE", apiUrl + "admin/softhouses/delete/" + id, {}, (result) => {
		if (result.message === "Softhouse was not deleted successfully") {
			Swal.fire({
				position: "center",
				icon: "error",
				title: "Ο συγκεκριμένος softhouse έχει μεταπωλητές και δεν μπορεί να διαγραφεί",
				showConfirmButton: true,
			});
		} else {
			fetchSofthouses();
		}
	});
}

// Initialize

$(document).ready(() => {
	fetchSofthouses();
});
