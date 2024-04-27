import Alpine from "alpinejs";
import { WaveElement } from "./wave-element.js";
import { voiceover } from "./voiceover.js";
import { listing } from "../../../components/listing.js";

customElements.define("component-wave", WaveElement);

console.log("Alpine", Alpine);

listing();
voiceover();

Alpine.start();
