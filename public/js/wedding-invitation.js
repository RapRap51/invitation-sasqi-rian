/* ==========================================================
   Wedding invitation interactions — Sasqia & Rian
========================================================== */
(() => {
    "use strict";

    const cover = document.getElementById("cover");
    const mainContent = document.getElementById("mainContent");
    const music = document.getElementById("music");
    const musicToggle = document.querySelector(".music-toggle");

    // Sampul berlayer: pakai PNG per-aset bila SEMUA layer tersedia,
    // jika tidak tetap memakai video sampul.
    const coverScene = document.querySelector(".cover-scene");
    const layerWrap = document.querySelector(".scene-layers");
    if (coverScene && layerWrap) {
        const layers = Array.from(layerWrap.querySelectorAll("img[data-layer]"));
        const loaded = layers.map(img => new Promise(resolve => {
            if (img.complete) return resolve(img.naturalWidth > 0);
            img.addEventListener("load", () => resolve(true), { once: true });
            img.addEventListener("error", () => resolve(false), { once: true });
        }));
        Promise.all(loaded).then(results => {
            const layered = layers.length > 0 && results.every(Boolean);
            if (layered) {
                layerWrap.hidden = false;
                coverScene.classList.add("is-layered");
                const video = coverScene.querySelector(".scene-video");
                if (video) { video.pause(); video.removeAttribute("autoplay"); }
            } else {
                layerWrap.remove();
            }
            window.__coverLayered = layered;
            document.dispatchEvent(new CustomEvent("cover:ready", { detail: { layered } }));
        });
    }

    // Mode video: sampul hanya mengulang adegan amplop berayun (0–LOOP_END detik).
    // Saat dibuka, video lanjut memutar adegan amplop terbuka ("We are inviting you")
    // sampai OPEN_END, baru undangan utama ditampilkan.
    const COVER_LOOP_END = 2.6;
    const COVER_OPEN_END = 4.6;
    const coverVideo = cover ? cover.querySelector(".scene-video") : null;
    let opened = false;
    if (coverVideo) {
        const keepLooping = () => {
            if (!opened && coverVideo.currentTime >= COVER_LOOP_END) coverVideo.currentTime = 0;
        };
        coverVideo.addEventListener("timeupdate", keepLooping);
        if ("requestVideoFrameCallback" in coverVideo) {
            const onFrame = () => { keepLooping(); if (!opened) coverVideo.requestVideoFrameCallback(onFrame); };
            coverVideo.requestVideoFrameCallback(onFrame);
        }
    }

    function afterCoverIntro(done) {
        const videoMode = coverVideo && !window.__coverLayered && coverVideo.readyState >= 2;
        if (!videoMode) return window.setTimeout(done, 900);
        let finished = false;
        const finish = () => { if (finished) return; finished = true; window.clearInterval(watch); done(); };
        coverVideo.playbackRate = 1.4;
        coverVideo.play().catch(finish);
        const watch = window.setInterval(() => {
            if (coverVideo.currentTime >= COVER_OPEN_END || coverVideo.ended) finish();
        }, 50);
        window.setTimeout(finish, 4500);
    }

    function revealMainContent() {
        if (!cover || !mainContent || opened) return;
        opened = true;
        cover.classList.add("is-opening");
        document.dispatchEvent(new CustomEvent("invitation:opening"));
        afterCoverIntro(() => {
            cover.classList.add("is-hidden");
            mainContent.classList.add("is-visible");
            mainContent.setAttribute("aria-hidden", "false");
            document.body.classList.add("invitation-opened");
            initRevealObserver();
            if (coverVideo) window.setTimeout(() => coverVideo.pause(), 900);
            document.dispatchEvent(new CustomEvent("invitation:opened"));
        });
    }

    window.openInvitation = function openInvitation() {
        if (opened) return;
        revealMainContent();
        if (music) {
            music.volume = 0.45;
            music.play().then(() => setMusicState(true)).catch(() => setMusicState(false));
        }
    };

    function setMusicState(playing) {
        if (!musicToggle) return;
        musicToggle.classList.toggle("is-playing", playing);
        musicToggle.setAttribute("aria-pressed", playing ? "true" : "false");
        musicToggle.setAttribute("aria-label", playing ? "Jeda musik" : "Putar musik");
    }

    if (musicToggle && music) {
        musicToggle.addEventListener("click", () => {
            if (music.paused) {
                music.play().then(() => setMusicState(true)).catch(() => setMusicState(false));
            } else {
                music.pause();
                setMusicState(false);
            }
        });
        music.addEventListener("pause", () => setMusicState(false));
        music.addEventListener("play", () => setMusicState(true));
    }

    // Countdown: tanggal acara yang sudah digunakan pada project.
    const weddingDate = new Date("2027-01-23T10:00:00+07:00").getTime();
    const countdownIds = ["days", "hours", "minutes", "seconds"];
    function updateCountdown() {
        const distance = weddingDate - Date.now();
        const values = distance <= 0 ? [0, 0, 0, 0] : [
            Math.floor(distance / 86400000),
            Math.floor((distance % 86400000) / 3600000),
            Math.floor((distance % 3600000) / 60000),
            Math.floor((distance % 60000) / 1000)
        ];
        countdownIds.forEach((id, index) => {
            const node = document.getElementById(id);
            if (node) node.textContent = String(values[index]).padStart(2, "0");
        });
    }
    updateCountdown();
    window.setInterval(updateCountdown, 1000);

    // Scroll reveal
    let revealObserver;
    function initRevealObserver() {
        const elements = document.querySelectorAll(".reveal:not(.is-visible)");
        if (!("IntersectionObserver" in window)) {
            elements.forEach(el => el.classList.add("is-visible"));
            return;
        }
        if (!revealObserver) {
            revealObserver = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: "0px 0px -35px 0px" });
        }
        elements.forEach(el => revealObserver.observe(el));
    }

    // RSVP quantity field
    const attendance = document.getElementById("kehadiran");
    const quantityWrapper = document.getElementById("jumlahwrapper");
    if (attendance && quantityWrapper) {
        const updateQuantityVisibility = () => {
            const absent = attendance.value === "Tidak Hadir";
            quantityWrapper.style.opacity = absent ? "0" : "1";
            quantityWrapper.style.maxHeight = absent ? "0" : "120px";
            quantityWrapper.style.pointerEvents = absent ? "none" : "auto";
        };
        attendance.addEventListener("change", updateQuantityVisibility);
        updateQuantityVisibility();
    }

    // Gallery lightbox
    const lightbox = document.getElementById("galleryLightbox");
    if (lightbox) {
        const lightboxImage = lightbox.querySelector("img");
        const closeButton = lightbox.querySelector(".lightbox-close");
        document.querySelectorAll("[data-gallery]").forEach(item => {
            item.addEventListener("click", () => {
                lightboxImage.src = item.dataset.gallery;
                lightbox.classList.add("is-open");
                lightbox.setAttribute("aria-hidden", "false");
                document.body.style.overflow = "hidden";
            });
        });
        const closeLightbox = () => {
            lightbox.classList.remove("is-open");
            lightbox.setAttribute("aria-hidden", "true");
            lightboxImage.src = "";
            document.body.style.overflow = "";
        };
        closeButton.addEventListener("click", closeLightbox);
        lightbox.addEventListener("click", event => {
            if (event.target === lightbox) closeLightbox();
        });
        document.addEventListener("keydown", event => {
            if (event.key === "Escape") closeLightbox();
        });
    }

    // Load more guest wishes
    const loadMoreButton = document.getElementById("loadMoreWishes");
    const wishesList = document.getElementById("wishesList");
    let wishesPage = 1;
    function escapeHtml(value) {
        return String(value ?? "").replace(/[&<>"']/g, char => ({
            "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
        })[char]);
    }
    if (loadMoreButton && wishesList) {
        loadMoreButton.addEventListener("click", async () => {
            const segments = window.location.pathname.split("/").filter(Boolean);
            const code = segments[segments.length - 1];
            const originalText = loadMoreButton.textContent;
            loadMoreButton.disabled = true;
            loadMoreButton.textContent = "Memuat ucapan...";
            try {
                const response = await fetch(`/undangan/${encodeURIComponent(code)}/wishes?page=${wishesPage + 1}`, {
                    headers: { "Accept": "application/json", "X-Requested-With": "XMLHttpRequest" }
                });
                if (!response.ok) throw new Error("Gagal mengambil ucapan.");
                const result = await response.json();
                (result.data || []).forEach(wish => {
                    const card = document.createElement("article");
                    card.className = "wish-card";
                    card.innerHTML = `
                        <div class="wish-avatar">${escapeHtml(wish.initial)}</div>
                        <div class="wish-content">
                            <div class="wish-header">
                                <div><h3>${escapeHtml(wish.name)}</h3><small class="wish-time">${escapeHtml(wish.time)}</small></div>
                                <span class="wish-heart">♡</span>
                            </div>
                            <p class="wish-message">${escapeHtml(wish.message)}</p>
                        </div>`;
                    wishesList.appendChild(card);
                });
                wishesPage += 1;
                if (!result.next_page) {
                    const pagination = document.getElementById("wishPagination");
                    if (pagination) pagination.remove();
                } else {
                    loadMoreButton.disabled = false;
                    loadMoreButton.textContent = originalText;
                }
            } catch (error) {
                loadMoreButton.disabled = false;
                loadMoreButton.textContent = "Coba lagi";
            }
        });
    }

    // Direct QR links can open the invitation and move to the QR area.
    document.addEventListener("DOMContentLoaded", () => {
        const params = new URLSearchParams(window.location.search);
        if (params.get("opened") === "1") {
            revealMainContent();
            window.setTimeout(() => {
                const qr = document.getElementById("guest-qr");
                if (qr) qr.scrollIntoView({ behavior: "smooth", block: "center" });
            }, 950);
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
})();
