document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("apiForm");
    const responseContainer = document.getElementById("responseContainer");
    const responseText = document.getElementById("responseText");
    const loadingSpinner = document.getElementById("loadingSpinner");

    document.querySelectorAll("input, textarea").forEach(input => {
        input.addEventListener("focus", () => {
            input.style.border = "2px solid #ff7f50";
        });
        input.addEventListener("blur", () => {
            input.style.border = "none";
        });
    });

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        responseContainer.style.display = "none";
        loadingSpinner.style.display = "block";

        const url = document.getElementById("url").value;
        const method = document.getElementById("method").value;
        const data = document.getElementById("jsonData").value;

        try {
            const options = {
                method: method,
                headers: {
                    "Content-Type": "application/json"
                }
            };

            if (method !== "GET" && data.trim() !== "") {
                options.body = data;
            }

            const response = await fetch(url, options);
            const result = await response.json();

            responseText.textContent = JSON.stringify(result, null, 2);
            responseContainer.style.display = "block";
        } catch (error) {
            responseText.textContent = `Errore: ${error.message}`;
            responseContainer.style.display = "block";
        } finally {
            loadingSpinner.style.display = "none";
        }
    });

    document.querySelectorAll("button").forEach(button => {
        button.addEventListener("mousedown", () => {
            button.style.transform = "scale(0.95)";
        });
        button.addEventListener("mouseup", () => {
            button.style.transform = "scale(1)";
        });
    });
});
