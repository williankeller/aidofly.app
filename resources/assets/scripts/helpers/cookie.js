const getCookie = (cookieName) => {
    const cookies = document.cookie.split(";");
    for (let cookie of cookies) {
        let [key, value] = cookie.trim().split("=");
        if (key === cookieName) {
            return decodeURIComponent(value);
        }
    }
    return null;
};

const setCookie = (name, value, days) => {
    let date = new Date();
    date.setTime(date.getTime() + 360 * 24 * 60 * 60 * 1000);
    expires = date.toUTCString();

    // JSON to string
    if (typeof value === "object") {
        value = JSON.stringify(value);
    }
    document.cookie = `${name}=${value}; path=/; expires=${expires};`;
};

const deleteCookie = (name) => {
    document.cookie = `${name}=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;`;
};

export { getCookie, setCookie, deleteCookie };
