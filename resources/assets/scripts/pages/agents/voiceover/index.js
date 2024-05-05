import Alpine from "alpinejs";
import Tooltip from "@ryangjchandler/alpine-tooltip";
import { WaveElement } from "./wave-element.js";
import { voiceover } from "./voiceover.js";
import { listing } from "../../../components/listing.js";

customElements.define("component-wave", WaveElement);

Alpine.plugin(Tooltip.defaultProps({}));

listing();
voiceover();

// Only start Alpine if it's not already initialized
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
