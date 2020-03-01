const configOptions = {
    baseURL: "http://127.0.0.1:9504"
}

export default function config(key) {
    if(key === "all") {
        return configOptions;
    }
    return configOptions[key] || '';
}