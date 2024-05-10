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
        pagination: {},

        init() {
            this.loadMore();

            let timer = null;
            timer = setTimeout(() => this.retrieveResources(), 200);

            window.addEventListener("filter.filtered", (e) => {
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

                    this.pagination = list.pagination;

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

    Alpine.data("filter", (filters = [], sort = []) => ({
        filters: filters,
        sort: sort,

        orderby: null,
        dir: null,

        params: {
            search: null,
            sort: null,
        },

        init() {
            this.filters.forEach(
                (filter) => (this.params[filter.model] = null)
            );
            this.getCategories();

            this.bindKeyboardShortcuts();

            let sortparams = ["orderby", "dir"];
            sortparams.forEach((param) => {
                this.$watch(param, () => {
                    this.params.sort = null;
                    if (this.orderby) {
                        this.params.sort = this.orderby;

                        if (this.dir) {
                            this.params.sort += `:${this.dir}`;
                        }
                    }
                });
            });

            this.$watch("params", (params) => {
                this.$dispatch("filter.filtered", params);
                this.updateUrl();
            });

            this.parseUrl();

            window.addEventListener("filter.reset", () => this.resetFilters());
        },

        bindKeyboardShortcuts() {
            window.addEventListener("keydown", (e) => {
                if (e.metaKey && e.key === "k") {
                    e.preventDefault();
                    // get the search element by type="search" attribute
                    let searchElement = document.querySelector(
                        'input[type="search"]'
                    );
                    searchElement.focus();
                } else if (e.key === "Escape") {
                    let searchElement = document.querySelector(
                        'input[type="search"]'
                    );
                    searchElement.blur();
                }
            });
        },

        resetFilters() {
            for (const key in this.params) {
                if (key != "sort") {
                    this.params[key] = null;
                }
            }
        },

        getCategories() {
            let filter = this.filters.find(
                (filter) => filter.model == "category"
            );

            if (!filter) {
                return;
            }

            getCategoryList().then((categories) => {
                categories.forEach((category) => {
                    filter.options.push({
                        value: category.id,
                        label: category.title,
                    });

                    this.parseUrl();
                });
            });
        },

        parseUrl() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search.slice(1));

            for (const key in this.params) {
                if (!params.has(key)) {
                    continue;
                }

                let filter = this.filters.find((f) => f.model == key);

                if (!filter) {
                    continue;
                }

                let option = filter.options?.find(
                    (o) => o.value == params.get(key)
                );

                if (!option && !filter.hidden) {
                    continue;
                }

                this.params[key] = params.get(key);
            }

            if (this.params.sort) {
                let sort = this.params.sort.split(":");
                this.orderby = sort[0];
                this.dir = sort[1] || "asc";
            }
        },

        updateUrl() {
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search.slice(1));

            for (const key in this.params) {
                if (this.params[key]) {
                    params.set(key, this.params[key]);
                } else {
                    params.delete(key);
                }
            }

            window.history.pushState(
                {},
                "",
                `${url.origin}${url.pathname}?${params}`
            );
        },
    }));
}
