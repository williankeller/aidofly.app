import api from "./api";
import { notification } from "./components/notification";
import helper from "./helpers/redirect";

$("#signup").on("submit", function (event) {
    event.preventDefault();

    let data = new FormData(this);
    data.append("locale", document.documentElement.lang);

    try {
        api.post("auth/register", data).then((response) => {
            if (!response.ok) {
                // Parse the JSON to get the detailed error asynchronously
                response.json().then((errorResponse) => {
                    throw new Error(
                        errorResponse.message || "Unknown error occurred"
                    );
                });
                return;
            }

            console.log(response);

            // Parse the JSON of the response
            response.json().then((data) => {
                console.log(data.xtoken, data, data.message);
                const token = data.xtoken;

                // Set token to cookie
                document.cookie = `x-token=${token}; Secure; HttpOnly; SameSite=Strict; path=/`;

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
                //window.location.href = path;
            });
        });
    } catch (error) {
        // If it's a network error or another issue, use a default message
        let msg =
            error.message || "Something went wrong! Please try again later!";
        notification(msg);
    }
});
