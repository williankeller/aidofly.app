`use strict`;

import Alpine from "alpinejs";
import { FormElement } from "./elements/form-element.js";
import { CreditElement } from "./elements/credit-element.js";
import { TimeElement } from "./elements/time-element.js";
import { Modal } from "./components/modal.js";

customElements.define("x-form", FormElement, { extends: "form" });
customElements.define("x-credit", CreditElement, { extends: "data" });
customElements.define("x-time", TimeElement, { extends: "time" });

window.modal = new Modal();

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}