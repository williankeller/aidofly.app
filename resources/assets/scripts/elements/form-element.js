"use strict";

/**
 * Represents a custom form element that extends the HTMLFormElement class.
 * This class provides additional functionality for handling form inputs and
 * validation.
 */
export class FormElement extends HTMLFormElement {
    constructor() {
        super();
        this.timer = 0;
    }

    /**
     * Called when the form element is connected to the DOM.
     * Sets up event listeners and a mutation observer to track changes in the
     * form element.
     */
    connectedCallback() {
        this.addEventListener("input", () => this.callback());
        this.addEventListener("submit", (event) =>
            this.handleSubmission(event)
        );

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
        this.observer.observe(this, config);

        // Check if the form is valid and enable/disable the submit button(s)
        this.checkSubmitable();
    }

    /**
     * Called when the form element is disconnected from the DOM.
     * Disconnects the mutation observer.
     */
    disconnectedCallback() {
        this.observer.disconnect();
    }

    /**
     * Checks if the form is valid and enables/disables the submit button(s)
     * accordingly.
     */
    callback() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.checkSubmitable(), 100);
    }

    /**
     * Checks if the form is valid and enables/disables the submit button(s)
     * accordingly.
     */
    checkSubmitable() {
        const btns = this.querySelectorAll('[type="submit"]');
        let isSubmitable = this.checkValidity();

        for (let i = 0; i < btns.length; i++) {
            btns[i].disabled = !isSubmitable;
        }
    }

    handleSubmission(event) {
        const submitBtn = this.querySelector('[type="submit"]');
        submitBtn.classList.add("loading"); // Add loading class
        submitBtn.disabled = true; // Disable button to prevent multiple clicks

        setTimeout(() => {
            // Simulate form submission delay and then reset
            submitBtn.classList.remove("loading");
            submitBtn.disabled = false;
        }, 3000);
    }
}
