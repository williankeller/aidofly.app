"use strict";

import Alpine from "alpinejs";

import { FormElement } from "./elements/form-element.js";
import { CreditElement } from "./elements/credit-element.js";
import { TimeElement } from "./elements/time-element.js";
import { Modal } from "./components/modal.js";

document.addEventListener("DOMContentLoaded", () => {
    // <form data-element="form"></form>
    const formElements = document.querySelectorAll('[data-element="form"]');
    formElements.forEach((form) => new FormElement(form));

    // <div data-element="credit" data-value="{amount}"></div>
    const creditElements = document.querySelectorAll('[data-element="credit"]');
    creditElements.forEach((credit) => new CreditElement(credit));

    // <time data-element="time" data-datetime="{timestamp}"></time>
    const timeElements = document.querySelectorAll("time[data-element='time']");
    timeElements.forEach((time) => new TimeElement(time));
});

window.modal = new Modal();

export default {
    methods: {
      toggleClasses() {
        document.body.classList.remove('nav-account');
        document.body.classList.toggle('nav-main');
      }
    }
  }

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
