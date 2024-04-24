`use strict`;

export class Modal extends HTMLElement {
    constructor() {
        super();

        let timer = 0;

        this.classList.add("group/modal");
        this.addEventListener("click", (e) => {
            if (e.target === this) {
                clearTimeout(timer);

                this.setAttribute("clicked", "");
                timer = setTimeout(() => {
                    this.removeAttribute("clicked");
                }, 100);
            }
        });
    }
}

export class ModalController {
    constructor() {
        document.body.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                this.close();
            }
        });

        document.body.addEventListener("click", (e) => {
            if (document.body.hasAttribute(`data-modal="delete-preset-modal"`)) {
                let el = document.querySelector(`div[id="delete-preset-modal"]`);
                el.classList.add("modal-static");
                timer = setTimeout(() => {
                    el.classList.add("modal-static");
                }, 200);
            }
        });

    }

    open(name) {
        this.close();
        let el = document.querySelector(`div[id="delete-preset-modal"]`);

        if (!el) {
            return;
        }

        document.body.setAttribute("data-modal", name);
        el.classList.add("d-block");
        // Add display block to the next div element too
        el.nextElementSibling.classList.remove("d-none");
    }

    close() {
        document.querySelectorAll(`.modal`).forEach((modal, index, array) => {
            modal.classList.remove("d-block");
            modal.nextElementSibling.classList.add("d-none");

            if (index === array.length - 1) {
                document.body.removeAttribute("data-modal");
            }
        });
    }
}
