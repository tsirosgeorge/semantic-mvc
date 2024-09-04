function handleAjaxError(xhr) {
	alert(`Request Status: ${xhr.status} Status Text: ${xhr.statusText} ${xhr.responseText}`);
}

// AJAX Request Function
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

// Fetch Customers
function fetchCustomers() {
	if ($.fn.DataTable.isDataTable("#customersTable")) {
		$("#customersTable").DataTable().destroy();
	}

	ajaxRequest("GET", apiUrl + "admin/allButNotAactive", {}, (result) => {
		if (result.length > 0) {
			console.log(result);
			renderCustomers(result);
		} else {
			$("#customersTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
		}
	});
}

// Render Customer Rows
function renderCustomers(customers) {
	let uniqueResellers = new Set(); // Use a Set to keep track of unique reseller names
	const rows = customers
		.map((customer) => {
			if (customer.rfullname) {
				uniqueResellers.add(customer.rfullname);
			}
			const afm = customer.afm || "";
			const company = truncateString(customer.company || "", 40);
			const rfullname = customer.rfullname || "";
			const createdAt = customer.created_at ? formatDateWithoutTime(customer.created_at) : "";
			const email = customer.email || "";
			const fileUrl = customer.fileurl ? `https://invoicing4all.com/${customer.fileurl}` : "";
			const isContractSigned = customer.contract === 1;

			const contractButton = customer.fileurl ? generateContractButton(fileUrl, isContractSigned) : generateEditButtons(customer);
			const eskapStatus = customer.eskap === 0 ? generateRegisterEskapButton(customer) : '<span class="badge badge-subtle-success">Εγγεγραμμένος</span>';
			const activateButton = customer.activate === 0 ? generateActivateButton(customer.id) : '<span class="badge badge-subtle-success">Ενεργοποιημένος</span>';

			return `
            <tr style="cursor:pointer;" class="align-middle">
                <td class="text-nowrap afm-excel text-start">${afm}</td>
                <td class="text-nowrap text-ellipsis">${company}</td>
                <td class="text-nowrap">${rfullname}</td>
                <td class="text-nowrap date-excel">${createdAt}</td>
                <td class="text-nowrap">${contractButton}</td>
                <td class="d-flex align-items-center">${activateButton} | ${eskapStatus}</td>
                <td class="text-nowrap email-excel">${email}</td>
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

function generateContractButton(fileUrl, isContractSigned) {
	const iconColor = isContractSigned ? "#50cd89" : "#932338";
	return `
        <a style="position:relative;" href="${fileUrl}" target="_blank">
            <img height="40" style="cursor:pointer; filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.1))" src="https://invoicing4all.com/reseller/assets/img/generic/PDF_file_icon.svg.png">
            <span style="position:absolute; top: -20px; right: -15px; z-index: 111111;" class="svg-icon svg-icon-1 svg-icon-success">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="${iconColor}"></rect>
                    <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="#50cd89"></path>
                </svg>
            </span>
        </a>`;
}

function generateEditButtons(customer) {
	return `
        <button onclick="openContractModal('${customer.id}', '${customer.afm}', '${customer.email}')" class="btn btn-primary">Επεξεργασία</button>
        / 
        <button onclick="createContract('${customer.id}', '${customer.afm}', '${customer.address}', '${customer.city}')" class="btn btn-info">Δημιουργεία σύμβασης</button>`;
}

function generateRegisterEskapButton(customer) {
	return `<button onclick="registerEskap('${customer.id}', '${customer.company}', '${customer.email}', '${customer.password}', '${customer.fullname}', '${customer.afm}', '${customer.address}', '${customer.postalcode}', '${customer.city}')" class="btn btn-primary btn-sm">Εγγραφή ESKAP</button>`;
}

function generateActivateButton(id) {
	return `<button onclick="setActivate(${id})" class="btn btn-success btn-sm">Ενεργοποίηση</button>`;
}

// Modal Handling
function openContractModal(id, afm) {
	$("#edit-contract-modal").modal("show");
	$("#customer-afm").val(afm);
	$("#customer-id").val(id);
}

// Contract Creation
function createContract(id, afm, address, city, email) {
	const ar = {
		companyName: afm,
		companyAfm: afm,
		companyCity: city,
		companyAddress: address,
		companyEmail: email,
		companyDate: formatDate(new Date()),
	};

	const queryString = new URLSearchParams(ar).toString();
	window.location.href = `https://invoicing4all.com/panel/eskap/file.html?${queryString}`;
}

// ESKAP Registration
function registerEskap(id, company, email, password, fullname, afm, address, postalcode, city) {
	const ar = {
		id,
		email,
		pass: password,
		company,
		firstname: fullname.split(" ")[0] || "",
		lastname: fullname.split(" ")[1] || "",
		afm,
		address,
		postcode: postalcode,
		city,
	};

	console.log(ar);
	return;

	ajaxRequest("POST", "https://invoicing4all.com/panel/ajaxSrv.php?op=registerEskap", ar, (result) => {
		try {
			const message = JSON.parse(result.message);
			if (message.error === "A customer with this email and afm already exists") {
				Swal.fire({
					position: "center",
					icon: "error",
					title: "Ο πελάτης υπάρχει ήδη",
					showConfirmButton: false,
					timer: 1500,
				});
			} else if (message.success) {
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
	});
}
// Activate Customer
function setActivate(id) {
	ajaxRequest("PUT", apiUrl + "admin/activate/" + id, {}, fetchCustomers);
}

// Initial Fetch
fetchCustomers();
