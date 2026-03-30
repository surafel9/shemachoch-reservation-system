        </main>
        </div>

        <script>
            const toggle = document.getElementById("darkToggle");

            if (localStorage.getItem("theme") === "dark") {
                document.documentElement.classList.add("dark");
                if (toggle) toggle.checked = true;
            }

            if (toggle) {
                toggle.addEventListener("change", () => {
                    if (toggle.checked) {
                        document.documentElement.classList.add("dark");
                        localStorage.setItem("theme", "dark");
                    } else {
                        document.documentElement.classList.remove("dark");
                        localStorage.setItem("theme", "light");
                    }
                });
            }
        </script>
        </body>

        </html>