
/**
 * FormElement class to handle form elements.
 * Usage: <form data-element="form" x-ref="form"></form>
 */
export class FormElement {
    constructor(form) {
        this.form = form;
        this.timer = 0;

        this.setupListeners();
        this.setupMutationObserver();
        this.checkSubmitable();
    }

    /**
     * Sets up event listeners for input and submit events.
     */
    setupListeners() {
        this.form.addEventListener("input", () => this.callback());
        this.form.addEventListener("submit", (event) =>
            this.handleSubmission(event)
        );
    }

    /**
     * Sets up a mutation observer to track changes in the form element.
     */
    setupMutationObserver() {
        this.observer = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                if (mutation.type === "childList") {
                    this.callback();
                }
            }
        });

        // Configuration of the observer
        const config = { attributes: false, childList: true, subtree: true };

        // Start observing the target node for configured mutations
        this.observer.observe(this.form, config);
    }

    /**
     * Callback function to handle input events.
     */
    callback() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.checkSubmitable(), 100);
    }

    /**
     * Checks if the form is valid and enables/disables the submit button(s) accordingly.
     */
    checkSubmitable() {
        const btns = this.form.querySelectorAll('[type="submit"]');
        let isSubmitable = this.form.checkValidity();

        for (let i = 0; i < btns.length; i++) {
            btns[i].disabled = !isSubmitable;
        }
    }

    /**
     * Handles form submission event.
     */
    handleSubmission(event) {
        const submitBtn = this.form.querySelector('[type="submit"]');
        submitBtn.classList.add("loading"); // Add loading class
        submitBtn.disabled = true; // Disable button to prevent multiple clicks

        setTimeout(() => {
            // Simulate form submission delay and then reset
            submitBtn.classList.remove("loading");
            submitBtn.disabled = false;
        }, 3000);
    }
}
