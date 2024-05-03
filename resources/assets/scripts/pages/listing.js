`use strict`;

import Alpine from "alpinejs";
import { listing } from "../components/listing";
import { CreditElement } from "../elements/credit-element.js";
import { TimeElement } from "../elements/time-element.js";

listing();


// Wait for Alpine.js template to be rendered
document.addEventListener("alpine:init", () => {
    // Listen for the "afterTransition" event, which indicates that Alpine.js has rendered the template
    document.addEventListener("afterTransition", () => {
        // <div data-element="credit" data-value="{amount}"></div>
        const creditElements = document.querySelectorAll(
            '[data-element="credit"]'
        );
        creditElements.forEach((credit) => new CreditElement(credit));

        // <time data-element="time" data-datetime="{timestamp}"></time>
        const timeElements = document.querySelectorAll(
            'template time[data-element="time"]'
        );
        console.log("timeElements", timeElements);
        timeElements.forEach((time) => new TimeElement(time));
    });
});

document.addEventListener('alpine:initialized', () => {
    console.log("alpine:initialized");
    document.addEventListener('afterTransition', () => {
        console.log("afterTransition");
    });
     // <time data-element="time" data-datetime="{timestamp}"></time>
     const timeElements = document.querySelectorAll(
        'template time[data-element="time"]'
    );
    console.log("timeElements", timeElements);
    timeElements.forEach((time) => new TimeElement(time));
})

// Check if Alpine has already been started
if (!window.AlpineStarted) {
    Alpine.start();
    window.AlpineStarted = true;
}
