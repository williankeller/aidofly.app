import { initBootstrap, setNonce } from "./globalSettings";
import { setupFieldValidation } from "./formValidation";
import { setupButtonActions } from "./buttonActions";


jQuery(($) => {
    initBootstrap();
    setNonce();
    setupButtonActions();
    setupFieldValidation();
});


