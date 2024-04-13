import { initBootstrap, setNonce } from "./globalSettings";
import { setupFieldValidation } from "./formValidation";
import { setupButtonActions } from "./buttonActions";
import api from "./api";
import { notification } from "./components/notification";
import helper from "./helpers/redirect";

jQuery(($) => {
    initBootstrap();
    setNonce();
    setupButtonActions();
    setupFieldValidation();
});

// Handle form submission
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

$("#signup").on("submit", function (event) {
    event.preventDefault();

    let data = new FormData(this);
    data.append("locale", document.documentElement.lang);

    try {
        api.post("auth/register", data).then((response) => {
            if (!response.ok) {
                // If the HTTP status code is not successful, throw the response
                const errorResponse = response.json(); // Parse the JSON to get the detailed error
                throw new Error(
                    errorResponse.message || "Unknown error occurred"
                );
            }
            if (response.data.token) {
                const token = response.data.token;

                // Save the token to local storage and cookie to be used for future api requests
                localStorage.setItem("token", token);

                // Set token to cookie
                document.cookie = `token=${token};path=/`;

                // Redirect user to the app or admin dashboard
                let path = "/";

                // If the user was redirected to the login page
                let redirectPath = helper.getRedirectPath();
                if (redirectPath) {
                    // Redirect the user to the path they were trying to access
                    path = redirectPath;

                    // Remove the redirect path from local storage
                    helper.clearRedirectPath();
                }

                // Redirect the user to the path
                window.location.href = path;
            }
        });
    } catch (error) {
        // If it's a network error or another issue, use a default message
        let msg =
            error.message || "Something went wrong! Please try again later!";
        notification(msg);
    }
});
