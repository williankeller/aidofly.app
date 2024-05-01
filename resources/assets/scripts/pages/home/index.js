"use strict";

import Alpine from "alpinejs";
import { homeView } from "./home-view";

homeView();

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
