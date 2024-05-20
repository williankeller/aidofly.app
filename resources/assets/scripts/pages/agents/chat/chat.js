"use strict";

import Alpine from "alpinejs";
import api from "../../../helpers/api";
import { markdownToHtml } from "../../../helpers/markdown";
import { EventSourceParserStream } from "eventsource-parser/stream";
import { notification } from "../../../helpers/notification";
import { typing } from "../../../helpers/typing";

export function chat() {
    Alpine.data("chat", (conversation = null) => ({
        conversation: null,
        prompt: null,
        isProcessing: false,
        isLoadingConversation: false,
        autoScroll: false,
        temporaryPrompt: null,
        promptUpdated: false,
        watingStream: false,
        isNewChat: true,

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

            if (conversation.uuid) {
                this.loadConversation(conversation.uuid);
            }
        },

        async loadConversation(uuid) {
            this.isNewChat = false;
            try {
                this.isLoadingConversation = true;
                // Make the API call and wait for the response
                const response = await api.get(
                    `/agent/chat/conversation/${uuid}`
                );
                const list = await response.json();

                // Destructure the 'data' from the list and parse the content
                const { data } = list;
                const messages = JSON.parse(data.content);

                // Process each message in the conversation
                messages.forEach((message) => {
                    this.cloneMessageTemplate(message.content, message.role);
                });

                // Select the conversation after processing messages
                this.select(data);
                this.isLoadingConversation = false;
            } catch (error) {
                this.error("The conversation could not be found.");
            }

            // Ensure autoScroll is set to true after attempting to load the conversation
            this.autoScroll = true;
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
            this.sendMessage(data, this.assistant);
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

        async sendMessage(data, assistant) {
            try {
                const response = await api.post("/agent/chat/", data);
                const stream = response.body
                    .pipeThrough(new TextDecoderStream())
                    .pipeThrough(new EventSourceParserStream());
                const reader = stream.getReader();

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

                this.autoScroll = true;
                this.promptUpdated = false;
                this.watingStream = false;

                // Keep reading data from the stream
                while (true) {
                    this.handleAutoScroll();
                    const { value, done } = await reader.read();
                    if (done) {
                        this.isProcessing = false;
                        break;
                    }
                    this.processStreamEvent(value, message, pushed);
                }
            } catch (error) {
                this.error('An error occurred while sending the message');
            }
        },

        handleAutoScroll() {
            if (this.autoScroll) {
                window.scrollTo(0, document.body.scrollHeight);
            }
        },

        processStreamEvent(value, message, pushed) {
            switch (value.event) {
                case "token":
                    this.handleToken(value.data, message, pushed);
                    break;
                case "message":
                    this.handleMessage(value.data);
                    break;
                case "error":
                    this.handleError(value.data);
                    break;
            }
        },

        handleToken(data, message, pushed) {
            message.content += JSON.parse(data);
            if (!pushed) {
                this.conversation.messages.push(message);
                pushed = true;
            }
            this.updateMessageContent(message.content);
        },

        handleMessage(data) {
            let conversation = JSON.parse(data);
            this.select(conversation);
            this.isProcessing = false;
        },

        handleError(error) {
            this.prompt = this.temporaryPrompt;
            this.error(error);
        },

        error(msg) {
            this.isProcessing = false;
            this.isLoadingConversation = false;
            this.isNewChat = true;
            notification(msg);
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

            typing(
                conversation.title,
                document.querySelector(".page-heading")
            );

            let url = new URL(window.location.href);
            url.pathname = "/agent/chat/" + conversation.uuid;
            window.history.pushState({}, "", url);
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
    }));
}
