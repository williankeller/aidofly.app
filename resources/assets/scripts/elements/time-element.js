"use strict";

/**
 * Represents a custom element that extends the HTMLElement class.
 * Usage: <time data-element="time" data-datetime="{timestamp}"></time>
 */
export class TimeElement {
    static observedAttributes = ["data-datetime"];

    constructor(element) {
        this.element = element;
        this.render();

        // Observe changes to attributes
        this.observeAttributes();
    }

    observeAttributes() {
        const observer = new MutationObserver((mutationsList) => {
            mutationsList.forEach((mutation) => {
                if (
                    mutation.type === "attributes" &&
                    mutation.attributeName === "data-datetime"
                ) {
                    this.render();
                }
            });
        });

        observer.observe(this.element, { attributes: true });
    }

    attributeChangedCallback(name, oldValue, newValue) {
        this.render();
    }

    render() {
        let timestamp =
            this.element.getAttribute("data-datetime") ||
            this.element.textContent;
        if (
            parseInt(timestamp, 10).toString() === timestamp &&
            timestamp.length === 10
        ) {
            timestamp = parseInt(timestamp, 10) * 1000;
        }

        let type =
            this.element.getAttribute("data-type") ||
            this.element.dataset.type ||
            "datetime";
        let date = new Date(timestamp);
        let lang = this.element.lang || document.documentElement.lang || "en";
        let diff = Math.abs(Date.now() - timestamp);
        let format = {
            month: "long",
            day: "numeric",
        };

        if (type == "time") {
            format = {
                hour: "2-digit",
                minute: "2-digit",
            };
        } else if (type == "date") {
            if (diff >= 86400000 * 365) {
                format.month = "short";
                format.year = "numeric";
            }
        } else {
            if (diff >= 86400000 * 365) {
                format.month = "short";
                format.year = "numeric";
            }

            format.hour = "2-digit";
            format.minute = "2-digit";
        }

        let formatter = new Intl.DateTimeFormat(lang, format);
        this.element.textContent = formatter.format(date);
        this.element.setAttribute("title", date.toUTCString());
    }
}
