function openInvitation() {
    const cover = document.getElementById("cover");

    const mainContent = document.getElementById("mainContent");

    const music = document.getElementById("music");

    cover.style.display = "none";

    mainContent.style.display = "block";

    music.play().catch(() => {
        console.log("Autoplay musik diblokir browser.");
    });
}

// ======================================
// COUNTDOWN
// ======================================

const weddingDate = new Date("2027-04-04T09:00:00").getTime();

const countdown = setInterval(() => {
    const now = new Date().getTime();

    const distance = weddingDate - now;

    if (distance <= 0) {
        clearInterval(countdown);

        document.getElementById("days").innerHTML = 0;
        document.getElementById("hours").innerHTML = 0;
        document.getElementById("minutes").innerHTML = 0;
        document.getElementById("seconds").innerHTML = 0;

        return;
    }

    document.getElementById("days").innerHTML = Math.floor(
        distance / (1000 * 60 * 60 * 24),
    );

    document.getElementById("hours").innerHTML = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
    );

    document.getElementById("minutes").innerHTML = Math.floor(
        (distance % (1000 * 60 * 60)) / (1000 * 60),
    );

    document.getElementById("seconds").innerHTML = Math.floor(
        (distance % (1000 * 60)) / 1000,
    );
}, 1000);

// ======================================
// ANIMATION
// ======================================

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add("show");
        }
    });
});

document.querySelectorAll(".fade").forEach((el) => {
    observer.observe(el);
});

document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);

    if (params.get("opened") === "1") {
        openInvitation();

        setTimeout(() => {
            const qr = document.getElementById("guest-qr");

            if (qr) {
                qr.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        }, 300);

        window.history.replaceState(
            {},
            document.title,
            window.location.pathname,
        );
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);

    if (params.get("opened") === "1") {
        openInvitation();

        const cleanUrl = window.location.pathname;

        window.history.replaceState({}, document.title, cleanUrl);
    }
});

// ======================================
// LOAD MORE UCAPAN
// ======================================

const loadMoreButton = document.getElementById("loadMoreWishes");

const wishesList = document.getElementById("wishesList");

let wishesPage = 1;

if (loadMoreButton) {
    loadMoreButton.addEventListener("click", async function () {
        const code = window.location.pathname.split("/").filter(Boolean).pop();

        loadMoreButton.disabled = true;

        const originalText = loadMoreButton.innerText;

        loadMoreButton.innerText = "Memuat...";

        try {
            const nextPage = wishesPage + 1;

            const response = await fetch(
                `/undangan/${code}/wishes?page=${nextPage}`,
                {
                    headers: {
                        Accept: "application/json",
                    },
                },
            );

            if (!response.ok) {
                throw new Error("Gagal mengambil ucapan.");
            }

            const result = await response.json();

            result.data.forEach((wish) => {
                const card = document.createElement("div");

                card.className = "wish-card wish-enter";

                card.innerHTML = `

                            <div class="wish-avatar">
                                ${escapeHtml(wish.initial)}
                            </div>

                            <div class="wish-content">

                                <div class="wish-header">

                                    <div>

                                        <h3>
                                            ${escapeHtml(wish.name)}
                                        </h3>

                                        <small class="wish-time">
                                            ${escapeHtml(wish.time)}
                                        </small>

                                    </div>

                                    <span class="wish-heart">
                                        ♡
                                    </span>

                                </div>

                                <div class="wish-message">
                                    ${escapeHtml(wish.message)}
                                </div>

                            </div>
                        `;

                wishesList.appendChild(card);

                requestAnimationFrame(() => {
                    card.classList.add("wish-show");
                });
            });

            wishesPage = nextPage;

            if (!result.next_page) {
                loadMoreButton.parentElement.style.display = "none";
            } else {
                loadMoreButton.disabled = false;

                loadMoreButton.innerText = originalText;
            }
        } catch (error) {
            loadMoreButton.disabled = false;

            loadMoreButton.innerText = originalText;

            alert("Ucapan gagal dimuat. Silakan coba lagi.");
        }
    });
}

// ======================================
// HTML ESCAPE
// ======================================

function escapeHtml(value) {
    const div = document.createElement("div");

    div.textContent = value ?? "";

    return div.innerHTML;
}
