"use strict";

/**
 * Usage:
 * <time is="x-time" datetime="{timestamp}" type="[datetime|date|time]"></time>
 */
export class TimeElement extends HTMLTimeElement {
    static observedAttributes = ["datetime"];

    constructor() {
        super();
    }

    attributeChangedCallback(name, oldValue, newValue) {
        this.render();
    }

    render() {
        let timestamp = this.dateTime || this.textContent;
        if (
            parseInt(timestamp, 10).toString() === timestamp &&
            timestamp.length === 10
        ) {
            timestamp = parseInt(timestamp, 10) * 1000;
        }

        let type = this.getAttribute("type") || this.dataset.type || "datetime";
        let date = new Date(timestamp);
        let lang = this.lang || document.documentElement.lang || "en";
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
        this.textContent = formatter.format(date);
        this.setAttribute("title", date.toUTCString());
    }
}
