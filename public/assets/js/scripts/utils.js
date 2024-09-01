function jsonEncode(data) {
	const decodedData = data
		.replace(/&quot;/g, '"')
		.replace(/&amp;/g, "&")
		.replace(/\n/g, "")
		.replace(/&lt;/g, "<")
		.replace(/&gt;/g, ">")
		.replace(/<br>/g, " ");

	const contactsData = JSON.parse(JSON.stringify(decodedData));
	return JSON.parse(contactsData);
}

function initiateCustomTable(customTable, data) {
	let encodedData = JSON.stringify(data);
	if (encodedData) {
		allData = encodedData; // Save the contacts data globally
		customTable(encodedData);
	}
}

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

/**
 * Parses a JSON string and decodes any nested JSON strings.
 *
 * @param {string} jsonString - The JSON string to parse.
 * @returns {Object|Array|null} - The parsed JavaScript object or array, or null if parsing fails.
 */
function parseJSONWithNested(jsonString) {
	let data;

	try {
		// Parse the main JSON string
		data = JSON.parse(jsonString);
	} catch (error) {
		console.error("Error parsing main JSON:", error);
		return null;
	}

	// Recursively parse nested JSON strings
	function parseNestedJSON(obj) {
		if (Array.isArray(obj)) {
			obj.forEach((item) => parseNestedJSON(item));
		} else if (obj && typeof obj === "object") {
			Object.keys(obj).forEach((key) => {
				if (typeof obj[key] === "string") {
					try {
						// Attempt to parse the string as JSON
						const parsed = JSON.parse(obj[key]);
						// If parsing succeeds, replace the string with the parsed object
						if (parsed && typeof parsed === "object") {
							obj[key] = parsed;
						}
					} catch (e) {
						// If parsing fails, leave the original string
						// Optionally log the error
					}
				} else if (typeof obj[key] === "object" && obj[key] !== null) {
					// Recursively parse nested objects and arrays
					parseNestedJSON(obj[key]);
				}
			});
		}
	}

	parseNestedJSON(data);

	return data;
}
