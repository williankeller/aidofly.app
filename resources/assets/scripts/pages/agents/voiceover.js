"use strict";

import Alpine from "alpinejs";
import api from "../../helpers/api";

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

    languages: {
        eleven_multilingual_v2: [
            "English",
            "Japanese",
            "Chinese",
            "German",
            "Hindi",
            "French",
            "Korean",
            "Portuguese",
            "Italian",
            "Spanish",
            "Indonesian",
            "Dutch",
            "Turkish",
            "Flipino",
            "Polish",
            "Swedish",
            "Bulgarian",
            "Romanian",
            "Arabic",
            "Czech",
            "Greek",
            "Finnish",
            "Croatian",
            "Malay",
            "Slowak",
            "Danish",
            "Tamil",
            "Ukranian",
            "Russian",
        ],

        eleven_multilingual_v1: [
            "English",
            "German",
            "Polish",
            "Spanish",
            "Italian",
            "French",
            "Portuguese",
            "Hindi",
            "Arabic",
        ],
        eleven_monolingual_v1: ["English"],
        eleven_turbo_v2: ["English"],
        "tts-1": [
            "Afrikaans",
            "Arabic",
            "Armenian",
            "Azerbaijani",
            "Belarusian",
            "Bosnian",
            "Bulgarian",
            "Catalan",
            "Chinese",
            "Croatian",
            "Czech",
            "Danish",
            "Dutch",
            "English",
            "Estonian",
            "Finnish",
            "French",
            "Galician",
            "German",
            "Greek",
            "Hebrew",
            "Hindi",
            "Hungarian",
            "Icelandic",
            "Indonesian",
            "Italian",
            "Japanese",
            "Kannada",
            "Kazakh",
            "Korean",
            "Latvian",
            "Lithuanian",
            "Macedonian",
            "Malay",
            "Marathi",
            "Maori",
            "Nepali",
            "Norwegian",
            "Persian",
            "Polish",
            "Portuguese",
            "Romanian",
            "Russian",
            "Serbian",
            "Slovak",
            "Slovenian",
            "Spanish",
            "Swahili",
            "Swedish",
            "Tagalog",
            "Tamil",
            "Thai",
            "Turkish",
            "Ukrainian",
            "Urdu",
            "Vietnamese",
            "Welsh",
        ],
    },

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

        this.getVoices();
    },

    fetchHistory() {
        api.get("/library/speeches")
            .then((response) => response.json())
            .then((list) => {
                let data = list.data;
                this.history = data.reverse();
            });
    },

    getVoices(cursor = null) {
        let params = {
            limit: 250,
        };

        if (cursor) {
            params.starting_after = cursor;
        }

        api.get("/agents/voiceover/voices", params)
            .then((response) => response.json())
            .then((list) => {
                if (!this.voices) {
                    this.voices = [];
                }

                this.voices.push(...list.data);

                if (list.data.length > 0 && list.data.length == params.limit) {
                    this.getVoices(this.voices[this.voices.length - 1].id);
                }
            });
    },

    submit() {
        if (this.isProcessing) {
            return;
        }

        this.isProcessing = true;

        let data = {
            voice_id: this.voice.id,
            prompt: this.prompt,
        };

        api.post("/ai/speeches", data)
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

                this.select(speech);
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

    save(speech) {
        api.post(`/library/speeches/${speech.id}`, {
            title: speech.title,
        });
    },

    remove(transcription) {
        this.isDeleting = true;

        api.delete(`/library/speeches/${transcription.id}`)
            .then(() => {
                this.preview = null;
                window.modal.close();

                toast.show(
                    "Speech has been deleted successfully.",
                    "ti ti-trash"
                );
                this.isDeleting = false;

                let url = new URL(window.location.href);
                url.pathname =
                    "/voiceover/" + (this.voice?.id || this.voices[0] || "");
                window.history.pushState({}, "", url);

                this.history.splice(this.history.indexOf(transcription), 1);
            })
            .catch((error) => (this.isDeleting = false));
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

        if (this.languages[voice.model]) {
            for (let lang of this.languages[voice.model]) {
                if (lang.toLowerCase().includes(query)) {
                    return true;
                }
            }
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

Alpine.start();
