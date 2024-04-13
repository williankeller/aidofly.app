import $ from "jquery";

const $actionableButton = $(".btn-actionable");

const setupButtonActions = () => {
    $actionableButton.on("click", function () {
        let $button = $(this);
        let actionable = $button.data("actionable");
        $button.prop("disabled", true).addClass("disabled loading");

        $(`#${actionable}`).trigger("submit");

        setTimeout(() => {
            $button.prop("disabled", false).removeClass("disabled loading");
        }, 3000);
    });
};

export { setupButtonActions };
