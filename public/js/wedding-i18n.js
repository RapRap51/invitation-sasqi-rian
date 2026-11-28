/* ==========================================================
   Terjemahan undangan — Bahasa Indonesia (utama) & English
   ----------------------------------------------------------
   - Teks Bahasa Indonesia ditulis langsung di invitation.blade.php
     dan dibaca otomatis dari halaman saat dimuat.
   - Teks English ada di kamus `en` di bawah. Untuk mengubah
     terjemahan, cukup ubah teks pada key yang sama.
   - Pilihan bahasa diingat di browser tamu. Link bisa memaksa
     bahasa tertentu: /undangan/KODE?lang=en atau ?lang=id
   - {n} = angka, {s} = akhiran jamak English ("s" bila n ≠ 1).
========================================================== */
(() => {
    "use strict";

    const STORAGE_KEY = "wedding-lang";
    const DEFAULT_LANG = "id";

    // Teks yang tidak tertulis di HTML (dipakai dari JavaScript) atau memakai angka.
    const id = {
        "cover.quota": "Undangan berlaku untuk <b>{n} orang</b>",
        "rsvp.people": "{n} orang",
        "music.play": "Putar musik",
        "music.pause": "Jeda musik",
        "wishes.loading": "Memuat ucapan...",
        "wishes.retry": "Coba lagi"
    };

    const en = {
        "meta.title": "The Wedding of Sasqia & Rian",
        "lang.label": "Choose language",

        "cover.openAria": "Open invitation",
        "cover.tap": "Tap the envelope",
        "cover.dear": "Dear",
        "cover.quota": "This invitation is valid for <b>{n} guest{s}</b>",
        "cover.open": "Open Invitation",
        "cover.footnote": "We kindly request your blessings and presence",

        "nav.aria": "Invitation navigation",
        "nav.quickAria": "Quick navigation",
        "nav.home": "Home",
        "nav.couple": "The Couple",
        "nav.event": "Event",
        "nav.gallery": "Gallery",
        "nav.wishes": "Wishes",
        "music.label": "Music",
        "music.play": "Play music",
        "music.pause": "Pause music",

        "common.weddingOf": "the wedding of",

        "hero.subtitle": "We are inviting you to our happily ever after…",
        "hero.date": "SATURDAY, 23 JANUARY 2027",
        "hero.details": "See event details",
        "hero.alt": "Illustration of Sasqia and Rian dancing in front of a gazebo",
        "hero.caption": "A little story, a lifetime of love",
        "hero.scroll": "SCROLL TO EXPLORE",

        "quote.verse": "“And among His signs is that He created for you spouses from among yourselves, so that you may find tranquility in them.”",
        "quote.source": "Qur'an, Ar-Rum: 21",

        "couple.title": "Meet the <em>Couple</em>",
        "couple.lead": "By the grace and blessing of Allah SWT, we joyfully announce the marriage of our beloved children.",
        "couple.aria": "The couple",
        "couple.alt": "Illustration of Sasqia and Rian dancing in a wisteria garden",
        "couple.hint": "Scroll to meet them",
        "couple.brideParents": "Second daughter of Mr. Patmanjaya, SE and Mrs. Ida Wahyuniari, SH",
        "couple.groomParents": "First son of Mr. Ir. Adi Wijaya Sama and Mrs. Ir. Christina Indrias Dwi",

        "countdown.eyebrow": "COUNTING THE DAYS",
        "countdown.title": "Counting Down",
        "countdown.lead": "Until the special day we have been waiting for.",
        "countdown.days": "Days",
        "countdown.hours": "Hours",
        "countdown.minutes": "Minutes",
        "countdown.seconds": "Seconds",

        "event.title": "The Wedding of Sasqia &amp; Rian",
        "event.dateLabel": "DAY & DATE",
        "event.date": "Saturday, 23 January 2027",
        "event.timeLabel": "TIME",
        "event.time": "10:00 AM – 2:00 PM (WIB)",
        "event.placeLabel": "VENUE",
        "event.maps": "View on Google Maps ↗",
        "event.note": "Your presence and blessings are the most precious gift to us.",

        "gallery.eyebrow": "OUR LITTLE MOMENTS",
        "gallery.title": "Our <em>Gallery</em>",
        "gallery.open": "Open Gallery",
        "gallery.close": "Close gallery",
        "gallery.swipe": "Swipe",
        "gallery.trackAria": "Photo gallery, swipe left or right",
        "gallery.alt1": "Sasqia and Rian showing their rings",
        "gallery.alt2": "Sasqia and Rian in front of the flower decoration",
        "gallery.alt3": "Rian giving a speech",
        "gallery.alt4": "Sasqia and Rian laughing together",
        "gallery.alt5": "Sasqia and Rian sitting side by side",

        "rsvp.eyebrow": "KINDLY RESPOND",
        "rsvp.title": "Confirm Your <em>Attendance</em>",
        "rsvp.lead": "Please help us prepare by filling in the confirmation below.",
        "rsvp.name": "Full name",
        "form.namePh": "Your name",
        "rsvp.attend": "Will you attend?",
        "rsvp.yes": "Joyfully, I will attend",
        "rsvp.no": "Sorry, I am unable to attend",
        "rsvp.count": "Number of guests attending",
        "rsvp.people": "{n} guest{s}",
        "rsvp.submit": "Send Confirmation",
        "qr.title": "RSVP Confirmed",
        "qr.lead": "Save this QR code and show it to our committee on the event day.",
        "qr.note": "This QR code is your invitation check-in pass.",

        "wishes.eyebrow": "WORDS FROM THE HEART",
        "wishes.title": "Wishes & <em>Prayers</em>",
        "wishes.lead": "Write your best wishes to accompany the beginning of our journey.",
        "wishes.name": "Name",
        "wishes.message": "Wishes and prayers",
        "wishes.messagePh": "Write your best wishes and prayers...",
        "wishes.submit": "Send Wishes",
        "wishes.empty": "No wishes yet.",
        "wishes.emptyNote": "Be the first to send your best wishes.",
        "wishes.more": "See more wishes ↓",
        "wishes.loading": "Loading wishes...",
        "wishes.retry": "Try again",

        "closing.alt": "Sasqia and Rian",
        "closing.eyebrow": "WITH LOVE AND GRATITUDE",
        "closing.title": "Thank You",
        "closing.lead": "It would be an honor and a joy for us if you could attend and give us your blessings."
    };

    const dict = { id, en };

    // Jenis isi yang bisa diterjemahkan: [atribut data, cara membaca, cara menulis]
    const kinds = [
        ["i18n", el => el.textContent.trim(), (el, v) => { el.textContent = v; }],
        ["i18nHtml", el => el.innerHTML.trim(), (el, v) => { el.innerHTML = v; }],
        ["i18nPlaceholder", el => el.getAttribute("placeholder"), (el, v) => el.setAttribute("placeholder", v)],
        ["i18nAria", el => el.getAttribute("aria-label"), (el, v) => el.setAttribute("aria-label", v)],
        ["i18nAlt", el => el.getAttribute("alt"), (el, v) => el.setAttribute("alt", v)]
    ];
    const selectorFor = name => "[data-" + name.replace(/[A-Z]/g, c => "-" + c.toLowerCase()) + "]";

    // Simpan teks Indonesia asli dari HTML sebagai kamus `id`.
    kinds.forEach(([name, read]) => {
        document.querySelectorAll(selectorFor(name)).forEach(el => {
            const key = el.dataset[name];
            if (!(key in id)) id[key] = read(el);
        });
    });

    function format(text, n) {
        if (n === undefined || n === null || n === "") return text;
        const num = Number(n);
        return text.replace(/\{n\}/g, n).replace(/\{s\}/g, num === 1 ? "" : "s");
    }

    let current = DEFAULT_LANG;

    function t(key, n) {
        const text = (dict[current] && dict[current][key]) ?? id[key] ?? key;
        return format(text, n);
    }

    function apply(root = document) {
        kinds.forEach(([name, , write]) => {
            root.querySelectorAll(selectorFor(name)).forEach(el => {
                // Teks yang sudah dipecah GSAP per kata tetap bisa diganti utuh.
                write(el, t(el.dataset[name], el.dataset.n));
                if (name === "i18n" && el.dataset.split) el.setAttribute("aria-label", el.textContent);
            });
        });
        document.documentElement.lang = current;
        document.querySelectorAll(".lang-switch [data-lang]").forEach(btn => {
            const active = btn.dataset.lang === current;
            btn.classList.toggle("is-active", active);
            btn.setAttribute("aria-pressed", active ? "true" : "false");
        });
    }

    function setLang(lang, remember = true) {
        if (!dict[lang]) return;
        current = lang;
        if (remember) {
            try { localStorage.setItem(STORAGE_KEY, lang); } catch (e) { /* mode privat */ }
        }
        apply();
        document.dispatchEvent(new CustomEvent("language:changed", { detail: { lang } }));
        if (window.ScrollTrigger) window.ScrollTrigger.refresh();
    }

    document.addEventListener("click", event => {
        const btn = event.target.closest && event.target.closest(".lang-switch [data-lang]");
        if (btn) setLang(btn.dataset.lang);
    });

    // Bahasa awal: ?lang= di link → pilihan tersimpan → Bahasa Indonesia.
    const fromUrl = new URLSearchParams(window.location.search).get("lang");
    let saved = null;
    try { saved = localStorage.getItem(STORAGE_KEY); } catch (e) { /* mode privat */ }
    if (fromUrl && dict[fromUrl]) setLang(fromUrl);
    else setLang(saved && dict[saved] ? saved : DEFAULT_LANG, false);

    window.weddingI18n = { t, setLang, apply, get lang() { return current; } };
})();
