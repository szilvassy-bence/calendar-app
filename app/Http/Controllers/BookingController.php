<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Services\BookingService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingController extends Controller
{

    public function __construct(protected BookingRepository $bookingRepository)
    {}
    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings);
    }

    public function store(BookingRequest $request)
    {
        try {
            $id = $this->bookingRepository->store($request);
            return response()->json(['message' => 'Booking created with id: ' . $id], 201);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }
    }
}
