`use strict`;

import Alpine from "alpinejs";
import api from "../helpers/api";
import { notification } from "../helpers/notification";

export function listing() {
    Alpine.data("list", (basePath, strings = []) => ({
        state: "initial",

        params: {},
        total: null,
        cursor: null,
        resources: [],
        isLoading: false,
        hasMore: true,
        isFiltered: false,
        currentResource: null,
        isDeleting: false,

        init() {
            this.loadMore();

            let timer = null;
            timer = setTimeout(() => this.retrieveResources(), 200);

            window.addEventListener("lc.filtered", (e) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    this.params = e.detail;
                    this.retrieveResources(true);
                }, 200);
            });

            // @todo: Implement this counter
            this.total = 0;
        },

        retrieveResources(reset = false) {
            this.isLoading = true;
            let params = {};
            let isFiltered = false;

            for (const key in this.params) {
                if (this.params[key]) {
                    if (key != "sort") {
                        isFiltered = true;
                    }

                    params[key] = this.params[key];
                }
            }

            this.isFiltered = isFiltered;

            if (!reset && this.cursor) {
                params.starting_after = this.cursor;
            }

            api.get(basePath, params)
                .then((response) => response.json())
                .then((list) => {
                    this.state = "loaded";

                    this.resources = reset
                        ? list.data
                        : this.resources.concat(list.data);

                    if (this.resources.length > 0) {
                        this.cursor =
                            this.resources[this.resources.length - 1].uuid;
                    } else {
                        this.state = "empty";
                    }

                    this.isLoading = false;
                    this.hasMore = list.data.length >= 15;
                });
        },

        loadMore() {
            window.addEventListener("scroll", () => {
                if (
                    this.hasMore &&
                    !this.isLoading &&
                    window.innerHeight + window.scrollY + 600 >=
                        document.documentElement.scrollHeight
                ) {
                    this.retrieveResources();
                }
            });
        },
    }));
}
