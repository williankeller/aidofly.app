import api from "./api";
import { notification } from "./components/notification";
import helper from "./helpers/redirect";

$("#signin").on("submit", function (event) {
    event.preventDefault();

    let data = new FormData(this);
    this.querySelectorAll('input[type="checkbox"]').forEach((element) => {
        data.append(element.name, element.checked ? "1" : "0");
    });

    data.append("locale", document.documentElement.lang);

    try {
        api.post("auth/authorize", data).then((response) => {
            if (!response.ok) {
                // If the HTTP status code is not successful, throw the response
                const errorResponse = response.json(); // Parse the JSON to get the detailed error
                throw new Error(
                    errorResponse.message || "Unknown error occurred"
                );
            }
        });
    } catch (error) {
        // If it's a network error or another issue, use a default message
        let msg =
            error.message || "Something went wrong! Please try again later!";
        notification(msg);
    }
});
