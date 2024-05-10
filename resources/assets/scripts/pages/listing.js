`use strict`;

import Alpine from "alpinejs";
import Tooltip from "@ryangjchandler/alpine-tooltip";
import { listing } from "../components/listing";

listing();

Alpine.plugin(Tooltip.defaultProps({}));

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
