"use strict";

import Alpine from "alpinejs";
import api from "../../../helpers/api";

export function voiceover() {
    Alpine.data("voiceover", (voice = null, speech = null) => ({
        isProcessing: false,
        isDeleting: false,
        history: null,
        preview: speech,
        showSettings: false,
        voice: voice,
        voices: null,
        prompt: null,
        query: "",

        init() {},

        submit() {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;

            let data = {
                uuid: this.voice.uuid,
                prompt: this.prompt,
            };

            api.post("/agent/voiceover/speech", data)
                .then((response) => response.json())
                .then((speech) => {
                    this.preview = speech;
                    this.isProcessing = false;
                    this.prompt = null;
                })
                .catch((error) => {
                    this.isProcessing = false;
                    console.error(error);
                });
        },
    }));

    Alpine.data("audience", (item) => ({
        item: item,
        isProcessing: null,

        changeAudience(visibility) {
            this.isProcessing = visibility;

            api.post(`/library/${this.item.id}`, { visibility: visibility })
                .then((resp) => resp.json())
                .then((resp) => {
                    window.modal.close();

                    this.isProcessing = null;
                    this.item = resp;
                });
        },
    }));
}
