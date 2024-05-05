`use strict`;

import Alpine from "alpinejs";
import api from "../helpers/api";
import { notification } from "../helpers/notification";

export function listing() {
    Alpine.data("list", (basePath) => ({
        state: "initial",

        params: {},
        total: null,
        currentPage: 1,
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
                    this.currentPage = 1; // Reset to the first page for filtered results
                    this.retrieveResources(true);
                }, 200);
            });

            // Initialize with total count as needed
            this.total = 0;
        },

        retrieveResources(reset = false) {
            this.isLoading = true;
            let params = {};
            let isFiltered = false;

            for (const key in this.params) {
                if (this.params[key]) {
                    if (key !== "sort") {
                        isFiltered = true;
                    }
                    params[key] = this.params[key];
                }
            }

            this.isFiltered = isFiltered;
            params.page = this.currentPage;

            api.get(basePath, params)
                .then((response) => response.json())
                .then((list) => {
                    this.state = "loaded";

                    this.resources = reset
                        ? list.data
                        : this.resources.concat(list.data);

                    if (this.resources.length === 0) {
                        this.state = "empty";
                    }

                    this.isLoading = false;
                    this.hasMore = this.currentPage < list.pagination.pages;
                    this.total = list.pagination.total;

                    // Move to the next page
                    this.currentPage += 1;
                })
                .catch((error) => {
                    this.state = "error";
                    console.error(error);
                    notification(
                        "An error occurred while loading the resources.",
                        "error"
                    );
                });
        },

        loadMore() {
            window.addEventListener("scroll", () => {
                if (
                    this.hasMore &&
                    !this.isLoading &&
                    window.innerHeight + window.scrollY + 300 >=
                        document.documentElement.scrollHeight
                ) {
                    this.retrieveResources();
                }
            });
        },
    }));
}
