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
    .js(
        "resources/assets/scripts/pages/agents/content.js",
        "public/js/agents/content.min.js"
    )
    .js(
        "resources/assets/scripts/services/list.js",
        "public/js/base/list.min.js"
    )
    .js(
        "resources/assets/scripts/pages/auth/auth.js",
        "public/js/auth/auth.min.js"
    )
    .sourceMaps();

/**
 * Compile styles
 */
mix.sass("resources/assets/styles/core.scss", "public/style/core.min.css");
