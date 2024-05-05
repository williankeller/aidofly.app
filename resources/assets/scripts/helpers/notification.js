const notification = (message, type = "error") => {
    // get all toast elements by the .toast class
    const toastElement = document.querySelector(".toast");

    const iconContainer = toastElement.querySelector(`[data-message="icon"]`);
    const messageContainer = toastElement.querySelector(
        `[data-message="content"]`
    );
    messageContainer.innerHTML = message;

    if (type == "success") {
        iconContainer.classList.remove("ti-square-rounded-x-filled");
        iconContainer.classList.add("ti-square-rounded-check-filled");
    } else {
        iconContainer.classList.remove("ti-square-rounded-check-filled");
        iconContainer.classList.add("ti-square-rounded-x-filled");
    }
    toastElement.classList.add("show");

    setTimeout(() => {
        toastElement.classList.remove("show");
    }, 5000);
};

export { notification };
