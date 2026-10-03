<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Booking Servis AHASS - Astra Honda Authorized Service Station')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css">

    <style>
        :root {
            --honda-red: #D3122A;
            --honda-red-dark: #A50D1E;
            --honda-red-subtle: #FEE8EB;
            --honda-black: #121316;
            --honda-charcoal: #1F2228;
            --honda-gray-bg: #F4F6F9;
            --honda-border: #E2E6ED;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--honda-gray-bg);
            color: #2D3748;
            min-height: 100vh;
        }

        /* Honda AHASS Header & Navbar */
        .ahass-navbar {
            background: linear-gradient(135deg, #A50D1E 0%, #D3122A 60%, #E81A32 100%);
            box-shadow: 0 4px 20px rgba(211, 18, 42, 0.25);
            border-bottom: 3px solid #ffcc00;
        }

        .ahass-brand-badge {
            background-color: #ffffff;
            color: var(--honda-red);
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .one-heart-badge {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            padding: 4px 14px;
            font-size: 0.8rem;
            font-style: italic;
            color: #fff;
        }

        /* Cards & Containers */
        .card-custom {
            border: 1px solid var(--honda-border);
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
        }

        .card-custom:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .card-header-custom {
            background-color: #ffffff;
            border-bottom: 1px solid var(--honda-border);
            padding: 1.1rem 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 14px 14px 0 0 !important;
        }

        /* License Plate Box */
        .plat-nomor-input {
            font-family: 'Consolas', 'Courier New', monospace;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            background-color: #FAFAFA;
            border: 2px solid #CBD5E1;
            text-align: center;
        }

        .plat-nomor-input:focus {
            background-color: #ffffff;
            border-color: var(--honda-red);
            box-shadow: 0 0 0 4px rgba(211, 18, 42, 0.15);
        }

        .plat-badge {
            background-color: #0F172A;
            color: #FFFFFF;
            font-family: 'Consolas', monospace;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            letter-spacing: 1px;
            border: 1px solid #334155;
            display: inline-block;
        }

        /* Time Slot Cards / Radio Chips */
        .slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 10px;
        }

        .slot-chip {
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            padding: 10px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: #FFFFFF;
            position: relative;
            user-select: none;
        }

        .slot-chip:hover:not(.slot-disabled) {
            border-color: var(--honda-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 18, 42, 0.12);
        }

        .slot-chip.selected {
            border-color: var(--honda-red);
            background: var(--honda-red-subtle);
            color: var(--honda-red-dark);
        }

        .slot-chip.selected .slot-time {
            color: var(--honda-red);
            font-weight: 800;
        }

        .slot-chip.slot-disabled {
            background: #F1F5F9;
            border-color: #CBD5E1;
            opacity: 0.65;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .slot-chip.slot-full {
            background: #FEF2F2;
            border-color: #FECACA;
            cursor: not-allowed;
        }

        .slot-chip.slot-full .slot-time {
            color: #DC2626;
            text-decoration: line-through;
        }

        .slot-time {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 2px;
        }

        .slot-indicator {
            font-size: 0.72rem;
            font-weight: 600;
            border-radius: 20px;
            padding: 2px 6px;
            display: inline-block;
        }

        /* Package selection card */
        .package-card {
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #FFFFFF;
            position: relative;
        }

        .package-card:hover {
            border-color: #CBD5E1;
            background: #FAFAFA;
        }

        .package-card.selected {
            border-color: var(--honda-red);
            background: #FFF9F9;
            box-shadow: 0 0 0 2px rgba(211, 18, 42, 0.2);
        }

        /* Nav Pills Modern */
        .nav-pills-custom .nav-link {
            color: #475569;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 10px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-pills-custom .nav-link.active {
            background: var(--honda-red);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(211, 18, 42, 0.3);
        }

        .nav-pills-custom .nav-link:hover:not(.active) {
            background: #E2E8F0;
            color: #1E293B;
        }

        /* Status Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-status-confirmed {
            background-color: #E0F2FE;
            color: #0369A1;
        }

        .badge-status-in_progress {
            background-color: #FEF3C7;
            color: #B45309;
        }

        .badge-status-completed {
            background-color: #DCFCE7;
            color: #15803D;
        }

        .badge-status-cancelled {
            background-color: #FEE2E2;
            color: #B91C1C;
        }

        .badge-status-pending {
            background-color: #F1F5F9;
            color: #475569;
        }

        /* Slot Progress Bar in Monitor */
        .slot-progress-bar {
            height: 8px;
            border-radius: 6px;
            background-color: #E2E8F0;
            overflow: hidden;
        }

        /* Pulsing Dot */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .pulse-dot-green {
            background-color: #10B981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 1.8s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Table custom */
        .table-custom thead th {
            background-color: #F8FAFC;
            color: #64748B;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-bottom: 2px solid #E2E8F0;
            padding: 12px 14px;
        }

        .table-custom tbody td {
            padding: 14px;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
        }

        /* Sticky Summary */
        .sticky-summary {
            position: sticky;
            top: 20px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top AHASS Navbar -->
    <header class="ahass-navbar py-3 text-white sticky-top">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="ahass-brand-badge shadow-sm">
                        <i class="fa-solid fa-motorcycle fs-5"></i>
                        <span>AHASS</span>
                    </div>
                    <div>
                        <div class="fw-bolder fs-5 text-white lh-1">Sistem Booking Servis Honda</div>
                        <small class="text-white-50">Astra Honda Authorized Service Station • Pit Management & Slot Reservasi</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span class="one-heart-badge d-none d-md-inline-block">
                        <i class="fa-solid fa-heart text-danger me-1"></i> Satu HATI. / One HEART.
                    </span>
                    <div class="bg-white bg-opacity-10 px-3 py-1 rounded-pill text-white small d-flex align-items-center gap-2">
                        <span class="pulse-dot pulse-dot-green"></span>
                        <span id="liveClock">--:--:-- WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-3 mt-5 text-center text-muted small">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start mb-2 mb-md-0">
                    <strong>AHASS Booking System</strong> &copy; {{ date('Y') }} — PT Astra Honda Motor Dealer Partner
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle me-2">Maks. 3 Unit / Slot</span>
                    <span class="badge bg-light text-secondary border">SPA Mode (jQuery & AJAX)</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>

    <script>
        // Setup CSRF header for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        });

        // Real-time clock in header
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            $('#liveClock').text(timeStr);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Helper format rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(number);
        }
    </script>
    @stack('scripts')
</body>
</html>
