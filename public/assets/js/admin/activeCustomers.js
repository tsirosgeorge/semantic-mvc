// Utility Functions

function handleAjaxError(xhr) {
	alert(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
}

// AJAX Request Wrapper

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

// Fetch and Render Customers

function fetchCustomers() {
	if ($.fn.DataTable.isDataTable("#customersTable")) {
		$("#customersTable").DataTable().destroy();
	}

	ajaxRequest("GET", apiUrl + "customers/active", {}, (result) => {
		const customers = result || [];
		if (customers.length > 0) {
			renderCustomers(customers);
		} else {
			$("#customersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
		}
	});
}

function renderCustomers(customers) {
	let uniqueResellers = new Set(); // Use a Set to keep track of unique reseller names
	const rows = customers
		.map((customer) => {
			// Add reseller name to the Set
			if (customer.rfullname) {
				uniqueResellers.add(customer.rfullname);
			}
			const contractLink = customer.fileurl
				? `<a style="position:relative;" href="https://invoicing4all.com/${customer.fileurl}" target="_blank">
                <img height="40" style="cursor:pointer; filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.1))" src="https://invoicing4all.com/reseller/assets/img/generic/PDF_file_icon.svg.png">
                <span style="position:absolute; top: -20px; right: -15px; z-index: 111111;" class="svg-icon svg-icon-1 svg-icon-success">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="${customer.contract == 1 ? "#50cd89" : "#fad7dd"}"></rect>
                    <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="${customer.contract == 1 ? "#50cd89" : "#932338"}"></path>
                  </svg>
                </span>
              </a><span style="opacity:0; font-size:0.1px">https://invoicing4all.com/${customer.fileurl}</span>`
				: `<button onclick="openContractModal('${customer.id}', '${customer.afm}')" class="btn btn-primary">Επεξεργασία</button> /
               <button onclick="createContract('${customer.id}', '${customer.afm}', '${customer.address}', '${customer.city}')" class="btn btn-info">Δημιουργεία σύμβασης</button>`;

			const activateButton = customer.activate == 0 ? `<button onclick="setActivate(${customer.id})" class="btn btn-success">Ενεργοποίηση</button>` : '<span class="badge badge-subtle-success">Ενεργοποιημένος</span>';

			return `
            <tr style="cursor:pointer;" class="align-middle">
                <td class="text-nowrap afm-excel">${customer.afm || ""}</td>
                <td class="text-nowrap text-ellipsis">${truncateString(customer.company || "", 40)}</td>
                <td class="text-nowrap">${customer.rfullname || ""}</td>
                <td class="text-nowrap date-excel">${formatDateWithoutTime(customer.created_at)}</td>
                <td class="text-nowrap">${contractLink}</td>
                <td>${activateButton}</td>
            </tr>`;
		})
		.join("");

	$("#customersBody").html(rows);
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

// Customer Actions

function openContractModal(id, afm) {
	$("#edit-contract-modal").modal("show");
	$("#customer-afm").val(afm);
	$("#customer-id").val(id);
}

function createContract(id, afm, address, city) {
	const currentDate = new Date();
	const params = new URLSearchParams({
		companyName: id,
		companyAfm: afm,
		companyCity: city,
		companyAddress: address,
		companyDate: formatDate(currentDate),
	});
	window.location.href = `https://invoicing4all.com/panel/eskap/file.html?${params.toString()}`;
}

function setActivate(id) {
	ajaxRequest("POST", "https://invoicing4all.com/panel/ajaxSrv.php?op=activateCustomer", { id }, fetchCustomers);
}

function sendContract(id) {
	ajaxRequest("POST", `https://invoicing4all.com/panel/ajaxSrv.php?op=sendContract&id=${id}`, {}, fetchCustomers);
}

function fetchCustomersByResellers() {
	const resellerId = $("#resellerSelect").val();
	ajaxRequest("POST", "https://invoicing4all.com/panel/ajaxSrv.php?op=fetchCustomersByResellers&type=active", { resellerid: resellerId }, (result) => {
		renderCustomers(result.data.resellers);
	});
}

// Initialize

$(document).ready(() => {
	fetchCustomers();
});
