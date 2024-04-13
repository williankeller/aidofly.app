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

/**
 * Compile styles
 */
mix.sass("resources/assets/styles/core.scss","public/style/core.min.css");