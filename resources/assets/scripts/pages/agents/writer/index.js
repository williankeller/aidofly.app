import Alpine from "alpinejs";
import Tooltip from "@ryangjchandler/alpine-tooltip";
import { content } from "./content.js";

content();

Alpine.plugin(Tooltip.defaultProps({}));

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
