"use strict";

import WaveSurfer from "wavesurfer.js";
import WebAudioPlayer from "wavesurfer.js/dist/webaudio.js";

export class WaveElement extends HTMLElement {
    static nowPlaying = null;

    static observedAttributes = [
        "src",
        "wave-color",
        "progress-color",
        "cursor-color",
    ];

    constructor() {
        super();

        this.container = this.querySelector("[wave]") || this;
        this.playBtn = this.querySelector("button[player]") || null;
        this.pauseBtn = this.querySelector("button[pause]") || null;
        this.playPauseBtn = this.querySelector("button[play-pause]") || null;
        this.processEl = this.querySelector("[process]") || null;
        this.durationEl = this.querySelector("[duration]") || null;

        if (this.playBtn) {
            this.playBtn.addEventListener("click", () => {
                if (!this.wave) {
                    this.initializeWaveSurfer();
                }
                this.wave.play();
            });
        }

        if (this.pauseBtn) {
            this.pauseBtn.addEventListener("click", () => {
                if (!this.wave) {
                    this.initializeWaveSurfer();
                }
                this.wave.pause();
            });
        }

        if (this.playPauseBtn) {
            this.playPauseBtn.addEventListener("click", () => {
                // Initialize WaveSurfer on first interaction if not already initialized
                if (!this.wave) {
                    this.initializeWaveSurfer();
                    // Add a flag to auto-play once ready
                    this.autoPlayOnceReady = true;
                } else {
                    // Play or pause based on current state
                    this.wave.playPause();
                }
            });
        }
    }

    connectedCallback() {}

    disconnectedCallback() {
        if (this.wave) {
            this.wave.destroy();
            WaveElement.nowPlaying = null;
        }
    }

    attributeChangedCallback(name, oldValue, newValue) {
        this.render();
    }

    getReadableDuration(duration) {
        let date = new Date(0);
        date.setSeconds(duration);

        if (duration > 3600) {
            return date.toISOString().substring(11, 19);
        }

        return date.toISOString().substring(14, 19);
    }

    seekTo(duration = 0) {
        this.wave.seekTo(duration / this.wave.getDuration());
    }

    initializeWaveSurfer() {
        const audio = new WebAudioPlayer();
        audio.src = this.getAttribute("src");

        this.wave = WaveSurfer.create({
            container: this.container,
            height: "auto",
            waveColor:
                this.getAttribute("wave-color") ||
                `${getComputedStyle(document.documentElement).getPropertyValue(
                    "--bs-gray-300"
                )}`,
            progressColor:
                this.getAttribute("progress-color") ||
                `${getComputedStyle(document.documentElement).getPropertyValue(
                    "--bs-primary"
                )}`,
            cursorColor:
                this.getAttribute("cursor-color") ||
                `${getComputedStyle(document.documentElement).getPropertyValue(
                    "--bs-primary"
                )}`,
            barWidth: 2,
            normalize: true,
            height: 24,
            cursorWidth: 0,
            barGap: 0,
            barRadius: 20,
            dragToSeek: true,
            media: audio,
        });

        this.wave.on("loading", () => this.setAttribute("state", "loading"));
        this.wave.on("load", () => this.setAttribute("state", "loaded"));

        this.wave.on("interaction", () => {
            if (!this.wave.isPlaying()) {
                this.wave.play();
            }
        });

        this.wave.on("audioprocess", (time) => {
            let duration = this.getReadableDuration(time);

            this.setAttribute("process", duration);

            // Dispatch/Trigger/Fire the event
            this.dispatchEvent(
                new CustomEvent("audioprocess", { detail: { time: time } })
            );

            if (this.processEl) {
                this.processEl.innerText = duration;
            }
        });

        this.wave.on("play", async () => {
            if (WaveElement.nowPlaying && WaveElement.nowPlaying != this.wave) {
                await WaveElement.nowPlaying.pause();
            }

            WaveElement.nowPlaying = this.wave;
            this.setAttribute("state", "playing");
        });

        this.wave.on("pause", () => {
            this.setAttribute("state", "paused");
        });

        this.wave.on("ready", (duration) => {
            if (this.durationEl) {
                this.durationEl.innerText = this.getReadableDuration(duration);
            }

            this.setAttribute("state", "ready");
            // Check if we need to auto-play
            if (this.autoPlayOnceReady) {
                this.wave.play();
                this.autoPlayOnceReady = false; // Reset the flag
            }
        });
    }

    render() {}
}
