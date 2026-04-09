document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("add-workshop");
    const container = document.getElementById("workshops-container");

    if (!btn || !container) return;

    btn.addEventListener("click", () => {
        const input = document.createElement("input");
        input.type = "text";
        input.name = "workshops[]";
        input.placeholder = "Workshop name";
        container.appendChild(input);
    });
});
