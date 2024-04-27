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
mix.js("resources/assets/scripts/core.js", `public/js/core.min.js`)
    .js("resources/assets/scripts/pages/content.js", "public/js/content.min.js")
    .js("resources/assets/scripts/pages/library.js", "public/js/library.min.js")
    .js("resources/assets/scripts/pages/listing.js", "public/js/listing.min.js")
    .js("resources/assets/scripts/pages/auth.js", "public/js/auth.min.js")
    .js(
        "resources/assets/scripts/pages/agents/voiceover/index.js",
        "public/js/voiceover.min.js"
    )
    .sourceMaps();

/**
 * Compile styles
 */
mix.sass("resources/assets/styles/core.scss", "public/style/core.min.css");
