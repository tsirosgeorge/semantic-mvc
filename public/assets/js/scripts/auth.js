class AuthService {
	constructor(baseUrl) {
		this.baseUrl = baseUrl;
	}

	async login(email, password) {
		try {
			const response = await fetch(`${this.baseUrl}/login`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify({ email, password }),
			});

			if (!response.ok) {
				$("#message").removeClass("d-none");
				$("#message").html("Αποτυχία σύνδεσης");
				$("#message").addClass("text-danger");
				throw new Error("Login failed");
			}

			$("#message").removeClass("d-none");
			$("#message").html("Επιτυχής σύνδεση");
			$("#message").addClass("text-success");
			const data = await response.json();
			return data;
		} catch (error) {
			console.error("Error:", error);
			throw error;
		}
	}
}

document.addEventListener("DOMContentLoaded", () => {
	const authService = new AuthService("/api");

	const form = document.getElementById("login-form");
	form.addEventListener("submit", async (event) => {
		event.preventDefault();
		const email = document.getElementById("email").value;
		const password = document.getElementById("password").value;

		try {
			const result = await authService.login(email, password);
			console.log("Login successful:", result);
			if (result.success == false) {
				$("#message").removeClass("d-none");
				$("#message").html(result.message);
				$("#message").addClass("text-danger");
			} else {
				$("#message").removeClass("d-none");
				$("#message").html(result.message);
				$("#message").addClass("text-success");
				setTimeout(() => {
					if (result.role == "admin") {
						window.location.href = "/admin/dashboard";
					} else if (result.role == "reseller") {
						window.location.href = "/reseller/dashboard";
					} else if (result.role == "softhouse") {
						window.location.href = "/softhouse/dashboard";
					}
					// window.location.href = "/dashboard";
				}, 1000);
			}

			// Handle successful login (e.g., redirect, display a success message)
		} catch (error) {
			console.error("Login failed:", error);
			// Handle login failure (e.g., display an error message)
		}
	});
});
