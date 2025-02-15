"use strict";

import Alpine from "alpinejs";
import api from "../../helpers/api";
import { searchForm } from "./search-form";

export function homeView() {
    Alpine.data("home", () => ({
        documents: [],
        documentsFetched: false,
        usage: [],
        usageFetched: false,

        init() {
            searchForm();
            //this.getRecentDocuments();
            this.getUsage();
        },

        getRecentDocuments() {
            let params = {
                limit: 5,
                sort: "created_at:desc",
            };

            api.get("/library/documents", params)
                .then((response) => response.json())
                .then((list) => {
                    this.documentsFetched = true;
                    this.documents = list.data;
                });
        },

        getUsage() {
            api.get("/user/usage")
                .then((response) => response.json())
                .then((usage) => {
                    this.usageFetched = true;
                    this.usage = usage.data;
                });
        },
    }));
}
