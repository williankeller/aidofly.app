import Alpine from "alpinejs";
import { WaveElement } from "./wave-element.js";
import { voiceover } from "./voiceover.js";
import { listing } from "../../../components/listing.js";

customElements.define("component-wave", WaveElement);

listing();
voiceover();

// Only start Alpine if it's not already initialized
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
