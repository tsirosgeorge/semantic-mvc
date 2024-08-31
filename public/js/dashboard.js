document.addEventListener("DOMContentLoaded", function () {
	fetch("/dashboard/data")
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				const welcomeMessage = document.getElementById("welcomeMessage");
				welcomeMessage.textContent = `Welcome, ${data.data.username}`;
			} else {
				window.location.href = "/";
			}
		})
		.catch((error) => {
			console.error("Error:", error);
		});
});

// Logout
document.getElementById("logoutButton").addEventListener("click", function (event) {
	event.preventDefault();

	fetch("/logout")
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				window.location.href = "/";
			}
		})
		.catch((error) => {
			console.error("Error:", error);
		});
});
