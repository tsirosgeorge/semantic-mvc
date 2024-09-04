// Fetch customers with specific contract status
function fetchCustomers() {
	if ($.fn.DataTable.isDataTable("#customersTable")) {
		$("#customersTable").DataTable().destroy();
	}
	$.ajax({
		type: "GET",
		url: apiUrl + "admin/authorized",
		dataType: "json",
		success: function (result) {
			if (result.length > 0) {
				renderCustomerRows(result);
			} else {
				displayNoResultsMessage();
			}
		},
		error: function (xhr) {
			handleAjaxError(xhr);
		},
	});
}

fetchCustomers();

// Render customer rows in the table
function renderCustomerRows(customers) {
	let uniqueResellers = new Set(); // Use a Set to keep track of unique reseller names
	let rows = customers
		.map((customer) => {
			// Add reseller name to the Set
			if (customer.rfullname) {
				uniqueResellers.add(customer.rfullname);
			}
			return `
            <tr style="cursor:pointer;" class="align-middle">
                <td class="text-nowrap afm-excel text-start">${customer.afm || ""}</td>
                <td class="text-nowrap text-ellipsis">${truncateString(customer.company || "", 40)}</td>
                <td class="text-nowrap">${customer.rfullname || ""}</td>
                <td class="text-nowrap date-excel">${formatDateWithoutTime(customer.created_at || "")}</td>
                <td class="text-nowrap">
                    ${generateActionButtons(customer)}
                </td>
            </tr>
        `;
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

// Generate action buttons based on customer data
function generateActionButtons(customer) {
	if (!customer.fileurl) {
		return `
            <button onclick="openContractModal('${customer.id}', '${customer.afm}')" class="btn btn-primary btn-sm">Επεξεργασία</button>
            <button onclick="createContract('${customer.afm}', '${customer.company}', '${customer.address}', '${customer.city}', '${customer.email}')" class="btn btn-info btn-sm">Νέα σύμβαση</button>
        `;
	}

	let iconUrl = "https://invoicing4all.com/reseller/assets/img/generic/PDF_file_icon.svg.png";
	let statusIcon = customer.contract == 1 ? generateStatusIcon("#50cd89") : generateStatusIcon("#932338");
	return `
        <a style="position:relative;" href="https://invoicing4all.com/${customer.fileurl}" target="_blank">
            <img height="40" style="cursor:pointer; filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.1))" src="${iconUrl}">
            ${statusIcon}
        </a>
    `;
}

// Generate status icon based on color
function generateStatusIcon(color) {
	return `
        <span style="position:absolute; top:-20px; right:-15px; z-index:111111;" class="svg-icon svg-icon-1 svg-icon-success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="${color}"></rect>
                <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="${color}"></path>
            </svg>
        </span>
    `;
}

// Open the contract modal
function openContractModal(id, afm) {
	$("#edit-contract-modal").modal("show");
	$("#customer-afm").val(afm);
	$("#customer-id").val(id);
}

// Create a new contract
function createContract(afm, fullname, address, city, email) {
	const currentDate = formatDate(new Date());
	const queryString = new URLSearchParams({
		companyName: fullname,
		companyAfm: afm,
		companyCity: city,
		companyAddress: address,
		companyEmail: email,
		companyDate: currentDate,
	}).toString();

	window.open(`https://invoicing4all.com/panel/contract/index.html?${queryString}`, "_blank");
}

// Display no results message
function displayNoResultsMessage() {
	$("#customersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
}

// Handle AJAX errors
function handleAjaxError(xhr) {
	alert(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
}
