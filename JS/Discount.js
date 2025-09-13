function generateCode() {
    let random = Math.random().toString(36).substring(2, 8).toUpperCase();
    let code = "DISC-" + random;
    document.getElementById("code").value = code;
}