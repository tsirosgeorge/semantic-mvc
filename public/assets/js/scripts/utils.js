function getDate(dateTime) {
	const date = new Date(dateTime);
	const day = date.getDate().toString().padStart(2, "0");
	const month = (date.getMonth() + 1).toString().padStart(2, "0");
	const year = date.getFullYear().toString();
	return `${day}/${month}/${year}`;
}

function getTime(dateTime) {
	const date = new Date(dateTime);
	return date.toLocaleTimeString();
}

// Helper functions
function truncateString(str, maxLength) {
	return str.length > maxLength ? str.substring(0, maxLength) + "..." : str;
}

function formatDateWithoutTime(dateString) {
	if (!dateString) return "";
	const date = new Date(dateString);
	return date.toLocaleDateString(); // Adjust as needed
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
	timeOut: "5000",
	extendedTimeOut: "1000",
	showEasing: "swing",
	hideEasing: "linear",
	showMethod: "fadeIn",
	hideMethod: "fadeOut",
};

function formatDate(date) {
	var day = date.getDate();
	var month = date.getMonth() + 1;
	var year = date.getFullYear();
	if (day < 10) {
		day = "0" + day;
	}
	if (month < 10) {
		month = "0" + month;
	}
	return day + "/" + month + "/" + year;
}
