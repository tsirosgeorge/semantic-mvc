class AuthService {
	constructor(baseUrl) {
		this.baseUrl = baseUrl;
	}

	async login(username, password) {
		try {
			const response = await fetch(`${this.baseUrl}/login`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify({ username, password }),
			});

			if (!response.ok) {
				throw new Error("Login failed");
			}

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
		const username = document.getElementById("username").value;
		const password = document.getElementById("password").value;

		try {
			const result = await authService.login(username, password);

			window.location.href = "/dashboard";

			// Handle successful login (e.g., redirect, display a success message)
		} catch (error) {
			console.error("Login failed:", error);
			// Handle login failure (e.g., display an error message)
		}
	});
});
