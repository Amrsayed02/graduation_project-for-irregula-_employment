<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendFCMNotificationJob;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id'   => 'required|exists:users,id',
            'user_id'     => 'required|exists:users,id',
            'type'        => 'required', // user or vendor
            'date'        => 'required|date|after_or_equal:today',
            'time'        => 'required|date_format:H:i',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        try {
            // Check if there are pre-bookings for the same user and maintenance worker
            $existingReservation = Reservation::where('vendor_id', $request->vendor_id)
                ->where('user_id', $request->user_id)
                ->where('date', $request->date)
                ->where('time', $request->time)
                ->exists();

            if ($existingReservation) {
                return errorResponse("Reservation already available", 400);
            }

            $user = User::findOrFail($request->user_id);
            $peer = User::findOrFail($request->vendor_id);

            if ($user->type === 'vendor' && $peer->type === 'vendor') {
                return errorResponse("Reservations cannot be created between two service providers", 400);
            }
            if ($user->type === 'user' && $peer->type === 'user') {
                return errorResponse("Reservations can't be created between two users", 400);
            }

            // Create the reservation
            $reservation = Reservation::create([
                'user_id'     => $request->user_id,
                'vendor_id'   => $request->vendor_id,
                'date'        => $request->date,
                'time'        => $request->time,
                'price'       => $request->price,
                'description' => $request->description ?? null,
                'status'      => 'pending'
            ]);

            //Prepare messages after the reservation is created
            $messageFromAdminUser = "Hello {$user->name},\n\n" .
                "{$peer->name} Your reservation is confirmed. Booking details:\n" .
                "- History: {$request->date}\n" .
                "- Seed: {$request->time}\n" .
                "- Price: {$request->price} TL\n" .
                "- Explanation: " . ($request->description ?? 'No description') . "\n\n" .
                "If you have any questions, please contact us.\n\n" .
                "Thank you!";

            $messageFromAdminVendor = "Hello {$peer->name},\n\n" .
                "{$user->name} A new reservation has been created by . Booking details:\n" .
                "- History: {$request->date}\n" .
                "- Seed: {$request->time}\n" .
                "- Price: {$request->price} TL\n" .
                "- Explanation: " . ($request->description ?? 'No description') . "\n\n" .
                "Please log in to the system to confirm or reject.\n\n" .
                "Thank you!";

            // Send notifications (in a separate try block so as not to affect the booking creation process)
            try {
                Notification::send([$user], new UserMessage($messageFromAdminUser, 'New Reservation', $messageFromAdminUser, 'Yeni Rezervasyon', "user", ""));
                Notification::send([$peer], new UserMessage($messageFromAdminVendor, 'New Booking Notification', $messageFromAdminVendor, 'Yeni Rezervasyon Bildirimi', "vendor", ""));

                if ($user->fcm) {
                    SendFCMNotificationJob::dispatch($user->fcm, 'New Reservation', $messageFromAdminUser);
                }
                if ($peer->fcm) {
                    SendFCMNotificationJob::dispatch($peer->fcm, 'New Reservation', $messageFromAdminVendor);
                }
            } catch (\Throwable $notifyEx) {
                Log::error("Failure to send notifications: " . $notifyEx->getMessage());
            }

            return successResponse($reservation, 200, "Reservation created successfully");
        } catch (\Throwable $e) {
            Log::error('Error creating a reservation: ' . $e->getMessage());
            return errorResponse("Failed to create a reservation: " . $e->getMessage(), 500);
        }
    }

    public function getUserWorkerReservations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'worker_id' => 'required|exists:users,id',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        try {
            $query = Reservation::where(function ($q) use ($request) {
                $q->where('user_id', $request->user->id)
                    ->where('vendor_id', $request->worker_id);
            });

        


            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [
                    $request->input('start_date'),
                    $request->input('end_date')
                ]);
            }

            // Upload user and employee details
            $reservations = $query->with([
                'user' => function ($q) {
                    $q->select('id', 'name', 'email', 'phone')->with([
                        'category' => function ($query) {
                            $query->select('categories.id', 'categories.title');
                        },
                        'ratings' => function ($query) {
                            $query->select(
                                'ratings.id',
                                'ratings.vendor_id',
                                'ratings.rating',
                                'ratings.review',
                                'ratings.user_id', // Add user_id
                                'ratings.created_at' // Optional: include creation date
                            )->with([
                                'user' => function ($query) {
                                    $query->select(
                                        'id',
                                        'name',
                                        'image'
                                        // Add any other user fields you need
                                    );
                                }
                            ]);
                        },
                    ]); // Select specific user fields
                },
                'maintenanceWorker' => function ($q) {
                    $q->select('id', 'name', 'email', 'phone')->with([
                        'category' => function ($query) {
                            $query->select('categories.id', 'categories.title');
                        },
                        'ratings' => function ($query) {
                            $query->select(
                                'ratings.id',
                                'ratings.vendor_id',
                                'ratings.rating',
                                'ratings.review',
                                'ratings.user_id', // Add user_id
                                'ratings.created_at' // Optional: include creation date
                            )->with([
                                'user' => function ($query) {
                                    $query->select(
                                        'id',
                                        'name',
                                        'image'
                                        // Add any other user fields you need
                                    );
                                }
                            ]);
                        },
                    ]); // Select specific worker fields
                }
            ])
                ->orderBy('created_at', 'desc')
                ->get();

            return successResponse($reservations);
        } catch (\Throwable $e) {
            Log::error('Error receiving reservations: ' . $e->getMessage());
            return errorResponse(__('custom.reservations_al couldnot'), 500);
        }
    }

    public function checkAvailability(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|exists:users,id',
                'user_id'   => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return errorResponse($validator->errors()->first(), 422);
            }

            //Booking check for the same user and maintenance worker
            $reservation = Reservation::where('user_id', $request->user_id)
                ->where('vendor_id', $request->vendor_id)->whereIn("status", ["pending", "confirmed"])
                ->first();

            $available = $reservation ? true : false;
            return successResponse(
                [
                    'available'   => $available,
                    'reservation' => $reservation,
                ],
                200,
                $available ? "This time slot is already booked" : "Available"
            );
        } catch (\Throwable $e) {
            return errorResponse("Failed to receive a reservation", 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Reservation::query();

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [
                    $request->input('start_date'),
                    $request->input('end_date')
                ]);
            }
            $user = $request->user;
            // Upload user and employee details
            $reservations = $query->where('user_id', $request->user->id)->orWhere('vendor_id', $request->user->id)->with([
                'user' => function ($q) {
                    $q->select('id', 'name', 'email', 'phone', 'image',  'category_id')->with([
                        'category' => function ($query) {
                            $query->select('categories.id', 'categories.title');
                        },
                        'ratings' => function ($query) {
                            $query->select(
                                'ratings.id',
                                'ratings.vendor_id',
                                'ratings.rating',
                                'ratings.review',
                                'ratings.user_id', // Add user_id
                                'ratings.created_at' // Optional: include creation date
                            )->with([
                                'user' => function ($query) {
                                    $query->select(
                                        'id',
                                        'name',
                                        'image'
                                        // Add any other user fields you need
                                    );
                                }
                            ]);
                        },
                    ]); // Select specific user fields
                },
                'maintenanceWorker' => function ($q) {
                    $q->select('id', 'name', 'email', 'phone', 'image', 'category_id')->with([
                        'category' => function ($query) {
                            $query->select('categories.id', 'categories.title');
                        },
                        'ratings' => function ($query) {
                            $query->select(
                                'ratings.id',
                                'ratings.vendor_id',
                                'ratings.rating',
                                'ratings.review',
                                'ratings.user_id', // Add user_id
                                'ratings.created_at' // Optional: include creation date
                            )->with([
                                'user' => function ($query) {
                                    $query->select(
                                        'id',
                                        'name',
                                        'image'
                                        // Add any other user fields you need
                                    );
                                }
                            ]);
                        },
                    ]); // Select specific worker fields
                }
            ])
                ->orderBy('created_at', 'desc')
                ->get();

            return successResponse($reservations);
        } catch (\Throwable $e) {
            return errorResponse(__('custom.reservations_al couldnot'), 500);
        }
    }

    public function signReservation(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:sign,reject,complete',
            ]);

            if ($validator->fails()) {
                return errorResponse($validator->errors()->first(), 422);
            }

            $reservation = Reservation::findOrFail($id);
            $user = $request->user;

            // Check that the user is part of the booking
            if ($user->id !== $reservation->user_id && $user->id !== $reservation->vendor_id) {
                return errorResponse("You are not authorized to perform this operation", 403);
            }

            switch ($request->action) {
                case 'sign':
                    // If the ID matches the user_id, save the user's signature
                    if ($user->id === $reservation->user_id) {
                        $reservation->user_signup = true;
                    }
                    // If the ID matches the vendor_id, record the care worker's signature
                    elseif ($user->id === $reservation->vendor_id) {
                        $reservation->vendor_signup = true;
                    }

                    // If both parties have signed, update the reservation status to "confirmed"
                    if ($reservation->user_signup && $reservation->vendor_signup) {
                        $reservation->status = 'confirmed';
                    }
                    $reservation->save();
                    return successResponse($reservation, 200, "Signature saved successfully");

                case 'reject':
                    $reservation->status = 'cancelled';
                    $reservation->save();
                    return successResponse($reservation, 200, "Booking declined and cancelled");

                case 'complete':
                    if ($reservation->status !== 'confirmed') {
                        return errorResponse("The reservation cannot be completed until it is confirmed", 400);
                    }
                    $reservation->status = 'completed';
                    $reservation->save();
                    return successResponse($reservation, 200, "Booking completed successfully");

                default:
                    return errorResponse("Invalid transaction", 400);
            }
        } catch (\Exception $e) {
            return errorResponse("An error occurred while processing the request: " . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $reservation = Reservation::with([
                'user' => function ($q) {
                    $q->select('id', 'name', 'email', 'phone', 'category_id')->with([
                        'category' => function ($query) {
                            $query->select('categories.id', 'categories.title');
                        },
                        'ratings' => function ($query) {
                            $query->select(
                                'ratings.id',
                                'ratings.vendor_id',
                                'ratings.rating',
                                'ratings.review',
                                'ratings.user_id', // Add user_id
                                'ratings.created_at' // Optional: include creation date
                            )->with([
                                'user' => function ($query) {
                                    $query->select(
                                        'id',
                                        'name',
                                        'image'
                                        // Add any other user fields you need
                                    );
                                }
                            ]);
                        },
                    ]); // Select specific user fields
                },
                'maintenanceWorker' => function ($q) {
                    $q->select('id', 'name', 'email', 'phone', 'category_id')->with([
                        'category' => function ($query) {
                            $query->select('categories.id', 'categories.title');
                        },
                        'ratings' => function ($query) {
                            $query->select(
                                'ratings.id',
                                'ratings.vendor_id',
                                'ratings.rating',
                                'ratings.review',
                                'ratings.user_id', // Add user_id
                                'ratings.created_at' // Optional: include creation date
                            )->with([
                                'user' => function ($query) {
                                    $query->select(
                                        'id',
                                        'name',
                                        'image'
                                        // Add any other user fields you need
                                    );
                                }
                            ]);
                        },
                    ]); // Select specific worker fields
                }
            ])->findOrFail($id);

            return successResponse($reservation);
        } catch (\Throwable $e) {
            return errorResponse(__('custom.reservations_al  couldnot'), 500);
        }
    }

    public function updateReservation(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|exists:reservations,id',
            'date' => 'sometimes|date|after_or_equal:today',
            'time' => 'sometimes|date_format:H:i',
            'vendor_id' => 'sometimes|exists:users,id',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'sometimes|in:pending,confirmed,cancelled,completed'
        ]);

        // Check validation
        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        try {
            // Find the reservation
            $reservation = Reservation::findOrFail($request->reservation_id);

            // Authorize the update (only user who created the reservation can update)
            if ($reservation->user_id !== $request->user->id) {
                return errorResponse(__('You do not have permission to update the reservation.'), 403);
            }

            // Prepare update data (only include provided fields)
            $updateData = collect($request->only([
                'date',
                'time',
                'vendor_id',
                'price',
                'description',
                'status'
            ]))->filter()->toArray();

            // Perform update
            $reservation->update($updateData);

            // Refresh the model to get updated data
            $reservation->refresh();

            return successResponse(
                $reservation,
                200,
                __('Booking updated successfully')
            );
        } catch (\Throwable $e) {
            // Log error
            Log::error('Reservation update error: ' . $e->getMessage());

            return errorResponse(__('Reservation could not be updated.'), 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->update(['status' => $request->status]);

            return successResponse(
                $reservation,
                200,
                __('custom.reservation_status_updated')
            );
        } catch (\Throwable $e) {
            return errorResponse(__('Reservation could not be updated.'), 500);
        }
    }
}
