<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f8edf1">
    {{-- Undangan hanya punya tampilan terang; cegah browser (Chrome Android, Samsung Internet,
         dll.) menggelapkan halaman secara otomatis saat HP memakai dark mode. --}}
    <meta name="color-scheme" content="only light">
    <meta name="supported-color-schemes" content="light only">
    <title data-i18n="meta.title">Undangan Pernikahan Sasqia & Rian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&family=Great+Vibes&family=Pinyon+Script&family=Playfair+Display:ital,wght@0,500;1,400;1,500&display=swap" rel="stylesheet">
    @php
        // Tambah ?v=<waktu ubah file> agar browser tamu selalu memuat CSS/JS terbaru
        // (server menyimpan cache file statis selama 7 hari).
        $versioned = fn (string $path) => asset($path) . '?v=' . @filemtime(public_path($path));
    @endphp
    <link rel="stylesheet" href="{{ $versioned('css/wedding-invitation.css') }}">
</head>
<body class="wedding-site">
    <audio id="music" loop preload="none">
        <source src="{{ asset('music.mp3') }}" type="audio/mpeg">
    </audio>

    <div class="ambient ambient-one" aria-hidden="true"></div>
    <div class="ambient ambient-two" aria-hidden="true"></div>

    {{-- COVER / ENVELOPE --}}
    <section id="cover" class="invitation-cover">
        {{-- Adegan sampul: layer PNG per-aset (dianimasikan GSAP).
             Jika salah satu PNG belum ada di public/img/cover/, otomatis memakai video. --}}
        <div class="cover-backdrop" style="background-image:url('{{ asset('img/cover/poster.jpg') }}')" aria-hidden="true"></div>
        <div class="cover-scene" aria-hidden="true">
            <div class="scene-stage">
                <video class="scene-video" muted playsinline preload="auto" poster="{{ asset('img/cover/poster.jpg') }}">
                    <source src="{{ asset('video/cover.mp4') }}" type="video/mp4">
                </video>
                <div class="scene-layers" hidden>
                    <img class="scene-layer layer-background" data-layer src="{{ asset('img/cover/background.png') }}" alt="">
                    <img class="scene-layer layer-arch" data-layer src="{{ asset('img/cover/arch.png') }}" alt="">
                    <img class="scene-layer layer-flowers-left" data-layer src="{{ asset('img/cover/flowers-left.png') }}" alt="">
                    <img class="scene-layer layer-flowers-right" data-layer src="{{ asset('img/cover/flowers-right.png') }}" alt="">
                    <img class="scene-layer layer-envelope" data-layer src="{{ asset('img/cover/envelope.png') }}" alt="">
                    <img class="scene-layer layer-wisteria-left" data-layer src="{{ asset('img/cover/wisteria-left.png') }}" alt="">
                    <img class="scene-layer layer-wisteria-right" data-layer src="{{ asset('img/cover/wisteria-right.png') }}" alt="">
                    <img class="scene-layer layer-wisteria-top" data-layer src="{{ asset('img/cover/wisteria-top.png') }}" alt="">
                </div>
                <div class="scene-petals"></div>
            </div>
        </div>

        <div class="cover-inner">
            <div class="cover-top">
                <h1 class="cover-title cover-logo">
                    <picture>
                        <source srcset="{{ asset('img/brand/wedding-logo-glow.webp') }}" type="image/webp">
                        <img src="{{ asset('img/brand/wedding-logo-glow.png') }}" alt="The wedding of Sasqia & Rian" width="1260" height="984">
                    </picture>
                </h1>
            </div>

            <button type="button" class="envelope-hotspot" onclick="openInvitation()" aria-label="Buka undangan" data-i18n-aria="cover.openAria">
                <span class="hotspot-ring"></span>
                <span class="hotspot-hint" data-i18n="cover.tap">Ketuk amplop</span>
            </button>

            <div class="cover-bottom">
                <div class="guest-greeting">
                    <span data-i18n="cover.dear">Kepada Yth.</span>
                    <strong>{{ $guest->name }}</strong>
                    <small class="guest-quota" data-i18n-html="cover.quota" data-n="{{ $guest->invitation_count }}">Undangan berlaku untuk <b>{{ $guest->invitation_count }} orang</b></small>
                </div>
                <button type="button" class="button button-primary open-button" onclick="openInvitation()">
                    <span class="button-icon">✉</span> <span data-i18n="cover.open">Buka Undangan</span>
                </button>
                <p class="cover-footnote" data-i18n="cover.footnote">Mohon doa restu dan kehadiran Anda</p>
                <div class="lang-switch lang-switch--cover" role="group" aria-label="Pilih bahasa" data-i18n-aria="lang.label">
                    <button type="button" data-lang="id" aria-pressed="true">ID</button>
                    <button type="button" data-lang="en" aria-pressed="false">EN</button>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN INVITATION --}}
    <main id="mainContent" class="invitation-main" aria-hidden="true">
        <header class="site-header">
            <a class="brand-mark" href="#home">S <span>&</span> R</a>
            <nav class="desktop-nav" aria-label="Navigasi undangan" data-i18n-aria="nav.aria">
                <a href="#home" data-i18n="nav.home">Beranda</a>
                <a href="#couple" data-i18n="nav.couple">Mempelai</a>
                <a href="#event" data-i18n="nav.event">Acara</a>
                <a href="#gallery" data-i18n="nav.gallery">Galeri</a>
                <a href="#rsvp">RSVP</a>
                <a href="#wishes" data-i18n="nav.wishes">Ucapan</a>
            </nav>
            <div class="header-actions">
                <div class="lang-switch" role="group" aria-label="Pilih bahasa" data-i18n-aria="lang.label">
                    <button type="button" data-lang="id" aria-pressed="true">ID</button>
                    <button type="button" data-lang="en" aria-pressed="false">EN</button>
                </div>
                <button class="music-toggle" type="button" aria-label="Putar atau jeda musik" aria-pressed="false">
                    <span class="music-note">♫</span><span class="music-label" data-i18n="music.label">Musik</span>
                </button>
            </div>
        </header>

        <section id="home" class="hero-section section-shell">
            <div class="hero-garden-art" aria-hidden="true">
                <span class="garden-orb orb-a"></span><span class="garden-orb orb-b"></span>
                <span class="garden-vine vine-left">❧</span><span class="garden-vine vine-right">❧</span>
            </div>
            <div class="hero-copy reveal">
                <h1 class="hero-logo">
                    <picture>
                        <source srcset="{{ asset('img/brand/wedding-logo.webp') }}" type="image/webp">
                        <img src="{{ asset('img/brand/wedding-logo.png') }}" alt="The wedding of Sasqia & Rian" width="1110" height="660">
                    </picture>
                </h1>
                <p class="hero-subtitle" data-i18n="hero.subtitle">Kami mengundangmu ke hari bahagia kami.</p>
                <div class="ornament-divider"><span>❦</span></div>
                <p class="hero-date" data-i18n="hero.date">SABTU, 23 JANUARI 2027</p>
                <p class="wedding-hashtag">#TIARAandtheDANIS</p>
                <a href="#event" class="text-link"><span data-i18n="hero.details">Lihat detail acara</span> <span>↓</span></a>
            </div>
            <div class="hero-photo-wrap reveal">
                <div class="hero-photo-frame">
                    <img src="{{ asset('img/scenes/scene-hero.jpg') }}" alt="Ilustrasi Sasqia dan Rian berdansa di depan gazebo" data-i18n-alt="hero.alt">
                </div>
                <span class="photo-caption" data-i18n="hero.caption">Sebuah kisah kecil, cinta untuk selamanya</span>
            </div>
            <div class="scroll-cue"><b data-i18n="hero.scroll">GULIR UNTUK MENJELAJAH</b> <span>↓</span></div>
        </section>

        <section class="quote-section scene-section section-shell">
            <div class="scene-bg" aria-hidden="true"><img src="{{ asset('img/scenes/scene-wisteria.jpg') }}" alt=""></div>
            <div class="scene-card reveal">
                <div class="ornament-divider"><span>❦</span></div>
                <p class="quote-verse" data-i18n="quote.verse">“Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya.”</p>
                <p class="quote-source" data-i18n="quote.source">QS. Ar-Rum: 21</p>
            </div>
        </section>

        <section id="couple" class="couple-section section-shell">
            <div class="section-heading reveal">
                <h2 data-i18n-html="couple.title">Memperkenalkan <em>Mempelai</em></h2>
                <p data-i18n="couple.lead">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami.</p>
            </div>
        </section>

        {{-- Profil mempelai: section ini "menempel" di layar, dan saat di-scroll ke bawah
             panoramanya bergeser dari Sasqia (kiri) ke Rian (kanan). Tanpa GSAP: bisa digeser manual. --}}
        <section class="couple-pan" aria-label="Profil mempelai" data-i18n-aria="couple.aria">
            <div class="pan-stage">
                <div class="pan-backdrop" style="background-image:url('{{ asset('img/scenes/couple-panorama.webp') }}')" aria-hidden="true"></div>
                <div class="pan-window">
                    <div class="pan-track">
                        <img src="{{ asset('img/scenes/couple-panorama.webp') }}" alt="Ilustrasi Sasqia dan Rian berdansa di taman wisteria" data-i18n-alt="couple.alt" draggable="false">
                    </div>
                    <div class="pan-dots" aria-hidden="true">
                        <span class="pan-dot pan-dot-bride">Sasqia</span>
                        <span class="pan-dot pan-dot-groom">Rian</span>
                    </div>
                    <p class="pan-hint" aria-hidden="true"><b data-i18n="couple.hint">Gulir atau geser untuk berkenalan</b> <span>↓</span></p>
                </div>
            </div>
            <div class="sr-only">
                <h3>Sasqia Tiara Megaprilia, SE</h3>
                <p data-i18n="couple.brideParents">Putri Kedua dari Bapak Patmanjaya, SE dan Ibu Ida Wahyuniari, SH</p>
                <h3>Rian Danis Adi Pratama, Ph.D</h3>
                <p data-i18n="couple.groomParents">Putra Pertama dari Bapak Ir. Adi Wijaya Sama dan Ibu Ir. Christina Indrias Dwi</p>
            </div>
        </section>

        <section class="countdown-section section-shell reveal">
            <p class="eyebrow" data-i18n="countdown.eyebrow">MENUJU HARI BAHAGIA</p>
            <h2 data-i18n="countdown.title">Menghitung Hari</h2>
            <p class="section-lead" data-i18n="countdown.lead">Menuju hari istimewa yang kami nantikan.</p>
            <div id="timer" class="countdown-grid">
                <div class="countdown-item"><strong id="days">0</strong><span data-i18n="countdown.days">Hari</span></div>
                <div class="countdown-item"><strong id="hours">0</strong><span data-i18n="countdown.hours">Jam</span></div>
                <div class="countdown-item"><strong id="minutes">0</strong><span data-i18n="countdown.minutes">Menit</span></div>
                <div class="countdown-item"><strong id="seconds">0</strong><span data-i18n="countdown.seconds">Detik</span></div>
            </div>
            <p class="countdown-date">23 · 01 · 2027</p>
        </section>

        <section id="event" class="event-section scene-section section-shell">
            <div class="scene-bg" aria-hidden="true"><img src="{{ asset('img/scenes/scene-fountain.jpg') }}" alt=""></div>
            <article class="event-card reveal">
                <p class="save-the-date"><span>Save</span> THE DATE</p>
                <h3 data-i18n-html="event.title">Pernikahan Sasqia &amp; Rian</h3>
                <div class="event-detail"><span>▦</span><div><small data-i18n="event.dateLabel">HARI & TANGGAL</small><strong data-i18n="event.date">Sabtu, 23 Januari 2027</strong></div></div>
                <div class="event-detail"><span>◷</span><div><small data-i18n="event.timeLabel">WAKTU</small><strong data-i18n="event.time">11.00 – 14.00 WIB</strong></div></div>
                <div class="event-detail"><span>⌖</span><div><small data-i18n="event.placeLabel">LOKASI</small><strong>Hotel Dafam Pacific Caesar Surabaya</strong><em>Jl. Dr. Ir. Soekarno No. 45C, Surabaya</em></div></div>
                <a class="button button-outline" href="https://www.google.com/maps/search/?api=1&amp;query=Hotel+Dafam+Pacific+Caesar+Surabaya" target="_blank" rel="noopener" data-i18n="event.maps">Lihat Lokasi di Google Maps ↗</a>
                <p class="event-note" data-i18n="event.note">Kehadiran dan doa restu Anda merupakan hadiah terindah bagi kami.</p>
            </article>
        </section>

        <section id="gallery" class="gallery-stage" aria-label="Galeri" data-i18n-aria="nav.gallery">
            {{-- Kolase foto: hitam-putih & samar di belakang sampul, berwarna & bisa digeser setelah "Buka Galeri". --}}
            <div class="gallery-collage" id="galleryCollage" tabindex="-1" aria-label="Galeri foto, geser ke kanan atau kiri" data-i18n-aria="gallery.trackAria">
                <figure class="collage-item"><img src="{{ asset('img/gallery/photo-1.jpg') }}" alt="Sasqia dan Rian menunjukkan cincin" data-i18n-alt="gallery.alt1" loading="lazy" draggable="false"></figure>
                <figure class="collage-item"><img src="{{ asset('img/gallery/photo-2.jpg') }}" alt="Sasqia dan Rian di depan dekorasi bunga" data-i18n-alt="gallery.alt2" loading="lazy" draggable="false"></figure>
                <figure class="collage-item"><img src="{{ asset('img/gallery/photo-3.jpg') }}" alt="Rian menyampaikan sambutan" data-i18n-alt="gallery.alt3" loading="lazy" draggable="false"></figure>
                <figure class="collage-item"><img src="{{ asset('img/gallery/photo-4.jpg') }}" alt="Sasqia dan Rian tertawa bersama" data-i18n-alt="gallery.alt4" loading="lazy" draggable="false"></figure>
                <figure class="collage-item"><img src="{{ asset('img/gallery/photo-5.jpg') }}" alt="Sasqia dan Rian duduk berdampingan" data-i18n-alt="gallery.alt5" loading="lazy" draggable="false"></figure>
            </div>

            <div class="gallery-cover">
                <div class="section-heading">
                    <p class="eyebrow" data-i18n="gallery.eyebrow">MOMEN KECIL KAMI</p>
                    <h2 data-i18n-html="gallery.title">Galeri <em>Cerita</em></h2>
                </div>
                <div class="gallery-frame">
                    <img src="{{ asset('img/gallery/photo-1.jpg') }}" alt="" loading="lazy">
                </div>
                <button type="button" class="button button-primary gallery-open" aria-controls="galleryCollage" aria-expanded="false" data-i18n="gallery.open">Buka Galeri</button>
            </div>

            <button type="button" class="gallery-close" aria-label="Tutup galeri" data-i18n-aria="gallery.close" hidden>✕</button>
            <div class="gallery-swipe" aria-hidden="true"><span class="swipe-hand">☝</span><span data-i18n="gallery.swipe">Geser</span></div>
        </section>

        <section id="rsvp" class="rsvp-section section-shell">
            <div class="section-heading reveal">
                <p class="eyebrow" data-i18n="rsvp.eyebrow">MOHON KONFIRMASI</p>
                <h2 data-i18n-html="rsvp.title">Konfirmasi <em>Kehadiran</em></h2>
                <p data-i18n="rsvp.lead">Mohon bantu kami mempersiapkan acara dengan mengisi konfirmasi berikut.</p>
            </div>
            @if (session('success'))
                <div class="notice notice-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice notice-error">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
            @endif

            <form id="rsvpForm" class="form-card reveal" method="POST" action="{{ route('rsvp.store', ['code' => $guest->short_code]) }}">
                @csrf
                <label for="nama" data-i18n="rsvp.name">Nama lengkap</label>
                <input type="text" name="nama" id="nama" value="{{ $guest->name }}" placeholder="Nama Anda" data-i18n-placeholder="form.namePh" required>
                <label for="kehadiran" data-i18n="rsvp.attend">Konfirmasi kehadiran</label>
                <select name="kehadiran" id="kehadiran" required>
                    <option value="Hadir" data-i18n="rsvp.yes">Dengan senang hati, saya hadir</option>
                    <option value="Tidak Hadir" data-i18n="rsvp.no">Maaf, saya belum dapat hadir</option>
                </select>
                <div id="jumlahwrapper">
                    <label for="jumlah" data-i18n="rsvp.count">Jumlah tamu yang hadir</label>
                    <select name="jumlah" id="jumlah" required>
                        @for ($i = 1; $i <= $guest->invitation_count; $i++)
                            <option value="{{ $i }}" data-i18n="rsvp.people" data-n="{{ $i }}">{{ $i }} orang</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="button button-primary form-submit"><span data-i18n="rsvp.submit">Kirim Konfirmasi</span> <span>→</span></button>
            </form>

            @if ($guest->rsvp_status === 'confirmed')
                @php
                    $shortCode = $guest->short_code;
                    $checkinUrl = route('invitation.show', ['code' => $shortCode]);
                @endphp
                <div class="guest-qr-section reveal" id="guest-qr">
                    <span class="qr-check">✓</span>
                    <h3 data-i18n="qr.title">RSVP Berhasil</h3>
                    <p data-i18n="qr.lead">Simpan QR Code ini dan tunjukkan kepada panitia saat hari acara.</p>
                    <div class="guest-qr-card">{!! QrCode::size(220)->generate($checkinUrl) !!}</div>
                    <strong class="guest-qr-code">{{ $shortCode }}</strong>
                    <small data-i18n="qr.note">QR ini merupakan tanda check-in undangan Anda.</small>
                </div>
            @endif
        </section>

        <section id="wishes" class="wishes-section section-shell">
            <div class="section-heading reveal">
                <p class="eyebrow" data-i18n="wishes.eyebrow">DARI LUBUK HATI</p>
                <h2 data-i18n-html="wishes.title">Ucapan & <em>Doa</em></h2>
                <p data-i18n="wishes.lead">Tuliskan doa terbaik untuk mengiringi awal perjalanan kami.</p>
            </div>
            @if (session('wish_success'))
                <div class="notice notice-success">{{ session('wish_success') }}</div>
            @endif
            <form method="POST" action="{{ route('wish.store', ['code' => $guest->short_code]) }}" class="wish-form form-card reveal">
                @csrf
                <label for="wishName" data-i18n="wishes.name">Nama</label>
                <input type="text" name="nama" id="wishName" placeholder="Nama Anda" data-i18n-placeholder="form.namePh" required>
                <label for="wishMessage" data-i18n="wishes.message">Ucapan dan doa</label>
                <textarea name="ucapan" id="wishMessage" placeholder="Tuliskan ucapan dan doa terbaik..." data-i18n-placeholder="wishes.messagePh" required></textarea>
                <button type="submit" class="button button-primary form-submit"><span data-i18n="wishes.submit">Kirim Ucapan</span> <span>♡</span></button>
            </form>

            <div class="wishes-list" id="wishesList">
                @forelse ($wishes as $wish)
                    <article class="wish-card reveal">
                        <div class="wish-avatar">{{ mb_strtoupper(mb_substr($wish->name, 0, 1)) }}</div>
                        <div class="wish-content">
                            <div class="wish-header"><div><h3>{{ $wish->name }}</h3><small class="wish-time">{{ $wish->created_at->timezone('Asia/Jakarta')->diffForHumans() }}</small></div><span class="wish-heart">♡</span></div>
                            <p class="wish-message">{{ $wish->message }}</p>
                        </div>
                    </article>
                @empty
                    <div class="wish-empty" id="wishEmpty"><span>♡</span><p data-i18n="wishes.empty">Belum ada ucapan dan doa.</p><small data-i18n="wishes.emptyNote">Jadilah yang pertama mengirimkan doa terbaik.</small></div>
                @endforelse
            </div>
            @if ($wishes->hasMorePages())
                <div class="wish-pagination" id="wishPagination"><button type="button" id="loadMoreWishes" class="button button-outline wish-load-more" data-i18n="wishes.more">Lihat ucapan lainnya ↓</button></div>
            @endif
        </section>

        <section class="closing-section section-shell reveal">
            <div class="closing-photo"><img src="{{ asset('img/scenes/scene-closing.jpg') }}" alt="Sasqia dan Rian" data-i18n-alt="closing.alt"></div>
            <div class="closing-flower">❀</div>
            <p class="eyebrow" data-i18n="closing.eyebrow">DENGAN CINTA DAN TERIMA KASIH</p>
            <h2 data-i18n="closing.title">Terima Kasih</h2>
            <p data-i18n="closing.lead">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
            <p class="closing-wedding-of" data-i18n="common.weddingOf">pernikahan</p>
            <p class="closing-sign">Sasqia <span>&</span> Rian</p>
            <p class="wedding-hashtag">#TIARAandtheDANIS</p>
            <small>23 · 01 · 2027</small>
        </section>

        <footer class="site-footer">a little note by <strong>nodetone</strong></footer>
    </main>

    <nav class="mobile-dock" aria-label="Navigasi cepat" data-i18n-aria="nav.quickAria">
        <a href="#home"><span>⌂</span><small data-i18n="nav.home">Beranda</small></a>
        <a href="#couple"><span>♡</span><small data-i18n="nav.couple">Mempelai</small></a>
        <a href="#event"><span>✧</span><small data-i18n="nav.event">Acara</small></a>
        <a href="#gallery"><span>▧</span><small data-i18n="nav.gallery">Galeri</small></a>
        <a href="#rsvp"><span>✓</span><small>RSVP</small></a>
    </nav>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="{{ $versioned('js/wedding-i18n.js') }}"></script>
    <script src="{{ $versioned('js/wedding-invitation.js') }}"></script>
    <script src="{{ $versioned('js/wedding-gsap.js') }}"></script>
</body>
</html>
