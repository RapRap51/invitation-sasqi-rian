@extends('layouts.admin')

@section('title', 'Scanner QR - Sasqia & Rian')

@section('content')

<div class="admin-page-header">
    <div>
        <h2>Scanner QR</h2>
        <p>
            Scan QR Code undangan untuk melakukan check-in.
        </p>
    </div>
</div>

<div class="admin-scanner-layout">

    <div class="admin-card admin-scanner-card">
        <div id="reader"></div>

        <p class="admin-scanner-help">
            Arahkan kamera ke QR Code undangan tamu.
        </p>
    </div>

    <div
        id="guestCard"
        class="admin-card admin-scan-result"
    >
        <div id="guestContent"></div>
    </div>

</div>

@endsection

@push('scripts')

<script src="https://unpkg.com/html5-qrcode"></script>

    <script>

        let lastToken = null;

        function onScanSuccess(decodedText) {

            if (decodedText === lastToken) {
                return;
            }

            lastToken = decodedText;

            let token = decodedText;

            /*
             * QR kita berisi URL:
             *
             * http://localhost/undangan/TOKEN
             *
             * Kita ambil bagian TOKEN-nya.
             */

            try {

                const url = new URL(decodedText);

                const parts =
                    url.pathname.split('/');

                token =
                    parts[parts.length - 1];

            } catch (error) {

                // Kalau ternyata QR langsung berisi token
                token = decodedText;

            }


            fetch("{{ route('admin.checkin.find') }}", {

                method: "POST",

                headers: {
                    "Content-Type":
                        "application/json",

                    "X-CSRF-TOKEN":
                        "{{ csrf_token() }}",

                    "Accept":
                        "application/json"
                },

                body: JSON.stringify({
                    token: token
                })

            })
            .then(response => response.json())

            .then(data => {

                const guestCard =
                    document.getElementById("guestCard");
                    
                const result =
                    document.getElementById("guestContent");

                guestCard.style.display = "block";

                if (!data.success) {

                    result.innerHTML = `
                        <div style="color:red;">
                            <h3>QR Tidak Valid</h3>
                            <p>
                                ${data.message}
                            </p>
                        </div>
                    `;

                    return;
                }
                playBeep();
                
                const guest =
                    data.guest;


                let button = "";


                if (
                    guest.checkin_status ===
                    "arrived"
                ) {

                    button = `
                        <p style="color:green;">
                            ✓ Tamu sudah check-in
                        </p>
                    `;

                } else {

                    button = `

                        <form
                            method="POST"
                            action="{{ url('/admin/checkin') }}/${guest.id}/confirm"
                        >

                            @csrf

                            <button type="submit">
                                ✓ KONFIRMASI HADIR
                            </button>

                        </form>

                    `;
                }


                result.innerHTML = `

                    <div
                        style="
                            margin-top:20px;
                            padding:20px;
                            border:1px solid #ccc;
                        "
                    >

                        <h2>
                            ${guest.name}
                        </h2>

                        <p>
                            Jumlah Undangan:
                            <strong>
                                ${guest.invitation_count}
                            </strong>
                            orang
                        </p>

                        <p>
                            Kode:
                            ${guest.unique_code}
                        </p>

                        <p>
                            RSVP:
                            ${guest.rsvp_status}
                        </p>

                        <p>
                            Check-in:
                            ${guest.checkin_status}
                        </p>

                        ${button}

                    </div>

                `;

            })
            .catch(error => {

                document.getElementById(
                    "guestConten"
                ).innerHTML = `
                    <p style="color:red;">
                        Gagal membaca data QR.
                    </p>
                `;

            });

        }


        function onScanFailure(error) {
            // Abaikan error scanner yang terjadi
            // saat kamera sedang mencari QR.
        }


        const scanner =
            new Html5QrcodeScanner(
                "reader",
                {
                    fps: 10,
                    qrbox: 250
                },
                false
            );


        scanner.render(
            onScanSuccess,
            onScanFailure
        );

        function playBeep()
        {
            const AudioContext =
                window.AudioContext ||
                window.webkitAudioContext;

            if (!AudioContext) {
                return;
            }

            const audioContext =
                new AudioContext();

            const oscillator =
                audioContext.createOscillator();

            const gainNode =
                audioContext.createGain();

            oscillator.type = "sine";

            oscillator.frequency.setValueAtTime(
                900,
                audioContext.currentTime
            );

            gainNode.gain.setValueAtTime(
                0.15,
                audioContext.currentTime
            );

            oscillator.connect(gainNode);

            gainNode.connect(
                audioContext.destination
            );

            oscillator.start();

            oscillator.stop(
                audioContext.currentTime + 0.15
            );
        }

    </script>

@endpush