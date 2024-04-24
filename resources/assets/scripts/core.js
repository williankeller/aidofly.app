`use strict`;

import { FormElement } from "./elements/form-element.js";
import { CreditElement } from "./elements/credit-element.js";
import { TimeElement } from "./elements/time-element.js";
import { Modal, ModalController } from "./elements/modal-element.js";

customElements.define("x-form", FormElement, { extends: "form" });
customElements.define("x-credit", CreditElement, { extends: "data" });
customElements.define("x-time", TimeElement, { extends: "time" });
customElements.define("modal-element", Modal), { extends: "div" };

// Define singletons for custom elements
window.modal = new ModalController();
