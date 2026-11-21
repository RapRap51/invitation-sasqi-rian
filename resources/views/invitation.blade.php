<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f8edf1">
    <title>Undangan Pernikahan Sasqia & Rian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&family=Great+Vibes&family=Pinyon+Script&family=Playfair+Display:ital,wght@0,500;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/wedding-invitation.css') }}">
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
                <video class="scene-video" autoplay muted loop playsinline preload="auto" poster="{{ asset('img/cover/poster.jpg') }}">
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
                <p class="eyebrow">THE WEDDING INVITATION</p>
                <p class="cover-wedding-of">the wedding of</p>
                <h1 class="cover-title">Sasqia <span>&</span> Rian</h1>
                <p class="cover-date">23 · 01 · 2027</p>
            </div>

            <button type="button" class="envelope-hotspot" onclick="openInvitation()" aria-label="Buka undangan">
                <span class="hotspot-ring"></span>
                <span class="hotspot-hint">Ketuk amplop</span>
            </button>

            <div class="cover-bottom">
                <div class="guest-greeting">
                    <span>Kepada Yth.</span>
                    <strong>{{ $guest->name }}</strong>
                    <small class="guest-quota">Undangan berlaku untuk <b>{{ $guest->invitation_count }} orang</b></small>
                </div>
                <button type="button" class="button button-primary open-button" onclick="openInvitation()">
                    <span class="button-icon">✉</span> Buka Undangan
                </button>
                <p class="cover-footnote">Mohon doa restu dan kehadiran Anda</p>
            </div>
        </div>
    </section>

    {{-- MAIN INVITATION --}}
    <main id="mainContent" class="invitation-main" aria-hidden="true">
        <header class="site-header">
            <a class="brand-mark" href="#home">S <span>&</span> R</a>
            <nav class="desktop-nav" aria-label="Navigasi undangan">
                <a href="#home">Beranda</a>
                <a href="#couple">Mempelai</a>
                <a href="#event">Acara</a>
                <a href="#gallery">Galeri</a>
                <a href="#rsvp">RSVP</a>
                <a href="#wishes">Ucapan</a>
            </nav>
            <button class="music-toggle" type="button" aria-label="Putar atau jeda musik" aria-pressed="false">
                <span class="music-note">♫</span><span class="music-label">Musik</span>
            </button>
        </header>

        <section id="home" class="hero-section section-shell">
            <div class="hero-garden-art" aria-hidden="true">
                <span class="garden-orb orb-a"></span><span class="garden-orb orb-b"></span>
                <span class="garden-vine vine-left">❧</span><span class="garden-vine vine-right">❧</span>
            </div>
            <div class="hero-copy reveal">
                <p class="hero-wedding-of">the wedding of</p>
                <h1 class="hero-names"><span>Sasqia</span><i>&</i><span>Rian</span></h1>
                <p class="hero-subtitle">We are inviting you to our happily ever after…</p>
                <p class="hero-subtitle-id">Kami mengundangmu ke hari bahagia kami.</p>
                <div class="ornament-divider"><span>❦</span></div>
                <p class="hero-date">SABTU, 23 JANUARI 2027</p>
                <a href="#event" class="text-link">Lihat detail acara <span>↓</span></a>
            </div>
            <div class="hero-photo-wrap reveal">
                <div class="hero-photo-frame">
                    <img src="{{ asset('img/scenes/scene-hero.jpg') }}" alt="Ilustrasi Sasqia dan Rian berdansa di depan gazebo">
                </div>
                <span class="photo-caption">A little story, a lifetime of love</span>
            </div>
            <div class="scroll-cue">SCROLL TO EXPLORE <span>↓</span></div>
        </section>

        <section class="quote-section scene-section section-shell">
            <div class="scene-bg" aria-hidden="true"><img src="{{ asset('img/scenes/scene-wisteria.jpg') }}" alt=""></div>
            <div class="scene-card reveal">
                <p class="eyebrow">A LITTLE FAIRYTALE</p>
                <div class="quote-mark">“</div>
                <blockquote>
                    Some fairytale begin with a heart that had forgotten how to hope, and someone who loved it back to life.
                </blockquote>
                <p class="quote-translation">Sebagian dongeng dimulai dari hati yang lupa caranya berharap, lalu dicintai hingga hidup kembali.</p>
                <div class="ornament-divider"><span>❦</span></div>
                <p class="quote-verse">“Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya.”</p>
                <p class="quote-source">QS. Ar-Rum: 21</p>
            </div>
        </section>

        <section id="couple" class="couple-section section-shell">
            <div class="section-heading reveal">
                <p class="eyebrow">THE PEOPLE BEHIND THE STORY</p>
                <h2>Memperkenalkan <em>Mempelai</em></h2>
                <p>Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami.</p>
            </div>
        </section>

        {{-- Profil mempelai: section ini "menempel" di layar, dan saat di-scroll ke bawah
             panoramanya bergeser dari Sasqia (kiri) ke Rian (kanan). Tanpa GSAP: bisa digeser manual. --}}
        <section class="couple-pan" aria-label="Profil mempelai">
            <div class="pan-stage">
                <div class="pan-backdrop" style="background-image:url('{{ asset('img/scenes/couple-panorama.jpg') }}')" aria-hidden="true"></div>
                <div class="pan-window">
                    <div class="pan-track">
                        <img src="{{ asset('img/scenes/couple-panorama.jpg') }}" alt="Ilustrasi Sasqia dan Rian berdansa di taman wisteria">
                    </div>
                    <div class="pan-dots" aria-hidden="true">
                        <span class="pan-dot pan-dot-bride">Sasqia</span>
                        <span class="pan-dot pan-dot-groom">Rian</span>
                    </div>
                    <p class="pan-hint" aria-hidden="true">Scroll untuk berkenalan <span>↓</span></p>
                </div>
            </div>
            <div class="sr-only">
                <h3>Sasqia Tiara Megaprilia, SE</h3>
                <p>Putri Kedua dari Bapak Patmanjaya, SE dan Ibu Ida Wahyuniari, SH</p>
                <h3>Rian Danis Adi Pratama, Ph.D</h3>
                <p>Putra Pertama dari Bapak Ir. Adi Wijaya Sama dan Ibu Ir. Christina Indrias Dwi</p>
            </div>
        </section>

        <section class="countdown-section section-shell reveal">
            <p class="eyebrow">COUNTING THE DAYS</p>
            <h2>Menghitung Hari</h2>
            <p class="section-lead">Menuju hari istimewa yang kami nantikan.</p>
            <div id="timer" class="countdown-grid">
                <div class="countdown-item"><strong id="days">0</strong><span>Hari</span></div>
                <div class="countdown-item"><strong id="hours">0</strong><span>Jam</span></div>
                <div class="countdown-item"><strong id="minutes">0</strong><span>Menit</span></div>
                <div class="countdown-item"><strong id="seconds">0</strong><span>Detik</span></div>
            </div>
            <p class="countdown-date">23 · 01 · 2027</p>
        </section>

        <section id="event" class="event-section scene-section section-shell">
            <div class="scene-bg" aria-hidden="true"><img src="{{ asset('img/scenes/scene-fountain.jpg') }}" alt=""></div>
            <article class="event-card reveal">
                <p class="save-the-date"><span>Save</span> THE DATE</p>
                <h3>Pernikahan Sasqia &amp; Rian</h3>
                <div class="event-detail"><span>▦</span><div><small>HARI & TANGGAL</small><strong>Sabtu, 23 Januari 2027</strong></div></div>
                <div class="event-detail"><span>◷</span><div><small>WAKTU</small><strong>10.00 – 14.00 WIB</strong></div></div>
                <div class="event-detail"><span>⌖</span><div><small>LOKASI</small><strong>Hotel Dafam Pacific Caesar Surabaya</strong><em>Jl. Dr. Ir. Soekarno No. 45C, Surabaya</em></div></div>
                <a class="button button-outline" href="https://www.google.com/maps/search/?api=1&amp;query=Hotel+Dafam+Pacific+Caesar+Surabaya" target="_blank" rel="noopener">Lihat Lokasi di Google Maps ↗</a>
                <p class="event-note">Kehadiran dan doa restu Anda merupakan hadiah terindah bagi kami.</p>
            </article>
        </section>

        <section id="gallery" class="gallery-section section-shell">
            <div class="section-heading reveal">
                <p class="eyebrow">OUR LITTLE MOMENTS</p>
                <h2>Galeri <em>Cerita</em></h2>
                <p>Potongan kecil dari perjalanan yang kami syukuri.</p>
            </div>
            <div class="gallery-grid">
                <button class="gallery-item gallery-tall reveal" type="button" data-gallery="{{ asset('img/scenes/gallery-1.jpg') }}"><img src="{{ asset('img/scenes/gallery-1.jpg') }}" alt="Sasqia dan Rian berdansa di taman"><span>01</span></button>
                <button class="gallery-item reveal" type="button" data-gallery="{{ asset('img/scenes/gallery-2.jpg') }}"><img src="{{ asset('img/scenes/gallery-2.jpg') }}" alt="Sasqia"><span>02</span></button>
                <button class="gallery-item reveal" type="button" data-gallery="{{ asset('img/scenes/gallery-3.jpg') }}"><img src="{{ asset('img/scenes/gallery-3.jpg') }}" alt="Rian"><span>03</span></button>
                <button class="gallery-item reveal" type="button" data-gallery="{{ asset('img/scenes/gallery-4.jpg') }}"><img src="{{ asset('img/scenes/gallery-4.jpg') }}" alt="Sasqia dan Rian"><span>04</span></button>
            </div>
        </section>

        <section id="rsvp" class="rsvp-section section-shell">
            <div class="section-heading reveal">
                <p class="eyebrow">KINDLY RESPOND</p>
                <h2>Konfirmasi <em>Kehadiran</em></h2>
                <p>Mohon bantu kami mempersiapkan acara dengan mengisi konfirmasi berikut.</p>
            </div>
            @if (session('success'))
                <div class="notice notice-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice notice-error">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
            @endif

            <form id="rsvpForm" class="form-card reveal" method="POST" action="{{ route('rsvp.store', ['code' => str_replace('RAFIINA-', '', $guest->unique_code)]) }}">
                @csrf
                <label for="nama">Nama lengkap</label>
                <input type="text" name="nama" id="nama" value="{{ $guest->name }}" placeholder="Nama Anda" required>
                <label for="kehadiran">Konfirmasi kehadiran</label>
                <select name="kehadiran" id="kehadiran" required>
                    <option value="Hadir">Dengan senang hati, saya hadir</option>
                    <option value="Tidak Hadir">Maaf, saya belum dapat hadir</option>
                </select>
                <div id="jumlahwrapper">
                    <label for="jumlah">Jumlah tamu yang hadir</label>
                    <select name="jumlah" id="jumlah" required>
                        @for ($i = 1; $i <= $guest->invitation_count; $i++)
                            <option value="{{ $i }}">{{ $i }} orang</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="button button-primary form-submit">Kirim Konfirmasi <span>→</span></button>
            </form>

            @if ($guest->rsvp_status === 'confirmed')
                @php
                    $shortCode = str_replace('RAFIINA-', '', $guest->unique_code);
                    $checkinUrl = route('invitation.show', ['code' => $shortCode]);
                @endphp
                <div class="guest-qr-section reveal" id="guest-qr">
                    <span class="qr-check">✓</span>
                    <h3>RSVP Berhasil</h3>
                    <p>Simpan QR Code ini dan tunjukkan kepada panitia saat hari acara.</p>
                    <div class="guest-qr-card">{!! QrCode::size(220)->generate($checkinUrl) !!}</div>
                    <strong class="guest-qr-code">{{ $shortCode }}</strong>
                    <small>QR ini merupakan tanda check-in undangan Anda.</small>
                </div>
            @endif
        </section>

        <section id="wishes" class="wishes-section section-shell">
            <div class="section-heading reveal">
                <p class="eyebrow">WORDS FROM THE HEART</p>
                <h2>Ucapan & <em>Doa</em></h2>
                <p>Tuliskan doa terbaik untuk mengiringi awal perjalanan kami.</p>
            </div>
            @if (session('wish_success'))
                <div class="notice notice-success">{{ session('wish_success') }}</div>
            @endif
            <form method="POST" action="{{ route('wish.store', ['code' => str_replace('RAFIINA-', '', $guest->unique_code)]) }}" class="wish-form form-card reveal">
                @csrf
                <label for="wishName">Nama</label>
                <input type="text" name="nama" id="wishName" placeholder="Nama Anda" required>
                <label for="wishMessage">Ucapan dan doa</label>
                <textarea name="ucapan" id="wishMessage" placeholder="Tuliskan ucapan dan doa terbaik..." required></textarea>
                <button type="submit" class="button button-primary form-submit">Kirim Ucapan <span>♡</span></button>
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
                    <div class="wish-empty" id="wishEmpty"><span>♡</span><p>Belum ada ucapan dan doa.</p><small>Jadilah yang pertama mengirimkan doa terbaik.</small></div>
                @endforelse
            </div>
            @if ($wishes->hasMorePages())
                <div class="wish-pagination" id="wishPagination"><button type="button" id="loadMoreWishes" class="button button-outline wish-load-more">Lihat ucapan lainnya ↓</button></div>
            @endif
        </section>

        <section class="closing-section section-shell reveal">
            <div class="closing-photo"><img src="{{ asset('img/scenes/scene-closing.jpg') }}" alt="Sasqia dan Rian"></div>
            <div class="closing-flower">❀</div>
            <p class="eyebrow">WITH LOVE AND GRATITUDE</p>
            <h2>Terima Kasih</h2>
            <p>Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
            <p class="closing-wedding-of">the wedding of</p>
            <p class="closing-sign">Sasqia <span>&</span> Rian</p>
            <small>23 · 01 · 2027</small>
        </section>

        <footer class="site-footer">Made with <span>♥</span> for Sasqia & Rian</footer>
    </main>

    <div class="lightbox" id="galleryLightbox" aria-hidden="true">
        <button type="button" class="lightbox-close" aria-label="Tutup foto">×</button>
        <img src="" alt="Foto galeri">
    </div>

    <nav class="mobile-dock" aria-label="Navigasi cepat">
        <a href="#home"><span>⌂</span><small>Home</small></a>
        <a href="#couple"><span>♡</span><small>Mempelai</small></a>
        <a href="#event"><span>✧</span><small>Acara</small></a>
        <a href="#gallery"><span>▧</span><small>Galeri</small></a>
        <a href="#rsvp"><span>✓</span><small>RSVP</small></a>
    </nav>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="{{ asset('js/wedding-invitation.js') }}"></script>
    <script src="{{ asset('js/wedding-gsap.js') }}"></script>
</body>
</html>
