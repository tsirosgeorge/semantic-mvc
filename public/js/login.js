document.getElementById("loginForm").addEventListener("submit", function (event) {
	event.preventDefault();

	const formData = new FormData(this);

	fetch("/login", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			const messageElement = document.getElementById("message");
			if (data.success) {
				messageElement.textContent = "Login successful. Redirecting to dashboard...";
				setTimeout(() => {
					window.location.href = "/dashboard";
				}, 1000);
			} else {
				messageElement.textContent = data.message;
			}
		})
		.catch((error) => {
			console.error("Error:", error);
		});
});
