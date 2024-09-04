function fetchCustomers() {
	if ($.fn.DataTable.isDataTable("#customersTable")) {
		$("#customersTable").DataTable().destroy();
	}

	$.ajax({
		type: "GET",
		url: apiUrl + "softhouse/customersAuthAndSigned",
		dataType: "json",
		success: function (result) {
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

fetchCustomers();

function makeLineFetchCustomers(customers) {
	var s = "";
	let uniqueResellers = new Set(); // Use a Set to keep track of unique reseller names
	for (var i = 0; i < customers.length; i++) {
		// Add reseller name to the Set
		if (customers[i].rfullname) {
			uniqueResellers.add(customers[i].rfullname);
		}

		s += '<tr style="cursor:pointer;" class="align-middle">';
		s += '<td class="text-nowrap afm-excel">' + (customers[i].afm == null ? "" : customers[i].afm) + "</td>";
		s += '<td class="text-nowrap text-ellipsis">' + truncateString(customers[i].company == null ? "" : customers[i].company, 40) + "</td>";
		s += '<td class="text-nowrap">' + (customers[i].rfullname == null ? "" : customers[i].rfullname) + "</td>";

		if (customers[i].fileurl == null || customers[i].fileurl == "") {
			s += '<td class="text-nowrap"><button onclick="openContractModal(\'' + customers[i].id + "', '" + customers[i].afm + '\')" class="btn btn-primary">Επεξεργασία</button></td>';
		} else {
			if (customers[i].signed == 1) {
				s += `<td class="text-nowrap"><a style="position:relative;" href="${apiUrl}${customers[i].fileurl}" target="_blank"><img height="40" style="cursor:pointer; filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.1))" src="${apiUrl}reseller/assets/img/generic/PDF_file_icon.svg.png"><span style="position:absolute; position: absolute; top: -20px; right: -15px; z-index: 111111;" class="svg-icon svg-icon-1 svg-icon-success">
                                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="#fad7dd"></rect>
                                    <path d="M5.87868 5.87868C6.05025 5.70711 6.32322 5.70711 6.4949 5.87868L12 11.3843L17.5051 5.87868C17.6768 5.70711 17.9497 5.70711 18.1213 5.87868C18.2929 6.05025 18.2929 6.32322 18.1213 6.4949L12.6162 12L18.1213 17.5051C18.2929 17.6768 18.2929 17.9497 18.1213 18.1213C17.9497 18.2929 17.6768 18.2929 17.5051 18.1213L12 12.6162L6.4949 18.1213C6.32322 18.2929 6.05025 18.2929 5.87868 18.1213C5.70711 17.9497 5.70711 17.6768 5.87868 17.5051L11.3843 12L5.87868 6.4949C5.70711 6.32322 5.70711 6.05025 5.87868 5.87868Z" fill="#932338"/>
                                  </svg>
                                </span></a></td>`;
			} else {
				s += `<td class="text-nowrap"><a style="position:relative;" href="${apiUrl}${customers[i].fileurl}" target="_blank"><img height="40" style="cursor:pointer; filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.1))" src="${apiUrl}reseller/assets/img/generic/PDF_file_icon.svg.png"><span style="position:absolute; position: absolute; top: -20px; right: -15px; z-index: 111111;" class="svg-icon svg-icon-1 svg-icon-success">
                                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="#fad7dd"></rect>
                                    <path d="M5.87868 5.87868C6.05025 5.70711 6.32322 5.70711 6.4949 5.87868L12 11.3843L17.5051 5.87868C17.6768 5.70711 17.9497 5.70711 18.1213 5.87868C18.2929 6.05025 18.2929 6.32322 18.1213 6.4949L12.6162 12L18.1213 17.5051C18.2929 17.6768 18.2929 17.9497 18.1213 18.1213C17.9497 18.2929 17.6768 18.2929 17.5051 18.1213L12 12.6162L6.4949 18.1213C6.32322 18.2929 6.05025 18.2929 5.87868 18.1213C5.70711 17.9497 5.70711 17.6768 5.87868 17.5051L11.3843 12L5.87868 6.4949C5.70711 6.32322 5.70711 6.05025 5.87868 5.87868Z" fill="#932338"/>
                                  </svg>
                                </span></a></td>`;
			}
		}
		if (customers[i].contract == 0) {
			s += `<td class="text-nowrap"><button onclick="setContract(${customers[i].id}, 1)" class="btn btn-primary me-3">Έγκριση</button><button onclick="setContract(${customers[i].id}, 0)" class="btn btn-danger">Ακύρωση</button></td>`;
		} else {
			s += '<td class="text-nowrap"><span class="badge badge-soft-success">Έγινε έγκριση</span></td>';
		}

		s += "</tr>";
	}
	$("#customersBody").html(s);
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

function openContractModal($id, $afm) {
	$("#edit-contract-modal").modal("show");
	$("#customer-afm").val($afm);
	$("#customer-id").val($id);
}

function setContract($id, $set) {
	$.ajax({
		type: "POST",
		url: apiUrl + "ajaxSrv.php?op=setContract&id=" + $id,
		data: { set: $set },
		dataType: "json",
		success: function (result) {
			fetchCustomers();
		},
		error: function (xhr) {
			alert("Request Status: " + xhr.status + " Status Text: " + xhr.statusText + " " + xhr.responseText);
		},
	});
}
