<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Repositories\Contracts\ServicePackageRepositoryInterface;
use App\Repositories\Contracts\ServicePartRepositoryInterface;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected ServicePackageRepositoryInterface $packageRepo;
    protected ServicePartRepositoryInterface $partRepo;

    public function __construct(
        BookingService $bookingService,
        ServicePackageRepositoryInterface $packageRepo,
        ServicePartRepositoryInterface $partRepo
    ) {
        $this->bookingService = $bookingService;
        $this->packageRepo = $packageRepo;
        $this->partRepo = $partRepo;
    }

    /**
     * Display the SPA view or return JSON list of bookings for AJAX.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            return $this->getBookingsData($request);
        }

        $packages = $this->packageRepo->getActivePackages();
        $parts = $this->partRepo->getActiveParts();
        $today = Carbon::today()->toDateString();
        $operationalSlots = $this->bookingService->getOperationalSlots();

        return view('bookings.index', compact('packages', 'parts', 'today', 'operationalSlots'));
    }

    /**
     * AJAX endpoint to get filtered bookings list with stats.
     */
    public function getBookingsData(Request $request): JsonResponse
    {
        $filters = [
            'date' => $request->input('date'),
            'status' => $request->input('status'),
            'package_id' => $request->input('package_id'),
            'search' => $request->input('search'),
        ];

        $result = $this->bookingService->getBookingsList($filters);

        return response()->json([
            'success' => true,
            'data' => $result['bookings'],
            'stats' => $result['stats'],
            'count' => $result['count'],
        ]);
    }

    /**
     * AJAX endpoint to check availability of all slots for a given date.
     */
    public function getSlots(Request $request): JsonResponse
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $slots = $this->bookingService->getAvailableSlots($date);

        return response()->json([
            'success' => true,
            'date' => $date,
            'slots' => $slots,
        ]);
    }

    /**
     * Store a newly created booking with Service and Repository flow.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());

            return response()->json([
                'success' => true,
                'message' => "Booking servis untuk {$booking->plate_number} berhasil didaftarkan pada slot jam {$booking->service_time}!",
                'data' => $booking,
            ], 201);

        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => [
                    'service_time' => [$e->getMessage()]
                ],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show booking details.
     */
    public function show(int $id): JsonResponse
    {
        $booking = $this->bookingService->getBookingDetails($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data booking tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $booking,
            'parts' => $booking->parts_details,
        ]);
    }

    /**
     * Update booking status.
     */
    public function updateStatus(UpdateBookingStatusRequest $request, int $id): JsonResponse
    {
        try {
            $booking = $this->bookingService->updateBookingStatus(
                $id,
                $request->input('status'),
                $request->input('notes')
            );

            return response()->json([
                'success' => true,
                'message' => "Status booking {$booking->booking_code} berhasil diperbarui menjadi: " . strtoupper($booking->status),
                'data' => $booking->load('servicePackage'),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data booking tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Delete booking.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->bookingService->deleteBooking($id);

            return response()->json([
                'success' => true,
                'message' => "Data booking berhasil dihapus dari sistem.",
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data booking tidak ditemukan.',
            ], 404);
        }
    }
}
