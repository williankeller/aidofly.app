`use strict`;

export class Modal {
    constructor() {
        let el = document.querySelector(`[aria-modal="true"]`);

        if (!el) {
            return;
        }

        let timer = 0;

        document.body.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                this.close();
            }
        });

        el.addEventListener("click", (e) => {
            if (!el.classList.contains("d-block")) {
                return;
            }
            if (e.target === el) {
                clearTimeout(timer);

                el.classList.add("modal-static");
                timer = setTimeout(() => {
                    el.classList.remove("modal-static");
                }, 200);
            }
        });
    }

    open(name) {
        this.close();
        let el = document.querySelector(`div[id="${name}"]`);

        if (!el) {
            return;
        }

        document.body.classList.add("modal-open");
        el.classList.add("d-block");
        el.nextElementSibling.classList.remove("d-none");
    }

    close() {
        document.querySelectorAll(`.modal`).forEach((modal, index, array) => {
            modal.classList.remove("d-block");
            modal.nextElementSibling.classList.add("d-none");

            if (index === array.length - 1) {
                document.body.classList.remove("modal-open");
            }
        });
    }
}
