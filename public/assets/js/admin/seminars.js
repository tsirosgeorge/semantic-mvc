function fetchSeminars() {
	if ($.fn.DataTable.isDataTable("#seminarsTable")) {
		$("#seminarsTable").DataTable().destroy();
	}

	$.ajax({
		type: "GET",
		url: apiUrl + "admin/fetchSeminars",
		dataType: "json",
		success: function (result) {
			if (result.length > 0) {
				makeLineFetchSeminars(result);
			} else {
				$("#seminarsTable").html("<br><center>Δεν βρέθηκαν αποτελέσματα</center>");
			}
		},

		error: function (xhr) {
			alert("Request Status: " + xhr.status + " Status Text: " + xhr.statusText + " " + xhr.responseText);
		},
	});
}

fetchSeminars();

function makeLineFetchSeminars(seminars) {
	s = "";
	for (var i = 0; i < seminars.length; i++) {
		s += '<tr style="cursor:pointer;" class="align-middle">';
		s += '<td class="text-nowrap">' + (seminars[i].date == null ? "" : seminars[i].date) + "</td>";
		s += '<td class="text-nowrap">' + (seminars[i].starttime == null ? "" : seminars[i].starttime) + "</td>";
		s += '<td class="text-nowrap">' + (seminars[i].endtime == null ? "" : seminars[i].endtime) + "</td>";
		s += '<td class="text-nowrap">' + (seminars[i].descr == null ? "" : seminars[i].descr) + "</td>";
		s += '<td class="text-nowrap"><a target="_blank" href=' + seminars[i].link + ">" + (seminars[i].link == null ? "" : seminars[i].link) + "</a></td>";
		s += '<td class="text-nowrap"><button class="btn btn-danger" onclick="deleteSeminar(' + seminars[i].id + ')">Διαγραφή</button></td>';

		s += "</tr>";
	}
	$("#seminarBody").html(s);

	var table = $("#seminarsTable").DataTable({
		order: [],
		language: {
			url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Greek.json",
		},
	});
}

function deleteSeminar($id) {
	$.ajax({
		type: "DELETE",
		url: apiUrl + "admin/deleteSeminar/" + $id,
		dataType: "json",
		success: function (result) {
			fetchSeminars();
		},

		error: function (xhr) {
			alert("Request Status: " + xhr.status + " Status Text: " + xhr.statusText + " " + xhr.responseText);
		},
	});
}
