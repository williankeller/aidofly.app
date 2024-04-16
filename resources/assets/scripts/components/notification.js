// Import Toast form Bootstrap
import { Toast } from "bootstrap";

const notification = (message, type = "error") => {
    // get all toast elements by the .toast class
    const toastElement = document.querySelector(".toast");

    const iconContainer = toastElement.querySelector(`[data-message="icon"]`);
    const messageContainer = toastElement.querySelector(
        `[data-message="content"]`
    );
    messageContainer.innerHTML = message;

    if (type === "success") {
        iconContainer.classList.add("ti ti-square-rounded-check-filled");
    } else {
        iconContainer.classList.add("ti ti-square-rounded-x-filled");
    }
    toast.show();

    setTimeout(() => {
        toast.hide();
    }, 3000);
};

export { notification };
