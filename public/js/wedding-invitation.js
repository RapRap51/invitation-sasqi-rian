/* ==========================================================
   Wedding invitation interactions — Sasqia & Rian
========================================================== */
(() => {
    "use strict";

    const cover = document.getElementById("cover");
    const mainContent = document.getElementById("mainContent");
    const music = document.getElementById("music");
    const musicToggle = document.querySelector(".music-toggle");
    // Terjemahan (wedding-i18n.js). Fallback ke teks Indonesia bila file itu tidak dimuat.
    const t = (key, fallback) => (window.weddingI18n ? window.weddingI18n.t(key) : fallback);

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

    // Mode video: sampul diam (freeze) di frame pertama amplop.
    // Saat amplop / tombol diklik, video melompat ke adegan amplop terbuka
    // (OPEN_START-OPEN_END) dan memutarnya perlahan, baru undangan utama tampil.
    const COVER_OPEN_START = 2.6;
    const COVER_OPEN_END = 4.6;
    const COVER_OPEN_RATE = 0.55;   // < 1 = lebih lambat
    const coverVideo = cover ? cover.querySelector(".scene-video") : null;
    let opened = false;
    if (coverVideo) {
        const freeze = () => { if (!opened) { coverVideo.pause(); coverVideo.currentTime = 0; } };
        if (coverVideo.readyState >= 1) freeze();
        else coverVideo.addEventListener("loadedmetadata", freeze, { once: true });
    }

    function afterCoverIntro(done) {
        const videoMode = coverVideo && !window.__coverLayered && coverVideo.readyState >= 2;
        if (!videoMode) return window.setTimeout(done, 1600);
        let finished = false;
        const finish = () => { if (finished) return; finished = true; window.clearInterval(watch); coverVideo.pause(); done(); };
        // Lompat ke adegan amplop terbuka. Bila server tidak mendukung seek
        // (mis. `php artisan serve` tanpa HTTP Range), video tetap diputar dari awal
        // dengan kecepatan normal lalu melambat saat adegan terbuka dimulai.
        coverVideo.currentTime = COVER_OPEN_START;
        coverVideo.playbackRate = 1;
        coverVideo.play().catch(finish);
        const watch = window.setInterval(() => {
            const t = coverVideo.currentTime;
            if (t >= COVER_OPEN_START - 0.05 && coverVideo.playbackRate !== COVER_OPEN_RATE) coverVideo.playbackRate = COVER_OPEN_RATE;
            if (t >= COVER_OPEN_END || coverVideo.ended) finish();
        }, 30);
        // Batas aman: durasi adegan (termasuk kemungkinan putar dari awal) + sedikit jeda.
        window.setTimeout(finish, (COVER_OPEN_START + (COVER_OPEN_END - COVER_OPEN_START) / COVER_OPEN_RATE) * 1000 + 1500);
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
        musicToggle.dataset.i18nAria = playing ? "music.pause" : "music.play";
        musicToggle.setAttribute("aria-label", playing ? t("music.pause", "Jeda musik") : t("music.play", "Putar musik"));
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

    // Galeri ala kolase: sampul bingkai oval + "Buka Galeri".
    // Setelah dibuka, kolase menjadi berwarna dan bisa digeser kanan-kiri.
    const galleryStage = document.getElementById("gallery");
    const galleryCollage = document.getElementById("galleryCollage");
    if (galleryStage && galleryCollage) {
        const openBtn = galleryStage.querySelector(".gallery-open");
        const closeBtn = galleryStage.querySelector(".gallery-close");
        const swipe = galleryStage.querySelector(".gallery-swipe");

        const setOpen = open => {
            galleryStage.classList.toggle("is-open", open);
            if (openBtn) openBtn.setAttribute("aria-expanded", open ? "true" : "false");
            if (closeBtn) closeBtn.hidden = !open;
            galleryCollage.tabIndex = open ? 0 : -1;
            if (open) {
                galleryCollage.scrollLeft = 0;
                if (swipe) swipe.classList.add("is-visible");
                // Pas-kan galeri memenuhi layar (dikoreksi sekali bila tata letak bergeser saat scroll).
                const fit = behavior => window.scrollTo({ top: galleryStage.getBoundingClientRect().top + window.scrollY, behavior });
                fit("smooth");
                window.setTimeout(() => { if (Math.abs(galleryStage.getBoundingClientRect().top) > 4) fit("smooth"); }, 700);
                galleryCollage.focus({ preventScroll: true });
                document.dispatchEvent(new CustomEvent("gallery:opened"));
            } else {
                if (swipe) swipe.classList.remove("is-visible");
                galleryCollage.scrollTo({ left: 0, behavior: "smooth" });
                if (openBtn) openBtn.focus({ preventScroll: true });
                document.dispatchEvent(new CustomEvent("gallery:closed"));
            }
        };

        if (openBtn) openBtn.addEventListener("click", () => setOpen(true));
        if (closeBtn) closeBtn.addEventListener("click", () => setOpen(false));
        document.addEventListener("keydown", event => {
            if (event.key === "Escape" && galleryStage.classList.contains("is-open")) setOpen(false);
        });
        // Petunjuk "Geser" hilang setelah tamu mulai menggeser.
        galleryCollage.addEventListener("scroll", () => {
            if (swipe && galleryCollage.scrollLeft > 30) swipe.classList.remove("is-visible");
        }, { passive: true });
        // Roda mouse vertikal → geser horizontal (desktop).
        galleryCollage.addEventListener("wheel", event => {
            if (!galleryStage.classList.contains("is-open") || Math.abs(event.deltaX) > Math.abs(event.deltaY)) return;
            const max = galleryCollage.scrollWidth - galleryCollage.clientWidth;
            const atEdge = (event.deltaY < 0 && galleryCollage.scrollLeft <= 0) || (event.deltaY > 0 && galleryCollage.scrollLeft >= max - 1);
            if (atEdge) return; // biarkan halaman lanjut scroll di ujung galeri
            event.preventDefault();
            galleryCollage.scrollLeft += event.deltaY;
        }, { passive: false });
        galleryCollage.addEventListener("keydown", event => {
            const step = galleryCollage.clientWidth * 0.8;
            if (event.key === "ArrowRight") { event.preventDefault(); galleryCollage.scrollBy({ left: step, behavior: "smooth" }); }
            if (event.key === "ArrowLeft") { event.preventDefault(); galleryCollage.scrollBy({ left: -step, behavior: "smooth" }); }
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
            loadMoreButton.disabled = true;
            loadMoreButton.dataset.i18n = "wishes.loading";
            loadMoreButton.textContent = t("wishes.loading", "Memuat ucapan...");
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
                    loadMoreButton.dataset.i18n = "wishes.more";
                    loadMoreButton.textContent = t("wishes.more", "Lihat ucapan lainnya \u2193");
                }
            } catch (error) {
                loadMoreButton.disabled = false;
                loadMoreButton.dataset.i18n = "wishes.retry";
                loadMoreButton.textContent = t("wishes.retry", "Coba lagi");
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
