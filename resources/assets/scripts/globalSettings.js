import $ from "jquery";
import "bootstrap";

// Setup jQuery and Bootstrap globally
window.$ = window.jQuery = $;

const initBootstrap = () => {
    //$('[data-bs-toggle="tooltip"]').tooltip();
};

const setNonce = () => {
    window.nonce = $('input[name="_token"]').val() || null;
};

export { initBootstrap, setNonce };
