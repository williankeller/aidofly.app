"use strict";

import Alpine from "alpinejs";
import api from "../../../helpers/api";
import { markdownToHtml } from "../../../helpers/markdown";
import { EventSourceParserStream } from "eventsource-parser/stream";
import { notification } from "../../../helpers/notification";

export function chat() {
    Alpine.data("chat", (conversation = null) => ({
        conversation: null,
        prompt: null,
        isProcessing: false,
        autoScroll: false,
        temporaryPrompt: null,
        promptUpdated: false,
        watingStream: false,

        init() {
            window.addEventListener("scroll", () => {
                this.autoScroll =
                    window.scrollY + window.innerHeight + 500 >=
                    document.documentElement.scrollHeight;
            });

            window.addEventListener("mouseup", (e) => {
                //
            });

            this.$watch("prompt", () => (this.promptUpdated = true));
        },

        format(message) {
            return message ? markdownToHtml(message) : "";
        },

        async submit() {
            if (this.isProcessing) {
                return;
            }

            this.isProcessing = true;

            if (!this.conversation) {
                this.createConversation();
            }

            let data = new FormData();
            data.append("prompt", this.prompt);

            // Get reference from the reference input
            let reference = document.querySelector('[name="reference"]');
            if (reference) {
                data.append("reference", reference.value);
            }

            let message = {
                object: "message",
                id: "temp",
                role: "user",
                content: this.prompt,
                reference: data.get("reference") ?? null,
            };
            // Add first user message to the conversation
            this.conversation.messages.push(message);
            this.cloneMessageTemplate(message.content, "user");

            // Clear the prompt after sending the message and save the previous one in case of an error
            this.temporaryPrompt = this.prompt;
            this.prompt = "";

            this.watingStream = true;
            // Create a new conversation for the assistant on load mode
            this.cloneMessageTemplate(null, "assistant");

            // Ask the assistant (perform the API request)
            this.ask(data, this.assistant);
        },

        cloneMessageTemplate(content, role) {
            let templateId =
                role === "user"
                    ? "#message-user-template"
                    : "#message-ai-template";

            let template = document
                .querySelector(templateId)
                .content.cloneNode(true);

            let messageText = template.querySelector(
                "[data-message-element='content']"
            );

            if (content !== null) {
                messageText.innerHTML = this.format(content);
            }

            document.querySelector("#chat-container").appendChild(template);

            if (this.autoScroll) {
                window.scrollTo(0, document.body.scrollHeight);
            }
        },

        updateMessageContent(content) {
            // Find the last assistant message
            let messageElements = document.querySelectorAll(".message-ai");

            if (messageElements.length > 0) {
                let lastMessageElement =
                    messageElements[messageElements.length - 1];

                lastMessageElement.querySelector(
                    "[data-message-element='content']"
                ).innerHTML = this.format(content);
            }
        },

        async ask(data, assistant) {
            try {
                let response = await api.post("/agent/chat/", data);

                // Get the readable stream from the response body
                const stream = response.body
                    .pipeThrough(new TextDecoderStream())
                    .pipeThrough(new EventSourceParserStream());

                // Get the reader from the stream
                const reader = stream.getReader();

                // Temporary message
                let message = {
                    object: "message",
                    id: "temp",
                    model: null,
                    role: "assistant",
                    content: "",
                    assistant: assistant,
                    reference: data.get("reference"),
                    children: [],
                };
                let pushed = false;

                window.scrollTo(0, document.body.scrollHeight);
                this.autoScroll = true;
                this.promptUpdated = false;
                this.watingStream = false;

                while (true) {
                    if (this.autoScroll) {
                        window.scrollTo(0, document.body.scrollHeight);
                    }

                    const { value, done } = await reader.read();
                    if (done) {
                        this.isProcessing = false;
                        break;
                    }

                    if (value.event == "token") {
                        message.content += JSON.parse(value.data);

                        if (!pushed) {
                            this.conversation.messages.push(message);
                            pushed = true;
                        }
                        this.updateMessageContent(message.content);

                        continue;
                    }

                    if (value.event == "message") {
                        let conversation = JSON.parse(value.data);

                        this.select(conversation);

                        this.isProcessing = false;
                        continue;
                    }

                    if (value.event == "error") {
                        this.prompt = this.temporaryPrompt;
                        this.error(value.data);
                        break;
                    }
                }
            } catch (error) {
                this.prompt = this.temporaryPrompt;
                this.error(error);
            }
        },

        error(msg) {
            this.isProcessing = false;
            notification(msg);
            console.error(msg);
        },

        createConversation() {
            let conversation = {
                messages: [],
            };
            this.conversation = conversation;
        },

        select(conversation) {
            let reference = document.querySelector('[name="reference"]');
            reference.value = conversation.uuid;

            // Add the title to the .page-heading element
            this.typing(
                conversation.title,
                document.querySelector(".page-heading")
            );

            let url = new URL(window.location.href);
            url.pathname = "/agent/chat/" + conversation.uuid;
            window.history.pushState({}, "", url);
        },

        typing(text, outputElement, speed = 40) {
            let index = 0;

            // Clear the output element
            outputElement.textContent = "";

            function type() {
                if (index < text.length) {
                    outputElement.textContent += text.charAt(index);
                    index++;
                    setTimeout(type, speed);
                }
            }
            type();
        },

        enter(e) {
            if (
                e.key === "Enter" &&
                !e.shiftKey &&
                !this.isProcessing &&
                this.prompt &&
                this.prompt.trim() !== ""
            ) {
                e.preventDefault();
                this.submit();
            }
        },

        copy(message) {
            navigator.clipboard.writeText(message.content).then(() => {
                notification("Copied to clipboard!", "success");
            });
        },

        textSelect(e) {
            this.$refs.quote.classList.remove("flex");

            let selection = window.getSelection();

            if (selection.rangeCount <= 0) {
                return;
            }

            let range = selection.getRangeAt(0);
            let text = range.toString();

            if (text.trim() == "") {
                return;
            }

            e.stopPropagation();

            let startNode = range.startContainer;
            let startOffset = range.startOffset;

            let rect;
            if (startNode.nodeType === Node.TEXT_NODE) {
                // Create a temporary range to get the exact position of the start
                let tempRange = document.createRange();
                tempRange.setStart(startNode, startOffset);
                tempRange.setEnd(startNode, startOffset + 1); // Add one character to make the range visible
                rect = tempRange.getBoundingClientRect();
            } else if (startNode.nodeType === Node.ELEMENT_NODE) {
                // For element nodes, get the bounding rect directly
                rect = startNode.getBoundingClientRect();
            }
            return;
        },
    }));
}
