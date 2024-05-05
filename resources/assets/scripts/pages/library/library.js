`use strict`;

import Alpine from "alpinejs";

import api from "../../helpers/api";
import { markdownToHtml } from "../../helpers/markdown";
import { notification } from "../../helpers/notification";

export function library() {
    Alpine.data("content", (library = null) => ({
        library: library,

        init() {},

        format(content) {
            return markdownToHtml(content);
        },

        copyDocumentContents(content) {
            navigator.clipboard.writeText(content).then(() => {
                notification("Content copied to clipboard!", "success");
            });
        },

        download(doc, format) {
            if (format == "markdown") {
                var mimeType = "text/markdown";
                var ext = "md";
                var content = doc.content;
            } else if (format == "html") {
                var mimeType = "text/html";
                var ext = "html";
                var content = `<html><head><meta charset="utf-8" /><title>${
                    doc.title
                }</title></head><body>${this.format(
                    doc.content
                )}</body></html>`;
            } else if (format == "word") {
                var mimeType = "application/vnd.ms-word";
                var ext = "doc";
                var content = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="utf-8" /><title>${
                    doc.title
                }</title></head><body>${this.format(
                    doc.content
                )}</body></html>`;
            } else {
                var mimeType = "text/plain";
                var ext = "txt";
                var content = doc.content;
            }

            this.downloadFromUrl(
                `data:${mimeType};charset=utf-8,${encodeURIComponent(content)}`,
                doc.title,
                ext
            );
        },

        downloadFromUrl(url, filename, ext) {
            const anchor = document.createElement("a");
            anchor.href = url;
            anchor.download = `${filename}.${ext}`;

            document.body.appendChild(anchor);
            anchor.click();

            // Clean up
            document.body.removeChild(anchor);
        },

        saveDocument(doc) {
            api.post(`/library/documents/${doc.uuid}`, doc);
        },
    }));
}
