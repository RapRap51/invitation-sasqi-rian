/* ==========================================================
   GSAP animations — Sasqia & Rian
   Membutuhkan gsap + ScrollTrigger (dimuat sebelum file ini).
   Jika GSAP gagal dimuat atau pengguna memilih "reduced motion",
   halaman otomatis memakai animasi CSS bawaan (.reveal).
========================================================== */
(() => {
    "use strict";

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (typeof window.gsap === "undefined" || typeof window.ScrollTrigger === "undefined" || reduceMotion) {
        return;
    }

    const { gsap, ScrollTrigger } = window;
    gsap.registerPlugin(ScrollTrigger);
    gsap.config({ nullTargetWarn: false });
    document.documentElement.classList.add("gsap-ready");
    window.__weddingGsap = true;

    const $ = (selector, scope = document) => scope.querySelector(selector);
    const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
    const EASE = "power3.out";

    // Pecah teks menjadi span per kata / huruf tanpa plugin SplitText.
    function splitText(el, mode = "chars") {
        if (!el || el.dataset.split) return [];
        el.dataset.split = mode;
        const text = el.textContent;
        el.setAttribute("aria-label", text.trim());
        el.textContent = "";
        const parts = mode === "words" ? text.trim().split(/(\s+)/) : Array.from(text);
        const nodes = [];
        parts.forEach(part => {
            if (/^\s+$/.test(part)) {
                el.appendChild(document.createTextNode(part));
                return;
            }
            const span = document.createElement("span");
            span.className = "split-unit";
            span.setAttribute("aria-hidden", "true");
            span.textContent = part;
            el.appendChild(span);
            nodes.push(span);
        });
        return nodes;
    }

    // Matikan CSS transition selama GSAP menganimasikan transform elemen.
    function withoutTransition(targets) {
        const els = gsap.utils.toArray(targets);
        els.forEach(el => { el.style.transition = "none"; });
        return {
            onComplete: () => els.forEach(el => { el.style.transition = ""; gsap.set(el, { clearProps: "transform" }); })
        };
    }

    function onScroll(trigger, start = "top 85%") {
        return { trigger, start, toggleActions: "play none none none" };
    }

    /* ---------- Ambient background (selalu bergerak) ---------- */
    gsap.to(".ambient-one", { x: 60, y: 80, scale: 1.12, duration: 14, ease: "sine.inOut", yoyo: true, repeat: -1 });
    gsap.to(".ambient-two", { x: -70, y: -60, scale: 1.1, duration: 16, ease: "sine.inOut", yoyo: true, repeat: -1 });

    /* ---------- COVER: adegan wisteria (per-aset) ---------- */
    const cover = $("#cover");
    const coverLoops = [];   // dihentikan saat amplop diklik
    const petalLoops = [];   // kelopak tetap jatuh sampai sampul tertutup
    if (cover) {
        const stage = $(".scene-stage", cover);
        const openButton = $(".open-button", cover);
        gsap.set(stage, { xPercent: -50, yPercent: -50, x: 0, y: 0 });

        // Teks & panel sampul
        const textTl = gsap.timeline({ defaults: { ease: EASE }, delay: .3 });
        textTl
            .from(".cover-top", { y: -40, autoAlpha: 0, duration: .9 })
            .from(".cover-title", { scale: .85, autoAlpha: 0, filter: "blur(8px)", duration: 1.1, ease: "power2.out" }, "-=.5")
            .from(".cover-bottom", { y: 50, autoAlpha: 0, duration: .9 }, "-=.6")
            .from(".guest-greeting > *", { y: 14, autoAlpha: 0, duration: .55, stagger: .1 }, "-=.5")
            .from(openButton, { y: 16, autoAlpha: 0, scale: .9, duration: .7, ease: "back.out(2)", ...withoutTransition(openButton) }, "-=.3")
            .from(".cover-footnote", { autoAlpha: 0, duration: .6 }, "-=.3")
            .from(".hotspot-ring, .hotspot-hint", { autoAlpha: 0, scale: .6, duration: .6, stagger: .15, ease: "back.out(2)" }, "-=.4");

        // Cincin penanda di segel amplop berdenyut + ikon tombol bergoyang
        coverLoops.push(
            gsap.to(".hotspot-ring", { scale: 1.6, autoAlpha: 0, duration: 1.6, ease: "power1.out", repeat: -1, delay: 3 }),
            gsap.to(".hotspot-hint", { y: -4, duration: .9, ease: "sine.inOut", yoyo: true, repeat: -1, delay: 3 }),
            gsap.to(".button-icon", { rotation: -12, y: -2, duration: .5, ease: "sine.inOut", yoyo: true, repeat: -1, repeatDelay: 1.6, delay: 3 })
        );

        // Kelopak wisteria berjatuhan (berlaku untuk mode video & layer)
        const petalBox = $(".scene-petals", cover);
        if (petalBox) {
            const rand = gsap.utils.random;
            const dropPetal = (el, first) => {
                const w = petalBox.clientWidth || window.innerWidth;
                const h = petalBox.clientHeight || window.innerHeight;
                gsap.set(el, {
                    x: rand(0, w), y: first ? rand(-h * .2, h * .6) : -24,
                    rotation: rand(0, 360), scale: rand(.55, 1.15), autoAlpha: rand(.55, .95)
                });
                const fall = gsap.to(el, {
                    y: h + 30, x: `+=${rand(-90, 90)}`, rotation: `+=${rand(180, 560)}`,
                    duration: rand(7, 13), ease: "none", delay: first ? rand(0, 2) : rand(0, 3),
                    onComplete: () => dropPetal(el, false)
                });
                petalLoops.push(fall);
            };
            for (let i = 0; i < 22; i++) {
                const petal = document.createElement("span");
                petal.className = "petal";
                petalBox.appendChild(petal);
                petalLoops.push(gsap.to(petal, { skewX: 25, duration: rand(.8, 1.6), ease: "sine.inOut", yoyo: true, repeat: -1 }));
                dropPetal(petal, true);
            }
        }

        // Animasi per-aset (hanya bila semua PNG layer tersedia)
        let sceneStarted = false;
        const startScene = layered => {
            if (sceneStarted) return;
            sceneStarted = true;
            if (!layered) {
                // Mode video: adegan muncul halus
                gsap.from(stage, { scale: 1.08, autoAlpha: 0, duration: 1.4, ease: "power2.out" });
                return;
            }
            const sway = (target, vars) => coverLoops.push(gsap.to(target, { ease: "sine.inOut", yoyo: true, repeat: -1, ...vars }));

            // Masuk
            gsap.timeline({ defaults: { ease: EASE } })
                .from(".layer-background", { scale: 1.12, autoAlpha: 0, duration: 1.6, ease: "power2.out" })
                .from(".layer-arch", { y: 40, autoAlpha: 0, duration: 1.1 }, .2)
                .from(".layer-flowers-left", { xPercent: -25, autoAlpha: 0, duration: 1.1 }, .4)
                .from(".layer-flowers-right", { xPercent: 25, autoAlpha: 0, duration: 1.1 }, .45)
                .from(".layer-wisteria-top", { yPercent: -30, autoAlpha: 0, duration: 1.3 }, .5)
                .from(".layer-wisteria-left", { xPercent: -30, autoAlpha: 0, duration: 1.2 }, .6)
                .from(".layer-wisteria-right", { xPercent: 30, autoAlpha: 0, duration: 1.2 }, .65)
                .from(".layer-envelope", { yPercent: -60, rotation: -18, autoAlpha: 0, duration: 1.4, ease: "bounce.out" }, .9)
                .add(() => {
                    // Loop: semua aset bergerak sendiri-sendiri
                    sway(".layer-background", { scale: 1.035, duration: 9 });
                    sway(".layer-wisteria-top", { rotation: 1.2, skewX: 1.5, duration: 3.6 });
                    sway(".layer-wisteria-left", { rotation: 2, skewY: -1, duration: 4.2 });
                    sway(".layer-wisteria-right", { rotation: -2, skewY: 1, duration: 4.8 });
                    sway(".layer-flowers-left", { rotation: 2.5, duration: 3.1 });
                    sway(".layer-flowers-right", { rotation: -2.5, duration: 3.4 });
                    gsap.set(".layer-envelope", { rotation: -4 });
                    sway(".layer-envelope", { rotation: 4, duration: 2, ease: "sine.inOut" });
                    sway(".layer-envelope", { y: -6, duration: 1, ease: "sine.inOut" });
                });
        };
        if (typeof window.__coverLayered === "boolean") startScene(window.__coverLayered);
        else document.addEventListener("cover:ready", e => startScene(e.detail.layered), { once: true });

        // Transisi saat undangan dibuka (klik amplop atau tombol)
        document.addEventListener("invitation:opening", () => {
            textTl.progress(1);
            coverLoops.forEach(t => t.kill());
            const layered = cover.querySelector(".cover-scene.is-layered");
            const tl = gsap.timeline({ defaults: { ease: "power2.inOut" } });
            tl.to(".hotspot-ring, .hotspot-hint", { autoAlpha: 0, duration: .25 }, 0)
              .to(".cover-top", { yPercent: -130, autoAlpha: 0, duration: 1.2 }, 0)
              .to(".cover-bottom", { yPercent: 130, autoAlpha: 0, duration: 1.2 }, 0);
            if (layered) {
                tl.to(".layer-envelope", { rotation: 0, y: 0, scale: 1.9, autoAlpha: 0, duration: .9, ease: "power2.in" }, .05)
                  .to(".layer-wisteria-left", { xPercent: -60, duration: .9 }, .05)
                  .to(".layer-wisteria-right", { xPercent: 60, duration: .9 }, .05)
                  .to(".layer-wisteria-top", { yPercent: -40, duration: .9 }, .05)
                  .to(".layer-background, .layer-arch, .layer-flowers-left, .layer-flowers-right", { scale: 1.15, duration: .9 }, .05);
            } else {
                // Video sedang memutar adegan amplop terbuka; kamera mendekat pelan.
                tl.to(stage, { scale: 1.18, transformOrigin: "47% 51%", duration: 4.2, ease: "power1.inOut" }, .05);
            }
        }, { once: true });
        document.addEventListener("invitation:opened", () => {
            window.setTimeout(() => petalLoops.forEach(t => t.kill()), 1000);
        }, { once: true });
    }

    /* ---------- MAIN (dibangun setelah undangan terbuka) ---------- */
    let mainBuilt = false;

    function buildMainAnimations() {
        if (mainBuilt) return;
        mainBuilt = true;

        gsap.set(".scroll-cue, .mobile-dock", { xPercent: -50, x: 0 });

        // Header & dock
        gsap.from(".site-header", { yPercent: -100, autoAlpha: 0, duration: .9, ease: EASE });
        gsap.from(".desktop-nav a, .brand-mark, .music-toggle", { y: -12, autoAlpha: 0, duration: .5, stagger: .05, delay: .3, ease: EASE });
        gsap.from(".mobile-dock", { y: 120, duration: 1, delay: .6, ease: "back.out(1.6)" });

        /* HERO */
        const heroTl = gsap.timeline({ defaults: { ease: EASE }, delay: .15 });
        heroTl
            .from(".hero-logo", { y: 30, scale: .92, autoAlpha: 0, filter: "blur(8px)", duration: 1.2, ease: "power2.out" })
            .from(".hero-subtitle", { y: 20, autoAlpha: 0, duration: .7 }, "-=.4")
            .from(".hero-copy .ornament-divider", { scaleX: 0, autoAlpha: 0, duration: .8 }, "-=.4")
            .from(".hero-date", { letterSpacing: "0.6em", autoAlpha: 0, duration: .8 }, "-=.5")
            .from(".hero-copy .wedding-hashtag", { y: 10, autoAlpha: 0, duration: .6 }, "-=.4")
            .from(".hero-copy .text-link", { y: 12, autoAlpha: 0, duration: .5 }, "-=.4")
            .from(".hero-photo-frame", {
                clipPath: "inset(100% 0% 0% 0% round 48% 48% 8px 8px)",
                rotation: -6, y: 50, autoAlpha: 0, duration: 1.3, ease: "power4.out"
            }, .2)
            .from(".hero-photo-frame img", { scale: 1.35, duration: 1.8, ease: "power2.out" }, .2)
            .from(".photo-caption", { y: 15, autoAlpha: 0, duration: .7 }, 1.1)
            .from(".scroll-cue", { autoAlpha: 0, y: 10, duration: .6 }, "-=.2");

        gsap.to(".scroll-cue span", { y: 6, duration: .8, ease: "sine.inOut", yoyo: true, repeat: -1 });
        gsap.to(".hero-copy .text-link span", { y: 4, duration: .7, ease: "sine.inOut", yoyo: true, repeat: -1 });

        // Ornamen taman melayang
        gsap.to(".orb-a", { y: -30, x: 20, duration: 6, ease: "sine.inOut", yoyo: true, repeat: -1 });
        gsap.to(".orb-b", { y: 25, x: -25, duration: 7, ease: "sine.inOut", yoyo: true, repeat: -1 });
        gsap.to(".vine-left", { rotation: 10, y: -12, duration: 4.5, ease: "sine.inOut", yoyo: true, repeat: -1 });
        gsap.to(".vine-right", { rotation: -10, y: 12, duration: 5, ease: "sine.inOut", yoyo: true, repeat: -1 });

        // Parallax foto hero saat scroll
        gsap.to(".hero-photo-wrap", {
            yPercent: -10, ease: "none",
            scrollTrigger: { trigger: ".hero-section", start: "top top", end: "bottom top", scrub: true }
        });
        gsap.to(".hero-copy", {
            yPercent: 12, autoAlpha: .4, ease: "none",
            scrollTrigger: { trigger: ".hero-section", start: "top top", end: "bottom top", scrub: true }
        });

        /* ADEGAN ILUSTRASI: parallax latar + kartu terangkat */
        $$(".scene-section").forEach(section => {
            const bg = $(".scene-bg", section);
            const img = $(".scene-bg img", section);
            if (bg) {
                gsap.fromTo(bg, { yPercent: -8 }, {
                    yPercent: 8, ease: "none",
                    scrollTrigger: { trigger: section, start: "top bottom", end: "bottom top", scrub: true }
                });
            }
            if (img) {
                gsap.fromTo(img, { scale: 1.15 }, {
                    scale: 1, ease: "none",
                    scrollTrigger: { trigger: section, start: "top bottom", end: "center center", scrub: true }
                });
            }
        });
        gsap.from(".scene-card", { y: 80, scale: .94, autoAlpha: 0, duration: 1.1, ease: EASE, scrollTrigger: onScroll(".quote-section", "top 70%") });

        /* QUOTE (ayat) */
        const quote = $(".quote-section");
        if (quote) {
            gsap.timeline({ scrollTrigger: onScroll(quote, "top 75%"), defaults: { ease: EASE } })
                .from($(".ornament-divider", quote), { scaleX: 0, autoAlpha: 0, duration: .7 })
                .from($$(".quote-verse, .quote-source", quote), { autoAlpha: 0, y: 14, duration: .7, stagger: .15 }, "-=.3");
        }

        /* SECTION HEADINGS */
        $$(".section-heading").forEach(heading => {
            gsap.timeline({ scrollTrigger: onScroll(heading), defaults: { ease: EASE } })
                .from($(".eyebrow", heading), { y: 14, autoAlpha: 0, letterSpacing: "0.5em", duration: .7 })
                .from($("h2", heading), { y: 35, autoAlpha: 0, duration: .8 }, "-=.45")
                .from($("h2 em", heading), { autoAlpha: 0, x: 20, duration: .6 }, "-=.4")
                .from($(":scope > p:last-child", heading), { y: 18, autoAlpha: 0, duration: .6 }, "-=.35");
        });

        /* COUPLE: scroll ke bawah, latar panorama bergeser kiri → kanan */
        const pan = $(".couple-pan");
        if (pan) {
            const stage = $(".pan-stage", pan);
            const win = $(".pan-window", pan);
            const track = $(".pan-track", pan);
            const distance = () => Math.max(0, track.scrollWidth - win.clientWidth);
            // Titik tengah teks nama di panorama (piksel gambar asli, lebar 1317).
            const PANO_W = 1317, BRIDE_X = 345, GROOM_X = 965;
            const focusX = imgX => {
                const scale = track.scrollWidth / PANO_W;
                return -gsap.utils.clamp(0, distance(), imgX * scale - win.clientWidth / 2);
            };

            const setupPan = () => {
                const panTl = gsap.timeline({
                    defaults: { ease: "none" },
                    scrollTrigger: {
                        trigger: pan,
                        start: "top top",
                        end: () => "+=" + Math.max(window.innerHeight * 1.2, distance() * 2.4),
                        pin: stage,
                        scrub: 0.8,
                        invalidateOnRefresh: true,
                        anticipatePin: 1
                    }
                });
                panTl
                    .fromTo(track, { x: () => focusX(BRIDE_X) }, { x: () => focusX(BRIDE_X), duration: .12 }) // jeda di mempelai wanita
                    .to(track, { x: () => focusX(GROOM_X), duration: 1, ease: "power1.inOut" })              // geser ke mempelai pria
                    .to(".pan-dot-bride", { opacity: .35, scale: 1, duration: .3 }, .45)
                    .fromTo(".pan-dot-groom", { opacity: .35, scale: 1 }, { opacity: 1, scale: 1.35, duration: .3 }, .45)
                    .to(".pan-hint", { autoAlpha: 0, duration: .2 }, .2)
                    .to({}, { duration: .12 });                                  // jeda sebentar di mempelai pria
            };
            // Lebar panorama sudah diketahui dari aspect-ratio di CSS, jadi bisa langsung dipasang.
            setupPan();

            gsap.set(".pan-dot-bride", { scale: 1.35 });
            gsap.from(win, { autoAlpha: 0, scale: .96, duration: 1, ease: EASE, scrollTrigger: onScroll(pan, "top 85%") });
            gsap.to(".pan-hint span", { y: 5, duration: .7, ease: "sine.inOut", yoyo: true, repeat: -1 });
        }

        /* COUNTDOWN */
        const countdown = $(".countdown-section");
        if (countdown) {
            gsap.timeline({ scrollTrigger: onScroll(countdown, "top 78%"), defaults: { ease: EASE } })
                .from($$(".eyebrow, h2, .section-lead", countdown), { y: 22, autoAlpha: 0, stagger: .12, duration: .7 })
                .from($$(".countdown-item", countdown), { scale: 0, rotation: -30, autoAlpha: 0, stagger: .12, duration: .8, ease: "back.out(2)" }, "-=.3")
                .from($(".countdown-date", countdown), { letterSpacing: "0.6em", autoAlpha: 0, duration: .8 }, "-=.3");

            // Detik "berdetak" setiap berganti.
            const seconds = $("#seconds");
            if (seconds && "MutationObserver" in window) {
                new MutationObserver(() => {
                    gsap.fromTo(seconds, { y: -10, autoAlpha: .2 }, { y: 0, autoAlpha: 1, duration: .45, ease: "back.out(2)", overwrite: true });
                }).observe(seconds, { childList: true, characterData: true, subtree: true });
            }
        }

        /* EVENT */
        const eventCard = $(".event-card");
        if (eventCard) {
            gsap.timeline({ scrollTrigger: onScroll(eventCard, "top 80%"), defaults: { ease: EASE } })
                .from(eventCard, { y: 70, scale: .94, autoAlpha: 0, duration: 1 })
                .from($(".save-the-date span", eventCard), { autoAlpha: 0, scale: .7, rotation: -8, filter: "blur(6px)", duration: 1, ease: "power2.out" }, "-=.5")
                .from($(".save-the-date", eventCard), { letterSpacing: "0.5em", duration: .9 }, "<")
                .from($("h3", eventCard), { y: 18, autoAlpha: 0, duration: .6 }, "-=.5")
                .from($$(".event-detail", eventCard), { x: -40, autoAlpha: 0, stagger: .15, duration: .7 }, "-=.3")
                .from($$(".event-detail > span", eventCard), { scale: 0, stagger: .15, duration: .5, ease: "back.out(3)" }, "<")
                .from($(".button", eventCard), { y: 20, autoAlpha: 0, duration: .6, ...withoutTransition($(".button", eventCard)) }, "-=.2")
                .from($(".event-note", eventCard), { autoAlpha: 0, duration: .6 }, "-=.2");
        }

        /* GALLERY: sampul bingkai oval masuk, foto kolase muncul bergiliran saat dibuka */
        const galleryStage = $("#gallery");
        if (galleryStage) {
            gsap.timeline({ scrollTrigger: onScroll(galleryStage, "top 70%"), defaults: { ease: EASE } })
                .from($(".gallery-frame", galleryStage), { scale: .85, autoAlpha: 0, duration: 1.1 })
                .from($(".gallery-open", galleryStage), { y: 16, autoAlpha: 0, duration: .6, ...withoutTransition($(".gallery-open", galleryStage)) }, "-=.4");
            gsap.to(".gallery-frame img", { scale: 1.06, duration: 6, ease: "sine.inOut", yoyo: true, repeat: -1 });
            gsap.to(".swipe-hand", { x: -14, rotation: -12, duration: .8, ease: "sine.inOut", yoyo: true, repeat: -1 });
            document.addEventListener("gallery:opened", () => {
                gsap.fromTo(".collage-item img", { y: 40, scale: .96 }, { y: 0, scale: 1, duration: .9, stagger: .08, ease: EASE, clearProps: "transform" });
            });
        }

        /* FORMS (RSVP & ucapan) */
        $$(".form-card").forEach(form => {
            const submit = $(".form-submit", form);
            gsap.timeline({ scrollTrigger: onScroll(form, "top 85%"), defaults: { ease: EASE } })
                .from(form, { y: 60, autoAlpha: 0, duration: .9 })
                .from($$(":scope > label, :scope > input, :scope > select, :scope > textarea, #jumlahwrapper", form), { y: 16, autoAlpha: 0, stagger: .07, duration: .5 }, "-=.5")
                .from(submit, { scale: .9, autoAlpha: 0, duration: .6, ease: "back.out(2)", ...withoutTransition(submit) }, "-=.2");
        });

        // Field input sedikit "mengangkat" saat fokus.
        $$(".form-card input, .form-card select, .form-card textarea").forEach(field => {
            field.addEventListener("focus", () => gsap.to(field, { y: -2, duration: .25, ease: "power2.out" }));
            field.addEventListener("blur", () => gsap.to(field, { y: 0, duration: .25, ease: "power2.out" }));
        });

        // Notifikasi sukses / error
        gsap.from(".notice", { y: -15, autoAlpha: 0, scale: .96, duration: .7, ease: "back.out(2)", scrollTrigger: onScroll(".rsvp-section", "top 80%") });

        /* QR */
        const qr = $("#guest-qr");
        if (qr) {
            gsap.timeline({ scrollTrigger: onScroll(qr, "top 85%"), defaults: { ease: EASE } })
                .from(qr, { y: 50, autoAlpha: 0, duration: .8 })
                .from($(".qr-check", qr), { scale: 0, rotation: -180, duration: .7, ease: "back.out(3)" }, "-=.4")
                .from($$("h3, p", qr), { y: 14, autoAlpha: 0, stagger: .1, duration: .5 }, "-=.3")
                .from($(".guest-qr-card", qr), { scale: .6, rotationY: 90, autoAlpha: 0, duration: .9, ease: "back.out(1.6)" }, "-=.2")
                .from($$(".guest-qr-code, small", qr), { autoAlpha: 0, y: 10, stagger: .1, duration: .5 }, "-=.3");
        }

        /* WISHES */
        animateWishCards($$(".wish-card, .wish-empty"));
        const wishesList = $("#wishesList");
        if (wishesList && "MutationObserver" in window) {
            // Kartu yang dimuat lewat tombol "Lihat ucapan lainnya".
            new MutationObserver(mutations => {
                const added = mutations.flatMap(m => Array.from(m.addedNodes)).filter(n => n.nodeType === 1 && n.classList.contains("wish-card"));
                if (added.length) {
                    gsap.from(added, { y: 40, autoAlpha: 0, scale: .96, duration: .7, stagger: .08, ease: EASE });
                    gsap.from(added.map(c => $(".wish-avatar", c)), { scale: 0, rotation: -90, duration: .6, stagger: .08, delay: .2, ease: "back.out(2.5)" });
                    ScrollTrigger.refresh();
                }
            }).observe(wishesList, { childList: true });
        }
        gsap.from(".wish-pagination", { y: 20, autoAlpha: 0, duration: .6, ease: EASE, scrollTrigger: onScroll(".wish-pagination", "top 95%") });

        /* CLOSING */
        const closing = $(".closing-section");
        if (closing) {
            gsap.timeline({ scrollTrigger: onScroll(closing, "top 75%"), defaults: { ease: EASE } })
                .from($(".closing-photo", closing), { clipPath: "inset(100% 0% 0% 0% round 48% 48% 10px 10px)", y: 40, duration: 1.3, ease: "power4.out" })
                .from($(".closing-photo img", closing), { scale: 1.3, duration: 1.6, ease: "power2.out" }, "<")
                .from($(".closing-flower", closing), { scale: 0, rotation: -270, autoAlpha: 0, duration: 1.2, ease: "back.out(1.8)" }, "-=.8")
                .from($$(".eyebrow, h2, :scope > p:not(.closing-sign):not(.closing-wedding-of):not(.wedding-hashtag)", closing), { y: 24, autoAlpha: 0, stagger: .14, duration: .7 }, "-=.6")
                .from($(".closing-wedding-of", closing), { y: 14, autoAlpha: 0, duration: .6 }, "-=.2")
                .from($(".closing-sign", closing), { scale: .6, autoAlpha: 0, filter: "blur(6px)", duration: 1.1, ease: "power2.out" }, "-=.2")
                .from($(".wedding-hashtag", closing), { y: 12, autoAlpha: 0, duration: .6 }, "-=.4")
                .from($(":scope > small", closing), { letterSpacing: "0.6em", autoAlpha: 0, duration: .8 }, "-=.5");
            gsap.to(".closing-flower", { rotation: 360, duration: 20, ease: "none", repeat: -1 });
        }

        /* FOOTER */
        gsap.from(".site-footer", { autoAlpha: 0, y: 20, duration: .8, scrollTrigger: onScroll(".site-footer", "top 98%") });

        // Hati di kartu ucapan berdenyut saat hover.
        document.addEventListener("mouseover", event => {
            const card = event.target.closest && event.target.closest(".wish-card");
            if (!card || card.dataset.hovered) return;
            card.dataset.hovered = "1";
            gsap.fromTo($(".wish-heart", card), { scale: 1 }, { scale: 1.35, duration: .25, yoyo: true, repeat: 1, ease: "power2.out", onComplete: () => delete card.dataset.hovered });
        });

        // Hitung ulang posisi ScrollTrigger setelah gambar/font termuat.
        window.addEventListener("load", () => ScrollTrigger.refresh());
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(() => ScrollTrigger.refresh());
        $$("img").forEach(img => { if (!img.complete) img.addEventListener("load", () => ScrollTrigger.refresh(), { once: true }); });
        ScrollTrigger.refresh();
    }

    function animateWishCards(items) {
        if (!items.length) return;
        gsap.set(items, { autoAlpha: 0 });
        ScrollTrigger.batch(items, {
            start: "top 90%",
            once: true,
            onEnter: batch => {
                gsap.fromTo(batch, { y: 40, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: .7, stagger: .1, ease: EASE, overwrite: true });
                gsap.fromTo(batch.map(c => $(".wish-avatar", c)).filter(Boolean), { scale: 0, rotation: -90 }, { scale: 1, rotation: 0, duration: .6, stagger: .1, delay: .2, ease: "back.out(2.5)" });
            }
        });
    }

    document.addEventListener("invitation:opened", buildMainAnimations);
    if ($("#mainContent.is-visible")) buildMainAnimations();
})();
