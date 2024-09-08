let contactsTableInitiate;
let membersTableInitiate;
let b2bInterestTableInitiate;
function contactsTable(contacts, filterStatus = "") {
	const tbody = document.querySelector("#contactsTable tbody");
	let s = "";

	// Filter contacts based on the filterStatus
	if (filterStatus === "0" || filterStatus === "") {
		contacts = contacts.contacts; // No filter applied, show all contacts
	} else {
		contacts = contacts.contacts.filter((contact) => contact.isqualified == filterStatus);
	}

	for (let i = 0; i < contacts.length; i++) {
		let trColor = "background-color:white;";
		let selectValue = "";
		let displayText = "";
		switch (contacts[i].isqualified) {
			case 1:
				trColor = "background-color:#D1E7DD";
				selectValue = "1";
				displayText = "Qualified";
				break;
			case 2:
				trColor = "background-color:#FEF3CD";
				selectValue = "2";
				displayText = "Unqualified";
				break;
			case 3:
				trColor = "background-color:#F8D7DA";
				selectValue = "3";
				displayText = "No Answer";
				break;
			default:
				trColor = "";
				displayText = "";
				break;
		}

		s += `
            <tr style="${trColor}">
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${getDate(contacts[i].currentstamp)}</td>
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${contacts[i].fullname}<br><span class='text-muted'>${contacts[i].email}<br>${contacts[i].phone}</span></td>										
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${contacts[i].descr}</td>
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${contacts[i].fromwhere == null ? "-" : contacts[i].fromwhere}</td>
                <td class="fw-bold align-middle w-auto white-space-nowrap text-end">                        
                    <select class="form-select qualifiedSelect" aria-label="Default select example">                            
                        <option value="1" ${selectValue === "1" ? "selected" : ""}>Qualified</option>
                        <option value="2" ${selectValue === "2" ? "selected" : ""}>Unqualified</option>
                        <option value="3" ${selectValue === "3" ? "selected" : ""}>No Answer</option>
                    </select>
                </td>
                <td class="align-middle w-auto white-space-nowrap text-end">
                    <div class="dropdown">
                        <button class="btn btn-icon btn-icon-only btn-soft-secondary bg-white" type="button" id="dropdownTable${contacts[i].id}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fas fa-ellipsis-h fs--1"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end border py-0 rounded-3" aria-labelledby="dropdownTable${contacts[i].id}" style="min-width: auto;">
                            <div class="bg-white py-2 rounded-3">									
                                <a class="dropdown-item text-success" href="#"><span class="bi-telephone text-success fs-7"></span></a>
                                <a class="dropdown-item text-danger" href="#"><span class="bi-trash text-danger fs-7"></span></a>									
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
	}
	tbody.innerHTML = s;

	if ($.fn.DataTable.isDataTable("#contactsTable")) {
		contactsTableInitiate.destroy();
	}

	// Reinitialize DataTable if needed
	let contactsTableInitiate = new DataTable("#contactsTable", {
		responsive: true,
		fixedHeader: true,
		pageLength: 50,
		buttons: ["copy", "excel"],
		bottomEnd: "paging",
		language: {
			url: "//cdn.datatables.net/plug-ins/2.1.5/i18n/el.json",
		},
	});
}

function membersTable(members, filterStatus = "") {
	const tbody = document.querySelector("#membersTable tbody");
	let s = "";

	// Filter contacts based on the filterStatus
	if (filterStatus === "0" || filterStatus === "") {
		members = members.members; // No filter applied, show all members
	} else {
		members = members.members.filter((contact) => contact.isqualified == filterStatus);
	}

	for (let i = 0; i < members.length; i++) {
		let trColor = "background-color:white;";
		let selectValue = "";
		let displayText = "";
		switch (members[i].isqualified) {
			case 1:
				trColor = "background-color:#D1E7DD";
				selectValue = "1";
				displayText = "Qualified";
				break;
			case 2:
				trColor = "background-color:#FEF3CD";
				selectValue = "2";
				displayText = "Unqualified";
				break;
			case 3:
				trColor = "background-color:#F8D7DA";
				selectValue = "3";
				displayText = "No Answer";
				break;
			default:
				trColor = "";
				displayText = "";
				break;
		}

		s += `
            <tr style="${trColor}">
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${getDate(members[i].recstamp)}</td>
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${members[i].fullname}<br><span class='text-muted'>${members[i].email}<br>${members[i].phone} - ${members[i].city}</span></td>										                
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${members[i].fromwhere == null ? "-" : members[i].fromwhere}</td>
                <td class="fw-bold align-middle w-auto white-space-nowrap text-end">                        
                    <select class="form-select qualifiedSelect" aria-label="Default select example">                            
                        <option value="1" ${selectValue === "1" ? "selected" : ""}>Qualified</option>
                        <option value="2" ${selectValue === "2" ? "selected" : ""}>Unqualified</option>
                        <option value="3" ${selectValue === "3" ? "selected" : ""}>No Answer</option>
                    </select>
                </td>
                <td class="align-middle w-auto white-space-nowrap text-end">
                    <div class="dropdown">
                        <button class="btn btn-icon btn-icon-only btn-soft-secondary bg-white" type="button" id="dropdownTable${members[i].id}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fas fa-ellipsis-h fs--1"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end border py-0 rounded-3" aria-labelledby="dropdownTable${members[i].id}" style="min-width: auto;">
                            <div class="bg-white py-2 rounded-3">									
                                <a class="dropdown-item text-success" href="#"><span class="bi-chat-dots text-success fs-7"></span></a>
                                <a class="dropdown-item text-danger" href="#"><span class="bi-trash text-danger fs-7"></span></a>									
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
	}
	tbody.innerHTML = s;

	if ($.fn.DataTable.isDataTable("#membersTable")) {
		membersTableInitiate.destroy();
	}

	// Reinitialize DataTable if needed
	let membersTableInitiate = new DataTable("#membersTable", {
		fixedHeader: true,
		responsive: true,
		pageLength: 50,
		buttons: ["copy", "excel"],
		bottomEnd: "paging",
		language: {
			url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json", // Updated URL
		},
	});
}

