`use strict`;

import Alpine from "alpinejs";
import { listing } from "../components/listing";

listing();

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
