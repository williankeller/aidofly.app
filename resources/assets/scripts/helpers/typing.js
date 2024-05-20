const typing = (text, outputElement, speed = 30) => {
    let index = 0;

    // Clear the output element
    outputElement.textContent = "";

    function type() {
        if (index < text.length) {
            outputElement.textContent += text.charAt(index);
            index++;
            setTimeout(type, speed);
        }
    }
    type();
};

const typingInput = (text, outputElement, speed = 30) => {
    let index = -1;

    // Clear the output element
    outputElement.value = "";

    function type() {
        if (index < text.length) {
            outputElement.value += text.charAt(index);
            index++;
            setTimeout(type, speed);
        }
    }
    type();
};

export { typing, typingInput };
