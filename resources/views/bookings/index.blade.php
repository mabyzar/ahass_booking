@extends('layouts.app')

@section('title', 'Sistem Booking Servis AHASS - Honda Astra')

@section('content')
<div class="container">
    <!-- Top Summary Banner -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-3 border-start border-4 border-primary h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Hari Ini</span>
                        <h3 class="fw-bold mb-0 mt-1" id="statTotalToday">-</h3>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="fa-solid fa-calendar-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-3 border-start border-4 border-info h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Dikonfirmasi</span>
                        <h3 class="fw-bold mb-0 mt-1 text-info" id="statConfirmed">-</h3>
                    </div>
                    <div class="bg-info-subtle text-info p-3 rounded-circle">
                        <i class="fa-solid fa-clipboard-list fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-3 border-start border-4 border-warning h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Dalam Pit Servis</span>
                        <h3 class="fw-bold mb-0 mt-1 text-warning" id="statInProgress">-</h3>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                        <i class="fa-solid fa-wrench fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-custom p-3 border-start border-4 border-success h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Selesai Servis</span>
                        <h3 class="fw-bold mb-0 mt-1 text-success" id="statCompleted">-</h3>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Pills / SPA Navigation -->
    <div class="card card-custom mb-4 p-2">
        <ul class="nav nav-pills nav-pills-custom gap-2" id="ahassSpaTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-form-btn" data-bs-toggle="pill" data-bs-target="#tab-form-content" type="button" role="tab">
                    <i class="fa-solid fa-calendar-plus"></i> Form Booking Baru
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-list-btn" data-bs-toggle="pill" data-bs-target="#tab-list-content" type="button" role="tab">
                    <i class="fa-solid fa-list-check"></i> Daftar Booking Servis
                    <span class="badge bg-white text-danger ms-1 fw-bold" id="badgeTotalBookings">0</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-slots-btn" data-bs-toggle="pill" data-bs-target="#tab-slots-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-gantt"></i> Monitor Slot Jam AHASS
                </button>
            </li>
        </ul>
    </div>

    <!-- TAB CONTENTS -->
    <div class="tab-content" id="ahassSpaTabsContent">

        <!-- ========================================== -->
        <!-- TAB 1: FORM BOOKING BARU                   -->
        <!-- ========================================== -->
        <div class="tab-pane fade show active" id="tab-form-content" role="tabpanel">
            <form id="bookingForm" novalidate>
                <div class="row g-4">
                    <!-- Left Column: Form Inputs -->
                    <div class="col-lg-8">
                        <!-- Card 1: Data Kendaraan & Pelanggan -->
                        <div class="card card-custom mb-4">
                            <div class="card-header-custom">
                                <span><i class="fa-solid fa-id-card text-danger me-2"></i>1. Identitas Pelanggan & Motor</span>
                                <span class="badge bg-danger-subtle text-danger">Wajib Diisi</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <!-- Plat Nomor -->
                                    <div class="col-md-6">
                                        <label for="plate_number" class="form-label fw-bold small text-muted">
                                            PLAT NOMOR KENDARAAN <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark text-white fw-bold"><i class="fa-solid fa-hashtag"></i></span>
                                            <input type="text" class="form-control plat-nomor-input" id="plate_number" name="plate_number" placeholder="B 1234 ABC" maxlength="12" required>
                                        </div>
                                        <div class="invalid-feedback" id="error-plate_number"></div>
                                        <small class="text-muted">Format plat nomor polisi motor Honda.</small>
                                    </div>

                                    <!-- Nama Pelanggan -->
                                    <div class="col-md-6">
                                        <label for="customer_name" class="form-label fw-bold small text-muted">
                                            NAMA LENGKAP PELANGGAN <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Contoh: Budi Santoso" required>
                                        </div>
                                        <div class="invalid-feedback" id="error-customer_name"></div>
                                    </div>

                                    <!-- No HP / WA -->
                                    <div class="col-md-6">
                                        <label for="customer_phone" class="form-label fw-bold small text-muted">
                                            NO. WHATSAPP / HP <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fa-brands fa-whatsapp text-success"></i></span>
                                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" placeholder="Contoh: 081234567890" required>
                                        </div>
                                        <div class="invalid-feedback" id="error-customer_phone"></div>
                                    </div>

                                    <!-- Tipe Motor Honda -->
                                    <div class="col-md-6">
                                        <label for="motorcycle_type" class="form-label fw-bold small text-muted">
                                            TIPE MOTOR HONDA <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fa-solid fa-motorcycle text-danger"></i></span>
                                            <input type="text" class="form-control" id="motorcycle_type" name="motorcycle_type" list="hondaModelsList" placeholder="Pilih atau ketik tipe motor..." required>
                                            <datalist id="hondaModelsList">
                                                <option value="Honda Vario 160">
                                                <option value="Honda Vario 125">
                                                <option value="Honda Beat Deluxe / CBS">
                                                <option value="Honda Beat Street">
                                                <option value="Honda Scoopy Prestige / Stylish">
                                                <option value="Honda PCX 160 ABS / CBS">
                                                <option value="Honda Stylo 160">
                                                <option value="Honda ADV 160">
                                                <option value="Honda Genio">
                                                <option value="Honda Supra X 125 FI">
                                                <option value="Honda Revo Fit">
                                                <option value="Honda CB150R Streetfire">
                                                <option value="Honda CBR 150R">
                                                <option value="Honda CRF 150L">
                                                <option value="Honda EM1 e: (Listrik)">
                                            </datalist>
                                        </div>
                                        <div class="invalid-feedback" id="error-motorcycle_type"></div>
                                        <!-- Quick Chips -->
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            <span class="badge bg-light text-dark border cursor-pointer quick-bike-btn" data-bike="Honda Vario 160">+ Vario 160</span>
                                            <span class="badge bg-light text-dark border cursor-pointer quick-bike-btn" data-bike="Honda Beat Deluxe">+ Beat</span>
                                            <span class="badge bg-light text-dark border cursor-pointer quick-bike-btn" data-bike="Honda PCX 160">+ PCX 160</span>
                                            <span class="badge bg-light text-dark border cursor-pointer quick-bike-btn" data-bike="Honda Scoopy">+ Scoopy</span>
                                            <span class="badge bg-light text-dark border cursor-pointer quick-bike-btn" data-bike="Honda Stylo 160">+ Stylo 160</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Jadwal Servis & Visual Slot Selector -->
                        <div class="card card-custom mb-4">
                            <div class="card-header-custom">
                                <span><i class="fa-solid fa-clock text-danger me-2"></i>2. Jadwal Servis & Ketersediaan Slot Jam</span>
                                <span class="badge bg-dark">Maksimal 3 Unit / Jam</span>
                            </div>
                            <div class="card-body p-4">
                                <!-- Tanggal Servis -->
                                <div class="row align-items-center mb-4">
                                    <div class="col-md-5">
                                        <label for="service_date" class="form-label fw-bold small text-muted">
                                            PILIH TANGGAL SERVIS <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fa-regular fa-calendar-days text-danger"></i></span>
                                            <input type="date" class="form-control fw-bold" id="service_date" name="service_date" min="{{ $today }}" value="{{ $today }}" required>
                                        </div>
                                        <div class="invalid-feedback" id="error-service_date"></div>
                                    </div>
                                    <div class="col-md-7 pt-3 pt-md-4">
                                        <div class="d-flex align-items-center gap-2 small">
                                            <span class="d-inline-flex align-items-center gap-1">
                                                <span class="badge bg-success" style="width: 12px; height: 12px; padding: 0;">&nbsp;</span> Tersedia
                                            </span>
                                            <span class="d-inline-flex align-items-center gap-1">
                                                <span class="badge bg-warning" style="width: 12px; height: 12px; padding: 0;">&nbsp;</span> Sisa 1 Slot
                                            </span>
                                            <span class="d-inline-flex align-items-center gap-1">
                                                <span class="badge bg-danger" style="width: 12px; height: 12px; padding: 0;">&nbsp;</span> Penuh (3/3)
                                            </span>
                                        </div>
                                        <small class="text-muted d-block mt-1">Sistem otomatis memeriksa kapasitas bengkel AHASS secara real-time.</small>
                                    </div>
                                </div>

                                <!-- Slot Jam Grid -->
                                <label class="form-label fw-bold small text-muted mb-2">
                                    PILIH JAM SERVIS <span class="text-danger">*</span>
                                </label>
                                <input type="hidden" name="service_time" id="service_time" required>
                                <div class="invalid-feedback d-block mb-2" id="error-service_time"></div>

                                <div id="slotGridContainer" class="slot-grid">
                                    <!-- Rendered dynamically via AJAX -->
                                    <div class="text-center py-4 w-100 text-muted">
                                        <i class="fa-solid fa-spinner fa-spin text-danger me-2"></i> Memuat status slot jam...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Pilihan Paket Servis AHASS -->
                        <div class="card card-custom mb-4">
                            <div class="card-header-custom">
                                <span><i class="fa-solid fa-screwdriver-wrench text-danger me-2"></i>3. Pilih Paket Servis Honda</span>
                                <span class="badge bg-primary-subtle text-primary">Standar Astra Honda</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="invalid-feedback d-block mb-2" id="error-service_package_id"></div>
                                <div class="row g-3">
                                    @foreach($packages as $pkg)
                                    <div class="col-md-6">
                                        <div class="package-card @if($loop->first) selected @endif" data-id="{{ $pkg->id }}" data-price="{{ $pkg->price }}">
                                            <div class="form-check">
                                                <input class="form-check-input package-radio" type="radio" name="service_package_id" id="pkg_{{ $pkg->id }}" value="{{ $pkg->id }}" @if($loop->first) checked @endif required>
                                                <label class="form-check-label w-100" for="pkg_{{ $pkg->id }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <h6 class="fw-bold mb-0 text-dark">{{ $pkg->name }}</h6>
                                                        <span class="badge bg-danger">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="small text-muted mb-2">
                                                        <i class="fa-regular fa-clock me-1"></i> Estimasi ~{{ $pkg->duration_minutes }} menit
                                                    </div>
                                                    <p class="small text-secondary mb-0 lh-sm">{{ $pkg->description }}</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Sparepart & Oli Tambahan (HGP) -->
                        <div class="card card-custom mb-4">
                            <div class="card-header-custom">
                                <span><i class="fa-solid fa-oil-can text-danger me-2"></i>4. Sparepart & Oli Asli Honda (HGP - Opsional)</span>
                                <span class="badge bg-success-subtle text-success">100% Genuine Parts</span>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted small mb-3">Pilih oli atau suku cadang asli AHM yang ingin diganti saat servis berkala:</p>
                                <div class="row g-2">
                                    @foreach($parts as $part)
                                    <div class="col-md-6">
                                        <div class="border rounded p-2.5 p-2 bg-light-subtle d-flex align-items-center justify-content-between">
                                            <div class="form-check me-2">
                                                <input class="form-check-input part-checkbox" type="checkbox" name="selected_parts[]" value="{{ $part->id }}" id="part_{{ $part->id }}" data-price="{{ $part->price }}">
                                                <label class="form-check-label small" for="part_{{ $part->id }}">
                                                    <div class="fw-semibold text-dark">{{ $part->name }}</div>
                                                    <small class="text-muted">{{ $part->category }} • Kode: {{ $part->code }}</small>
                                                </label>
                                            </div>
                                            <span class="badge bg-light text-dark border fw-bold text-nowrap">
                                                +Rp {{ number_format($part->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card 5: Keluhan Tambahan -->
                        <div class="card card-custom mb-4">
                            <div class="card-header-custom">
                                <span><i class="fa-solid fa-comment-dots text-danger me-2"></i>5. Keluhan / Gejala Kendaraan (Opsional)</span>
                            </div>
                            <div class="card-body p-4">
                                <textarea class="form-control" name="complaints" id="complaints" rows="2" placeholder="Tuliskan keluhan atau gejala motor Anda (contoh: tarikan berat saat tanjakan, rem depan berdecit, ada getaran di bagian CVT)..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Sticky Summary & Action -->
                    <div class="col-lg-4">
                        <div class="sticky-summary">
                            <div class="card card-custom border-danger">
                                <div class="card-header bg-danger text-white py-3 rounded-top-3">
                                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-receipt me-2"></i>Ringkasan Booking Servis</h6>
                                </div>
                                <div class="card-body p-4">
                                    <!-- Selected Plate & Bike -->
                                    <div class="mb-3 text-center pb-3 border-bottom">
                                        <span class="plat-badge fs-5" id="summaryPlate">_ _ _ _ _ _</span>
                                        <div class="fw-bold mt-2 text-dark" id="summaryBike">-</div>
                                        <small class="text-muted" id="summaryCustomer">-</small>
                                    </div>

                                    <!-- Date & Slot -->
                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <span class="text-muted"><i class="fa-regular fa-calendar me-1"></i> Tanggal:</span>
                                        <span class="fw-bold text-dark" id="summaryDate">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3 small pb-3 border-bottom">
                                        <span class="text-muted"><i class="fa-regular fa-clock me-1"></i> Slot Jam:</span>
                                        <span class="badge bg-danger fs-6" id="summarySlot">Pilih Jam</span>
                                    </div>

                                    <!-- Pricing Breakdown -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span class="text-muted" id="summaryPackageName">Paket Servis:</span>
                                            <span class="fw-bold" id="summaryPackagePrice">Rp 0</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 small pb-2 border-bottom">
                                            <span class="text-muted">Sparepart Tambahan (<span id="summaryPartsCount">0</span>):</span>
                                            <span class="fw-bold" id="summaryPartsPrice">Rp 0</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="fw-bold fs-6">Total Estimasi:</span>
                                            <span class="fw-bolder fs-4 text-danger" id="summaryTotalPrice">Rp 0</span>
                                        </div>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">*Biaya final dibayarkan di kasir AHASS setelah pengerjaan selesai.</small>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-danger btn-lg fw-bold shadow" id="btnSubmitBooking">
                                            <i class="fa-solid fa-check-circle me-1"></i> Konfirmasi Booking Servis
                                        </button>
                                    </div>

                                    <div class="alert alert-secondary mt-3 mb-0 py-2 px-3 small border-0 bg-light">
                                        <i class="fa-solid fa-shield-halved text-danger me-1"></i>
                                        <strong>Garansi Servis AHASS</strong> hingga 500 KM atau 1 minggu setelah pengerjaan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: DAFTAR BOOKING SERVIS (LIVE FILTER) -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-list-content" role="tabpanel">
            <div class="card card-custom">
                <div class="card-header-custom flex-wrap gap-3">
                    <div>
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-list-check text-danger me-2"></i>Daftar Booking Servis AHASS</h5>
                        <small class="text-muted">Kelola antrean pelanggan, ubah status pengerjaan, dan filter data tanpa reload halaman.</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-outline-secondary btn-sm" id="btnRefreshList">
                            <i class="fa-solid fa-rotate me-1"></i> Refresh Data
                        </button>
                        <button class="btn btn-danger btn-sm" id="btnGoToCreate">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Booking Baru
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Filter Toolbar -->
                    <div class="card bg-light border-0 p-3 mb-4">
                        <div class="row g-3 align-items-end">
                            <!-- Filter Tanggal -->
                            <div class="col-md-3">
                                <label for="filterDate" class="form-label small fw-bold text-muted">Filter Tanggal</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fa-regular fa-calendar text-danger"></i></span>
                                    <input type="date" class="form-control" id="filterDate" value="{{ $today }}">
                                </div>
                            </div>

                            <!-- Filter Status -->
                            <div class="col-md-2">
                                <label for="filterStatus" class="form-label small fw-bold text-muted">Status</label>
                                <select class="form-select form-select-sm" id="filterStatus">
                                    <option value="all">Semua Status</option>
                                    <option value="confirmed" selected>Dikonfirmasi</option>
                                    <option value="in_progress">Dalam Pengerjaan</option>
                                    <option value="completed">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>

                            <!-- Filter Paket Servis -->
                            <div class="col-md-3">
                                <label for="filterPackage" class="form-label small fw-bold text-muted">Paket Servis</label>
                                <select class="form-select form-select-sm" id="filterPackage">
                                    <option value="all">Semua Paket Servis</option>
                                    @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Search Input -->
                            <div class="col-md-3">
                                <label for="filterSearch" class="form-label small fw-bold text-muted">Cari (Plat / Nama / HP)</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                    <input type="text" class="form-control" id="filterSearch" placeholder="Contoh: B 1234 atau Budi...">
                                </div>
                            </div>

                            <!-- Reset Button -->
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100" id="btnResetFilter" title="Reset Filter">
                                    <i class="fa-solid fa-filter-circle-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Jadwal Servis</th>
                                    <th>Plat & Tipe Motor</th>
                                    <th>Pelanggan</th>
                                    <th>Paket & Suku Cadang</th>
                                    <th>Estimasi Biaya</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bookingsTableBody">
                                <!-- Loaded dynamically via AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <div id="tableEmptyState" class="text-center py-5 d-none">
                        <div class="text-muted mb-3">
                            <i class="fa-solid fa-folder-open fs-1 text-black-50"></i>
                        </div>
                        <h6 class="fw-bold text-secondary">Tidak ada data booking yang sesuai</h6>
                        <p class="small text-muted mb-3">Silakan sesuaikan tanggal atau filter pencarian Anda di atas.</p>
                        <button class="btn btn-outline-danger btn-sm" id="btnResetFilterEmpty">
                            <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Booking Hari Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: MONITOR KETERSEDIAAN SLOT JAM AHASS -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-slots-content" role="tabpanel">
            <div class="card card-custom">
                <div class="card-header-custom flex-wrap gap-2">
                    <div>
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-chart-gantt text-danger me-2"></i>Monitor Kapasitas Slot Servis Pit AHASS</h5>
                        <small class="text-muted">Pantau beban kerja mekanik per slot jam (kapasitas maksimal 3 motor per slot).</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fa-regular fa-calendar text-danger"></i></span>
                            <input type="date" class="form-control" id="monitorDate" value="{{ $today }}">
                        </div>
                        <button class="btn btn-outline-danger btn-sm" id="btnRefreshMonitor">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3" id="monitorSlotCards">
                        <!-- Loaded dynamically via AJAX -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: DETAIL BOOKING SERVIS               -->
<!-- ========================================== -->
<div class="modal fade" id="detailBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="detailModalTitle"><i class="fa-solid fa-receipt me-2"></i>Detail Booking Servis AHASS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="detailModalBody">
                <!-- Injected dynamically via AJAX -->
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <div>
                    <span class="text-muted small">Status Operasional: <strong id="modalStatusBadge"></strong></span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" onclick="window.print()">
                        <i class="fa-solid fa-print me-1"></i> Cetak Bukti Booking
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: UBAH STATUS BOOKING                 -->
<!-- ========================================== -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Perbarui Status Booking</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="statusBookingId">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">KODE BOOKING / PLAT</label>
                    <div class="fw-bold fs-6 text-dark" id="statusBookingLabel">-</div>
                </div>
                <div class="mb-3">
                    <label for="newStatusSelect" class="form-label small fw-bold text-muted">PILIH STATUS BARU</label>
                    <select class="form-select" id="newStatusSelect">
                        <option value="confirmed">Dikonfirmasi (Siap Datang)</option>
                        <option value="in_progress">Dalam Pengerjaan (Di Pit)</option>
                        <option value="completed">Selesai Servis</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="statusNotes" class="form-label small fw-bold text-muted">CATATAN MEKANIK / ADMIN (OPSIONAL)</label>
                    <textarea class="form-control" id="statusNotes" rows="2" placeholder="Contoh: Motor sudah naik di Pit 2, penggantian sparepart selesai..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger fw-bold" id="btnSaveStatus">
                    <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentSelectedSlot = '';

    // Initialize View
    initSummary();
    loadSlots($('#service_date').val());
    loadBookings();
    loadMonitorSlots($('#monitorDate').val());

    // ==========================================
    // 1. INTERAKSI FORM & SUMMARY
    // ==========================================

    // Auto capitalize plate number and format
    $('#plate_number').on('input', function() {
        let val = $(this).val().toUpperCase();
        $(this).val(val);
        $('#summaryPlate').text(val || '_ _ _ _ _ _');
    });

    $('#customer_name').on('input', function() {
        let val = $(this).val();
        $('#summaryCustomer').text(val ? 'Pemilik: ' + val : '-');
    });

    $('#motorcycle_type').on('input change', function() {
        let val = $(this).val();
        $('#summaryBike').text(val || '-');
    });

    // Quick bike chips
    $('.quick-bike-btn').on('click', function() {
        let bike = $(this).data('bike');
        $('#motorcycle_type').val(bike).trigger('input');
    });

    // Date change on form -> reload slots
    $('#service_date').on('change', function() {
        let dateVal = $(this).val();
        currentSelectedSlot = '';
        $('#service_time').val('');
        $('#summaryDate').text(formatDateIndo(dateVal));
        $('#summarySlot').text('Pilih Jam').removeClass('bg-success').addClass('bg-danger');
        loadSlots(dateVal);
    });

    // Package card selection
    $(document).on('click', '.package-card', function() {
        $('.package-card').removeClass('selected');
        $(this).addClass('selected');
        $(this).find('input[type="radio"]').prop('checked', true);
        calculateTotal();
    });

    $('.package-radio').on('change', function() {
        calculateTotal();
    });

    // Parts selection
    $('.part-checkbox').on('change', function() {
        calculateTotal();
    });

    // Helper: Format tanggal Indonesia
    function formatDateIndo(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr + 'T00:00:00');
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        return d.toLocaleDateString('id-ID', options);
    }

    function initSummary() {
        $('#summaryDate').text(formatDateIndo($('#service_date').val()));
        calculateTotal();
    }

    // Live Total Price Calculation
    function calculateTotal() {
        let selectedPkg = $('.package-radio:checked');
        let pkgPrice = 0;
        let pkgName = 'Paket Servis';

        if (selectedPkg.length) {
            let card = selectedPkg.closest('.package-card');
            pkgPrice = parseFloat(card.data('price')) || 0;
            pkgName = card.find('h6').text();
        }

        let partsPrice = 0;
        let partsCount = 0;
        $('.part-checkbox:checked').each(function() {
            partsPrice += parseFloat($(this).data('price')) || 0;
            partsCount++;
        });

        let grandTotal = pkgPrice + partsPrice;

        $('#summaryPackageName').text(pkgName + ':');
        $('#summaryPackagePrice').text(formatRupiah(pkgPrice));
        $('#summaryPartsCount').text(partsCount);
        $('#summaryPartsPrice').text(formatRupiah(partsPrice));
        $('#summaryTotalPrice').text(formatRupiah(grandTotal));
    }

    // ==========================================
    // 2. LOAD SLOTS VIA AJAX (WITH 3-SLOT LIMIT)
    // ==========================================
    function loadSlots(date) {
        $('#slotGridContainer').html(`
            <div class="col-12 text-center py-4 text-muted">
                <i class="fa-solid fa-spinner fa-spin text-danger me-2"></i> Memeriksa ketersediaan slot AHASS...
            </div>
        `);

        $.ajax({
            url: "{{ route('bookings.slots') }}",
            type: 'GET',
            data: { date: date },
            success: function(res) {
                if (res.success) {
                    renderSlotGrid(res.slots);
                }
            },
            error: function() {
                $('#slotGridContainer').html(`
                    <div class="col-12 alert alert-danger py-2 text-center small">
                        Gagal memuat slot. Silakan refresh halaman atau coba tanggal lain.
                    </div>
                `);
            }
        });
    }

    function renderSlotGrid(slots) {
        let html = '';
        slots.forEach(slot => {
            let disabledClass = '';
            let fullClass = '';
            let badgeClass = 'bg-success';
            let indicatorText = `${slot.remaining_slots} Tersisa`;

            if (slot.is_full) {
                disabledClass = 'slot-disabled';
                fullClass = 'slot-full';
                badgeClass = 'bg-danger';
                indicatorText = 'PENUH (3/3)';
            } else if (slot.remaining_slots === 1) {
                badgeClass = 'bg-warning text-dark';
                indicatorText = 'Sisa 1 Slot';
            }

            let isSelected = (currentSelectedSlot === slot.time && !slot.is_full);

            html += `
                <div class="slot-chip ${disabledClass} ${fullClass} ${isSelected ? 'selected' : ''}" 
                     data-time="${slot.time}" 
                     data-available="${slot.is_available}"
                     data-full="${slot.is_full}">
                    <div class="slot-time">${slot.time} WIB</div>
                    <span class="slot-indicator badge ${badgeClass}">${indicatorText}</span>
                    ${slot.is_full ? '<div class="text-danger mt-1 fw-bold" style="font-size: 0.65rem;">KUOTA PENUH</div>' : ''}
                </div>
            `;
        });

        $('#slotGridContainer').html(html);

        // Click slot chip handler
        $('.slot-chip').off('click').on('click', function() {
            if ($(this).hasClass('slot-disabled') || $(this).data('full')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Slot Jam Penuh!',
                    text: `Slot jam ${$(this).data('time')} WIB sudah terisi maksimal 3 motor. Silakan pilih jam lain.`,
                    confirmButtonColor: '#D3122A'
                });
                return;
            }

            $('.slot-chip').removeClass('selected');
            $(this).addClass('selected');

            currentSelectedSlot = $(this).data('time');
            $('#service_time').val(currentSelectedSlot);
            $('#error-service_time').text('');

            $('#summarySlot').text(currentSelectedSlot + ' WIB').removeClass('bg-danger').addClass('bg-success');
        });
    }

    // ==========================================
    // 3. SUBMIT BOOKING FORM VIA AJAX
    // ==========================================
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous error messages
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Client-side quick check
        if (!currentSelectedSlot) {
            $('#error-service_time').text('Silakan klik salah satu slot jam servis yang tersedia.');
            Swal.fire({
                icon: 'warning',
                title: 'Jam Servis Belum Dipilih',
                text: 'Harap pilih slot jam servis yang masih tersedia sebelum melanjutkan.',
                confirmButtonColor: '#D3122A'
            });
            return;
        }

        let $submitBtn = $('#btnSubmitBooking');
        let originalText = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Memproses Booking...');

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('bookings.store') }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                $submitBtn.prop('disabled', false).html(originalText);

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Servis Berhasil!',
                        html: `
                            <p class="mb-2">Pendaftaran booking motor <strong>${response.data.plate_number}</strong> telah terkonfirmasi.</p>
                            <div class="p-3 bg-light rounded text-start small border">
                                <div><strong>Kode Booking:</strong> <span class="text-danger fw-bold">${response.data.booking_code}</span></div>
                                <div><strong>Jadwal:</strong> ${formatDateIndo(response.data.service_date)} - Jam ${response.data.service_time} WIB</div>
                                <div><strong>Estimasi Biaya:</strong> ${formatRupiah(response.data.estimated_total_price)}</div>
                            </div>
                            <p class="text-muted small mt-2 mb-0">Harap tiba di AHASS 15 menit sebelum jam servis.</p>
                        `,
                        confirmButtonText: 'Lihat Daftar Booking',
                        confirmButtonColor: '#D3122A',
                        showCancelButton: true,
                        cancelButtonText: 'Buat Booking Lain',
                        cancelButtonColor: '#64748b'
                    }).then((result) => {
                        // Reset form
                        $('#bookingForm')[0].reset();
                        currentSelectedSlot = '';
                        $('#service_time').val('');
                        $('#summaryPlate').text('_ _ _ _ _ _');
                        $('#summaryBike').text('-');
                        $('#summaryCustomer').text('-');
                        $('#summarySlot').text('Pilih Jam').removeClass('bg-success').addClass('bg-danger');
                        $('.package-card').removeClass('selected');
                        $('.package-card:first').addClass('selected');
                        $('.package-card:first input').prop('checked', true);
                        calculateTotal();

                        // Reload data
                        loadSlots($('#service_date').val());
                        loadBookings();
                        loadMonitorSlots($('#monitorDate').val());

                        if (result.isConfirmed) {
                            // Switch to List Tab
                            $('#tab-list-btn').tab('show');
                        }
                    });
                }
            },
            error: function(xhr) {
                $submitBtn.prop('disabled', false).html(originalText);

                if (xhr.status === 422) {
                    let err = xhr.responseJSON;
                    let errorMessages = '';

                    if (err.errors) {
                        for (let field in err.errors) {
                            let msg = err.errors[field][0];
                            $(`#${field}`).addClass('is-invalid');
                            $(`#error-${field}`).text(msg);
                            errorMessages += `<li class="text-start">${msg}</li>`;
                        }
                    }

                    // Special Alert for Slot Limit Backend Rejection!
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Booking Gagal',
                        html: `
                            <p class="mb-2 text-danger fw-semibold">${err.message || 'Slot jam atau data formulir tidak valid.'}</p>
                            ${errorMessages ? `<ul class="small mb-0 text-muted">${errorMessages}</ul>` : ''}
                        `,
                        confirmButtonColor: '#D3122A'
                    });

                    // Refresh slots because quota may have just filled up!
                    loadSlots($('#service_date').val());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menghubungkan ke server AHASS. Silakan periksa koneksi Anda.',
                        confirmButtonColor: '#D3122A'
                    });
                }
            }
        });
    });

    // ==========================================
    // 4. LOAD & FILTER BOOKINGS TABLE (AJAX)
    // ==========================================
    let searchTimeout = null;

    $('#filterDate, #filterStatus, #filterPackage').on('change', function() {
        loadBookings();
    });

    $('#filterSearch').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadBookings();
        }, 300);
    });

    $('#btnResetFilter, #btnResetFilterEmpty').on('click', function() {
        $('#filterDate').val("{{ $today }}");
        $('#filterStatus').val('all');
        $('#filterPackage').val('all');
        $('#filterSearch').val('');
        loadBookings();
    });

    $('#btnRefreshList').on('click', function() {
        let $btn = $(this);
        $btn.find('i').addClass('fa-spin');
        loadBookings(function() {
            $btn.find('i').removeClass('fa-spin');
        });
    });

    $('#btnGoToCreate').on('click', function() {
        $('#tab-form-btn').tab('show');
    });

    function loadBookings(callback) {
        let params = {
            date: $('#filterDate').val(),
            status: $('#filterStatus').val(),
            package_id: $('#filterPackage').val(),
            search: $('#filterSearch').val()
        };

        $('#bookingsTableBody').html(`
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-spinner fa-spin text-danger fs-4 mb-2"></i>
                    <div>Memuat daftar antrean booking AHASS...</div>
                </td>
            </tr>
        `);

        $.ajax({
            url: "{{ route('bookings.data') }}",
            type: 'GET',
            data: params,
            success: function(res) {
                if (res.success) {
                    renderBookingsTable(res.data);
                    updateStatsDisplay(res.stats);
                    $('#badgeTotalBookings').text(res.count);
                }
                if (callback) callback();
            },
            error: function() {
                $('#bookingsTableBody').html(`
                    <tr>
                        <td colspan="8" class="text-center text-danger py-4">
                            Gagal memuat data booking dari server.
                        </td>
                    </tr>
                `);
                if (callback) callback();
            }
        });
    }

    function renderBookingsTable(data) {
        if (!data || data.length === 0) {
            $('#bookingsTable').addClass('d-none');
            $('#tableEmptyState').removeClass('d-none');
            return;
        }

        $('#bookingsTable').removeClass('d-none');
        $('#tableEmptyState').addClass('d-none');

        let rowsHtml = '';
        data.forEach(item => {
            let statusBadge = getStatusBadge(item.status);
            let partsCount = item.selected_parts ? item.selected_parts.length : 0;
            let partsBadge = partsCount > 0 ? `<span class="badge bg-secondary-subtle text-secondary border ms-1">+${partsCount} part</span>` : '';

            rowsHtml += `
                <tr>
                    <td>
                        <span class="fw-bold text-danger cursor-pointer btn-detail" data-id="${item.id}" title="Klik untuk lihat detail">
                            ${item.booking_code}
                        </span>
                        <div class="text-muted" style="font-size: 0.7rem;">ID: #${item.id}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">${item.service_time} WIB</div>
                        <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i>${formatDateIndo(item.service_date)}</small>
                    </td>
                    <td>
                        <span class="plat-badge mb-1">${item.plate_number}</span>
                        <div class="small fw-semibold text-dark">${item.motorcycle_type}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">${item.customer_name}</div>
                        <a href="https://wa.me/${formatWaNumber(item.customer_phone)}" target="_blank" class="small text-success text-decoration-none">
                            <i class="fa-brands fa-whatsapp me-1"></i>${item.customer_phone || '-'}
                        </a>
                    </td>
                    <td>
                        <div class="small fw-bold text-dark">${item.service_package ? item.service_package.name : '-'}</div>
                        ${partsBadge}
                    </td>
                    <td>
                        <span class="fw-bold text-dark">${formatRupiah(item.estimated_total_price)}</span>
                    </td>
                    <td>
                        ${statusBadge}
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-dark btn-detail" data-id="${item.id}" title="Lihat Rincian">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-primary btn-status-modal" data-id="${item.id}" data-status="${item.status}" data-code="${item.booking_code}" data-plate="${item.plate_number}" title="Ubah Status">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-delete-booking" data-id="${item.id}" data-code="${item.booking_code}" title="Batalkan / Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        $('#bookingsTableBody').html(rowsHtml);

        // Bind Action Events
        $('.btn-detail').off('click').on('click', function() {
            showDetailModal($(this).data('id'));
        });

        $('.btn-status-modal').off('click').on('click', function() {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let code = $(this).data('code');
            let plate = $(this).data('plate');

            $('#statusBookingId').val(id);
            $('#statusBookingLabel').text(`${code} (${plate})`);
            $('#newStatusSelect').val(status);
            $('#statusNotes').val('');

            let modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        });

        $('.btn-delete-booking').off('click').on('click', function() {
            let id = $(this).data('id');
            let code = $(this).data('code');
            confirmDeleteBooking(id, code);
        });
    }

    function getStatusBadge(status) {
        switch (status) {
            case 'confirmed':
                return '<span class="badge-status badge-status-confirmed"><i class="fa-solid fa-circle-check"></i> Dikonfirmasi</span>';
            case 'in_progress':
                return '<span class="badge-status badge-status-in_progress"><i class="fa-solid fa-gear fa-spin"></i> Dikerjakan</span>';
            case 'completed':
                return '<span class="badge-status badge-status-completed"><i class="fa-solid fa-check-double"></i> Selesai</span>';
            case 'cancelled':
                return '<span class="badge-status badge-status-cancelled"><i class="fa-solid fa-ban"></i> Dibatalkan</span>';
            default:
                return `<span class="badge-status badge-status-pending">${status}</span>`;
        }
    }

    function formatWaNumber(phone) {
        if (!phone) return '';
        let clean = phone.replace(/[^0-9]/g, '');
        if (clean.startsWith('0')) {
            clean = '62' + clean.substring(1);
        }
        return clean;
    }

    function updateStatsDisplay(stats) {
        if (!stats) return;
        $('#statTotalToday').text(stats.total_bookings || 0);
        $('#statConfirmed').text(stats.confirmed || 0);
        $('#statInProgress').text(stats.in_progress || 0);
        $('#statCompleted').text(stats.completed || 0);
    }

    // ==========================================
    // 5. UPDATE STATUS VIA AJAX
    // ==========================================
    $('#btnSaveStatus').on('click', function() {
        let id = $('#statusBookingId').val();
        let newStatus = $('#newStatusSelect').val();
        let notes = $('#statusNotes').val();

        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Menyimpan...');

        $.ajax({
            url: `/bookings/${id}/status`,
            type: 'PATCH',
            data: {
                status: newStatus,
                notes: notes
            },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-save me-1"></i> Simpan Perubahan');
                bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Status Diperbarui',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                loadBookings();
                loadSlots($('#service_date').val());
                loadMonitorSlots($('#monitorDate').val());
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-save me-1"></i> Simpan Perubahan');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memperbarui Status',
                    text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.'
                });
            }
        });
    });

    // ==========================================
    // 6. DELETE / CANCEL BOOKING VIA AJAX
    // ==========================================
    function confirmDeleteBooking(id, code) {
        Swal.fire({
            title: 'Hapus Booking Servis?',
            text: `Apakah Anda yakin ingin membatalkan dan menghapus booking ${code}? Slot jam akan kembali dibuka untuk pelanggan lain.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D3122A',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus Booking',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/bookings/${id}`,
                    type: 'DELETE',
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        loadBookings();
                        loadSlots($('#service_date').val());
                        loadMonitorSlots($('#monitorDate').val());
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menghapus data booking.'
                        });
                    }
                });
            }
        });
    }

    // ==========================================
    // 7. SHOW DETAIL MODAL VIA AJAX
    // ==========================================
    function showDetailModal(id) {
        $.ajax({
            url: `/bookings/${id}`,
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    let b = res.data;
                    let parts = res.parts || [];

                    let partsListHtml = '';
                    if (parts.length > 0) {
                        partsListHtml = `
                            <div class="mt-3">
                                <label class="small fw-bold text-muted text-uppercase">Sparepart & Oli Asli Honda:</label>
                                <ul class="list-group list-group-flush border rounded mt-1">
                        `;
                        parts.forEach(p => {
                            partsListHtml += `
                                <li class="list-group-item d-flex justify-content-between align-items-center small py-2">
                                    <div>
                                        <span class="fw-semibold">${p.name}</span>
                                        <span class="badge bg-light text-muted border ms-2">${p.code}</span>
                                    </div>
                                    <span class="fw-bold text-dark">${formatRupiah(p.price)}</span>
                                </li>
                            `;
                        });
                        partsListHtml += `</ul></div>`;
                    } else {
                        partsListHtml = `
                            <div class="mt-3 small text-muted">
                                <em>Tidak ada sparepart tambahan yang dipilih.</em>
                            </div>
                        `;
                    }

                    let bodyHtml = `
                        <div class="text-center pb-3 mb-3 border-bottom">
                            <span class="plat-badge fs-4 mb-2">${b.plate_number}</span>
                            <h5 class="fw-bold text-dark mb-0">${b.motorcycle_type}</h5>
                            <span class="badge bg-danger-subtle text-danger fw-bold mt-1">${b.booking_code}</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Nama Pemilik:</small>
                                <strong class="text-dark">${b.customer_name}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">WhatsApp / No HP:</small>
                                <strong class="text-success"><i class="fa-brands fa-whatsapp me-1"></i>${b.customer_phone || '-'}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Tanggal Servis:</small>
                                <strong class="text-dark">${formatDateIndo(b.service_date)}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Jam Slot Servis:</small>
                                <span class="badge bg-danger fs-6">${b.service_time} WIB</span>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <span class="badge bg-danger me-1">Paket</span>
                                    <strong>${b.service_package ? b.service_package.name : '-'}</strong>
                                </div>
                                <span class="fw-bold">${formatRupiah(b.service_package ? b.service_package.price : 0)}</span>
                            </div>
                            <small class="text-muted d-block">${b.service_package ? b.service_package.description : ''}</small>
                        </div>

                        ${partsListHtml}

                        ${b.complaints ? `
                            <div class="mt-3">
                                <label class="small fw-bold text-muted text-uppercase">Keluhan / Catatan Pelanggan:</label>
                                <div class="p-2.5 p-2 bg-light-subtle border rounded small text-secondary">
                                    <i class="fa-solid fa-quote-left text-black-50 me-1"></i> ${b.complaints}
                                </div>
                            </div>
                        ` : ''}

                        ${b.notes ? `
                            <div class="mt-2">
                                <label class="small fw-bold text-muted text-uppercase">Catatan Bengkel / Pit:</label>
                                <div class="p-2 bg-warning-subtle border border-warning-subtle rounded small text-dark">
                                    <i class="fa-solid fa-wrench text-warning me-1"></i> ${b.notes}
                                </div>
                            </div>
                        ` : ''}

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <h6 class="mb-0 fw-bold">TOTAL ESTIMASI BIAYA:</h6>
                            <h4 class="mb-0 fw-bolder text-danger">${formatRupiah(b.estimated_total_price)}</h4>
                        </div>
                    `;

                    $('#detailModalBody').html(bodyHtml);
                    $('#modalStatusBadge').html(getStatusBadge(b.status));

                    let modal = new bootstrap.Modal(document.getElementById('detailBookingModal'));
                    modal.show();
                }
            }
        });
    }

    // ==========================================
    // 8. MONITOR SLOT JAM AHASS (VISUAL PIT)
    // ==========================================
    $('#monitorDate').on('change', function() {
        loadMonitorSlots($(this).val());
    });

    $('#btnRefreshMonitor').on('click', function() {
        loadMonitorSlots($('#monitorDate').val());
    });

    function loadMonitorSlots(date) {
        $('#monitorSlotCards').html(`
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-solid fa-spinner fa-spin text-danger fs-3 mb-2"></i>
                <div>Memuat monitor kapasitas slot untuk tanggal ${formatDateIndo(date)}...</div>
            </div>
        `);

        // Fetch slots status + bookings for this date to show registered units
        $.when(
            $.ajax({ url: "{{ route('bookings.slots') }}", type: 'GET', data: { date: date } }),
            $.ajax({ url: "{{ route('bookings.data') }}", type: 'GET', data: { date: date, status: 'all' } })
        ).done(function(slotsRes, bookingsRes) {
            let slots = slotsRes[0].slots;
            let bookings = bookingsRes[0].data;

            renderMonitorCards(slots, bookings, date);
        }).fail(function() {
            $('#monitorSlotCards').html(`
                <div class="col-12 alert alert-danger text-center">Gagal memuat monitor slot.</div>
            `);
        });
    }

    function renderMonitorCards(slots, bookings, date) {
        let html = '';

        slots.forEach(slot => {
            // Filter bookings for this slot
            let slotBookings = bookings.filter(b => b.service_time === slot.time && b.status !== 'cancelled');
            let bookedCount = slotBookings.length;
            let percent = (bookedCount / slot.max_slots) * 100;

            let cardBorder = 'border-secondary-subtle';
            let barColor = 'bg-success';
            let statusText = `${slot.remaining_slots} Slot Tersedia`;

            if (slot.is_full) {
                cardBorder = 'border-danger';
                barColor = 'bg-danger';
                statusText = 'PENUH (3/3 Motor)';
            } else if (bookedCount === 2) {
                cardBorder = 'border-warning';
                barColor = 'bg-warning';
                statusText = 'Tersisa 1 Slot';
            }

            let vehicleChips = '';
            if (slotBookings.length > 0) {
                vehicleChips = '<div class="d-flex flex-column gap-1.5 gap-1 mt-2">';
                slotBookings.forEach((b, idx) => {
                    vehicleChips += `
                        <div class="p-1.5 p-1 bg-light border rounded small d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-1.5 gap-1">
                                <span class="badge bg-dark" style="font-size: 0.65rem;">Pit ${idx + 1}</span>
                                <strong style="font-size: 0.75rem;">${b.plate_number}</strong>
                                <span class="text-muted d-none d-sm-inline" style="font-size: 0.7rem;">(${b.motorcycle_type})</span>
                            </div>
                            <span class="badge ${b.status === 'completed' ? 'bg-success' : (b.status === 'in_progress' ? 'bg-warning text-dark' : 'bg-info')} text-uppercase" style="font-size: 0.6rem;">
                                ${b.status}
                            </span>
                        </div>
                    `;
                });
                vehicleChips += '</div>';
            } else {
                vehicleChips = '<div class="text-muted small text-center py-2" style="font-size: 0.72rem;">Belum ada motor yang dibooking pada jam ini.</div>';
            }

            html += `
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom h-100 ${cardBorder} border-2">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-0 text-dark">${slot.time} WIB</h5>
                                <span class="badge ${slot.is_full ? 'bg-danger' : (bookedCount === 2 ? 'bg-warning text-dark' : 'bg-success')} fw-bold">
                                    ${bookedCount} / ${slot.max_slots} Unit
                                </span>
                            </div>

                            <div class="slot-progress-bar mb-2">
                                <div class="${barColor}" style="width: ${percent}%; height: 100%;"></div>
                            </div>

                            <div class="d-flex justify-content-between small text-muted mb-2" style="font-size: 0.72rem;">
                                <span>Kapasitas: ${slot.max_slots} Motor</span>
                                <span class="fw-bold ${slot.is_full ? 'text-danger' : 'text-success'}">${statusText}</span>
                            </div>

                            <hr class="my-2 opacity-25">

                            <div class="small fw-semibold text-muted mb-1" style="font-size: 0.7rem; text-transform: uppercase;">
                                Antrean Motor Terdaftar:
                            </div>
                            ${vehicleChips}
                        </div>
                    </div>
                </div>
            `;
        });

        $('#monitorSlotCards').html(html);
    }
});
</script>
@endpush
