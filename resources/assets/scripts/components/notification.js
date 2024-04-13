// Import Toast form Bootstrap
import { Toast } from "bootstrap";

const notification = (message, type = "error") => {
    const toastElement = document.querySelector(
        `[data-message="notification"]`
    );
    const iconContainer = toastElement.querySelector(`[data-message="icon"]`);
    const messageContainer = toastElement.querySelector(
        `[data-message="content"]`
    );

    const toast = new Toast(toastElement);

    messageContainer.innerHTML = message;

    if (type === "success") {
        iconContainer.innerHTML = `<i class="ti ti-square-rounded-check-filled"></i>`;
    } else {
        iconContainer.innerHTML = `<i class="ti ti-square-rounded-x-filled"></i>`;
    }

    toast.show();
};

export { notification };
