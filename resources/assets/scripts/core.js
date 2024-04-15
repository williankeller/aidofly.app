`use strict`;

import { FormElement } from "./elements/form-element.js";
import { CreditElement } from "./elements/credit-element.js";

customElements.define("x-form", FormElement, { extends: "form" });
customElements.define("x-credit", CreditElement, { extends: "data" });
