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

        init() {
            this.$watch("preview", (value) => {
                // Update the item in the history list
                if (this.history) {
                    let index = this.history.findIndex(
                        (item) => item.id === value.id
                    );
                    if (index >= 0) {
                        this.history[index] = value;
                    }
                }
            });
        },

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
                    console.log(speech);

                    if (this.history === null) {
                        this.history = [];
                    }

                    this.history.push(speech);
                    this.preview = speech;
                    this.isProcessing = false;
                    this.prompt = null;

                    // TEMP this.select(speech);
                })
                .catch((error) => {
                    this.isProcessing = false;
                    console.error(error);
                });
        },

        select(speech) {
            this.preview = speech;

            let url = new URL(window.location.href);
            url.pathname = "/voiceover/" + speech.id;
            window.history.pushState({}, "", url);

            if (speech.voice) {
                this.voice = speech.voice;
            }
        },

        selectVoice(voice) {
            this.voice = voice;
            window.modal.close();

            let url = new URL(window.location.href);
            url.pathname = "/voiceover/" + voice.id;
            window.history.pushState({}, "", url);
        },

        doesVoiceMatch(voice, query) {
            query = query.trim().toLowerCase();

            if (!query) {
                return true;
            }

            if (voice.name.toLowerCase().includes(query)) {
                return true;
            }

            if (voice.tone && voice.tone.toLowerCase().includes(query)) {
                return true;
            }

            return false;
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