function b2bInterestTable(b2binterest, filterStatus = "") {
	const tbody = document.querySelector("#b2bInterestTable tbody");
	let s = "";

	if (filterStatus === "0" || filterStatus === "") {
		b2binterest = b2binterest; // No filter applied, show all members
	}
	for (let i = 0; i < b2binterest.length; i++) {
		s += `
            <tr>
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${getDate(b2binterest[i].created_at)}</td>
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">${b2binterest[i].email}</td>										                                								                
                <td class="fw-bold align-middle w-auto white-space-nowrap text-start">
            		${b2binterest[i].has_codes == 1 ? '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked></div>' : '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckUnchecked"></div>'}
				</td>  
                <td class="align-middle w-auto white-space-nowrap text-end">
                    <div class="dropdown">
                        <button class="btn btn-icon btn-icon-only btn-soft-secondary bg-white" type="button" id="dropdownTable${b2binterest[i].id}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fas fa-ellipsis-h fs--1"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end border py-0 rounded-3" aria-labelledby="dropdownTable${b2binterest[i].id}" style="min-width: auto;">
                            <div class="bg-white py-2 rounded-3">									
                                <a class="dropdown-item text-success" href="#"><span class="bi-chat-dots text-success fs-7"></span></a>
                                <a class="dropdown-item text-danger" href="#"><span class="bi-trash text-danger fs-7"></span></a>									
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        `;
	}
	tbody.innerHTML = s;

	if ($.fn.DataTable.isDataTable("#b2bInterestTable")) {
		b2bInterestTableInitiate.destroy();
	}

	// Reinitialize DataTable if needed
	let b2bInterestTableInitiate = new DataTable("#b2bInterestTable", {
		fixedHeader: true,
		responsive: true,
		pageLength: 50,
		buttons: ["copy", "excel"],
		bottomEnd: "paging",
		language: {
			url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json", // Updated URL
		},
	});
}

if ($("#membersTable").length) {
	document.getElementById("filterMembersStatus").addEventListener("change", function () {
		const selectedValue = this.value;
		membersTable(allData, selectedValue); // Update the table with the selected filter
	});
}

if ($("#contactsTable").length) {
	document.getElementById("filterContactsStatus").addEventListener("change", function () {
		const selectedValue = this.value;
		contactsTable(allData, selectedValue); // Update the table with the selected filter
	});
}
