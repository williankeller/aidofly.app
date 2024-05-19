import Alpine from "alpinejs";
import Tooltip from "@ryangjchandler/alpine-tooltip";
import { chat } from "./chat.js";

chat();

Alpine.plugin(Tooltip.defaultProps({}));

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
