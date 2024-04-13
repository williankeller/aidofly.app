import $ from "jquery";

const $actionableButton = $(".btn-actionable");
const $fields = $(".needs-validation [required]");

const validateRequiredFields = () => {
    let allFilled = true;
    $fields.each(function () {
        if ($(this).val() === "") {
            allFilled = false;
            return false; // Break the loop
        }
    });

    $actionableButton.prop("disabled", !allFilled);
};

const setupFieldValidation = () => {
    $fields.on("keyup", validateRequiredFields);
};

export { validateRequiredFields, setupFieldValidation };
