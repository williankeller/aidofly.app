<?php

namespace App\Services\Studio;

use Illuminate\Support\Str;
use Highlight\Highlighter;

class Markdown
{
    /**
     * Escapes HTML special characters in a string
     * Source: https://stackoverflow.com/questions/1787322
     *
     * @param string $text Raw text
     * @return string Escaped HTML
     */
    public static function escapeHtml($text)
    {
        $map = [
            "&" => "&amp;",
            "<" => "&lt;",
            ">" => "&gt;",
            '"' => "&quot;",
            "'" => "&#039;",
        ];

        return str_replace(array_keys($map), array_values($map), $text);
    }

    public static function convertMarkdownToHtml($text)
    {
        if (empty($text)) {
            return '';
        }

        // add ending code block tags when missing
        $codeBlockCount = substr_count($text, '```');
        if ($codeBlockCount % 2 !== 0) {
            $text .= "\n```";
        }

        // HTML-escape parts of text that are not inside ticks.
        // This prevents <?php from turning into a comment tag
        $escapedParts = [];
        $codeParts = explode('`', $text);
        foreach ($codeParts as $i => $part) {
            if ($i % 2 === 0) {
                // Escape HTML special characters
                $escapedParts[] = self::escapeHtml($part);
            } else {
                $escapedParts[] = $part;
            }
        }
        $escapedMessage = implode('`', $escapedParts);

        // Convert Markdown to HTML
        $formattedMessage = '';
        $codeBlocks = explode('```', $escapedMessage);

        foreach ($codeBlocks as $i => $block) {
            if ($i % 2 === 0) {
                // add two spaces at the end of every line for non-codeblocks
                // so that one-per-line lists without markdown can be generated
                $formattedMessage .= Str::markdown(
                    trim($block) . "  \n"
                );
            } else {
                // convert Markdown code blocks to HTML
                $formattedMessage .= Str::markdown(
                    "```" . $block . "```"
                );
            }
        }

        return $formattedMessage;
    }

    public static function markdownToHtml($text)
    {
        $html = self::convertMarkdownToHtml($text);

        $highlighter = new Highlighter();
        $html = preg_replace_callback(
            '/<pre><code class="language-java">(.*?)<\/code><\/pre>/s',
            function ($matches) use ($highlighter) {
                $textContent = trim(html_entity_decode($matches[1]));
                $highlighted = $highlighter->highlightAuto($textContent);

                $highlightedHtml = '<pre><code class="hljs ' . $highlighted->language . '">' . $highlighted->value . '</code></pre>';

                $actions = '<div class="actions">';
                $actions .= '<span class="lang">' . $highlighted->language . '</span>';
                $actions .= '<span class="copy" is="copyable-element" data-copy="' . htmlspecialchars($textContent) . '"><i class="ti ti-copy"></i></span>';
                $actions .= '</div>';

                return $actions . $highlightedHtml;
            },
            $html
        );

        return $html;
    }
}
