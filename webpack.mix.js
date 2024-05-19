const mix = require("laravel-mix");
const fs = require("fs-extra");

/**
 * Copy images
 */
if (fs.existsSync("resources/assets/images")) {
    fs.copy("resources/assets/images", "public/img");
}

/**
 * Compile scripts
 */
mix.js("resources/assets/scripts/core.js", "public/js/core.min.js")
    .js("resources/assets/scripts/pages/listing.js", "public/js/listing.min.js")
    .js(
        "resources/assets/scripts/pages/library/index.js",
        "public/js/library.min.js"
    )
    .js(
        "resources/assets/scripts/pages/agents/writer/index.js",
        "public/js/content.min.js"
    )
    .js(
        "resources/assets/scripts/pages/agents/voiceover/index.js",
        "public/js/voiceover.min.js"
    )
    .js(
        "resources/assets/scripts/pages/agents/chat/index.js",
        "public/js/chat.min.js"
    )
    .js(
        "resources/assets/scripts/pages/home/index.js",
        "public/js/home.min.js"
    );

/**
 * Compile styles
 */
mix.sass("resources/assets/styles/core.scss", "public/style/core.min.css");
