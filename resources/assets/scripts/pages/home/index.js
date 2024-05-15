"use strict";

import Alpine from "alpinejs";
import { homeView } from "./home-view";

homeView();

const userAgent = navigator.userAgent.toLowerCase();
if (userAgent.includes("linux") || userAgent.includes("windows")) {
    document.querySelectorAll(".command-key").forEach((element) => {
        element.textContent = "Ctrl";
    });
}

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
