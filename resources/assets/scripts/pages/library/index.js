`use strict`;

import Alpine from "alpinejs";
import Tooltip from "@ryangjchandler/alpine-tooltip";
import { library } from "./library";

library();

Alpine.plugin(Tooltip.defaultProps({}));

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
