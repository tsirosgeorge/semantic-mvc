function fetchSeminars() {
	$.ajax({
		type: "GET",
		url: "/seminars/fetchSeminars",
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

function formatDateWithoutTime(dateTimeString) {
	var dateTime = new Date(dateTimeString);
	var year = dateTime.getFullYear();
	var month = ("0" + (dateTime.getMonth() + 1)).slice(-2);
	var day = ("0" + dateTime.getDate()).slice(-2);
	var formattedDate = day + "-" + month + "-" + year;
	return formattedDate;
}

function makeLineFetchSeminars(seminars) {
	s = "";
	for (var i = 0; i < seminars.length; i++) {
		s += '<tr style="cursor:pointer;" class="align-middle">';
		s += '<td class="text-nowrap" style="width:5%">' + formatDateWithoutTime(seminars[i].date == null ? "" : seminars[i].date) + "</td>";
		s += '<td class="text-nowrap" style="width:5%">' + (seminars[i].starttime == null ? "" : seminars[i].starttime) + "</td>";
		s += '<td class="text-nowrap" style="width:5%">' + (seminars[i].endtime == null ? "" : seminars[i].endtime) + "</td>";
		s += '<td class="text-nowrap" style="width:40%">' + (seminars[i].descr == null ? "" : seminars[i].descr) + "</td>";
		s += '<td class="text-nowrap" style="width:5%"><a href="#" onclick="copyAfm(\'' + seminars[i].link + '\')"><i class="fa-solid fa-copy"></i></a></td>';

		s += "</tr>";
	}
	$("#seminarBody").html(s);
}

function copyAfm($link) {
	var tempInput = document.createElement("input");
	document.body.appendChild(tempInput);
	tempInput.value = $link;
	tempInput.select();
	document.execCommand("copy");
	document.body.removeChild(tempInput);
	toastr.success("Ο σύνδεσμος " + $link + " αντιγράφτηκε με επιτυχία");
	// window.location.href = $link;
}

toastr.options = {
	closeButton: false,
	debug: false,
	newestOnTop: true,
	progressBar: true,
	positionClass: "toast-top-right",
	preventDuplicates: false,
	onclick: null,
	showDuration: "300",
	hideDuration: "1000",
	timeOut: "2000",
	extendedTimeOut: "1000",
	showEasing: "swing",
	hideEasing: "linear",
	showMethod: "fadeIn",
	hideMethod: "fadeOut",
};
