"use strict";

/**
 * Represents a custom element that extends the HTMLElement class.
 * Usage: <div data-element="credit" data-value="{amount}"></div>
 */
export class CreditElement {
    static observedAttributes = [
        "data-value",
        "lang",
        "format",
        "data-format",
        "format-unlimited",
        "data-format-unlimited",
    ];

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
                    mutation.attributeName === "data-value"
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
        let value =
            this.element.getAttribute("data-value") || this.element.textContent;
        let format =
            this.element.getAttribute("format") ||
            this.element.dataset.format ||
            ":count";
        let showFraction =
            this.element.getAttribute("fraction") ||
            this.element.dataset.fraction;

        if (value === "" || value === "null" || isNaN(value)) {
            this.element.textContent = format.replaceAll(":count", "Unlimited");
            return;
        }

        let lang =
            this.element.getAttribute("lang") ||
            document.documentElement.lang ||
            "en";

        let amount = parseFloat(value);

        let options = {
            style: "decimal",
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            trailingZeroDisplay: "stripIfInteger",
        };

        let titleFormatter = new Intl.NumberFormat(lang, options);

        if (
            showFraction === "false" ||
            showFraction === "0" ||
            showFraction === "no" ||
            showFraction === "off"
        ) {
            // Explictly hide fraction
            options.minimumFractionDigits = 0;
            options.maximumFractionDigits = 0;
        }

        let formatter = new Intl.NumberFormat(lang, options);
        let title = titleFormatter.format(amount);
        let text = formatter.format(amount);

        if (text.length >= 7) {
            formatter = new Intl.NumberFormat(lang, {
                ...options,
                notation: "compact",
                compactDisplay: "short",
            });
            text = formatter.format(amount);
        }

        this.element.textContent = format.replaceAll(":count", text);

        if (title !== text) {
            this.element.setAttribute("title", title);
        }
    }
}
