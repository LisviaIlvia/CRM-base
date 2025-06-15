<?php

namespace App\Http\Controllers;

use App\Services\ZoomService;
use Illuminate\Http\Request;
use App\Models\Zoom;

class ZoomController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    /**
     * Restituisce il token Zoom salvato nel database
     */
    public function getZoomToken()
    {
        $token = Zoom::first();
        return response()->json($token);
    }

    public function getUserId()
    {
        $userId = $this->zoomService->getUserId();

        if (!$userId) {
            return response()->json(['error' => 'Impossibile ottenere lo user_id'], 500);
        }

        return response()->json(['user_id' => $userId]);
    }

    /**
     * Crea un meeting Zoom
     */
    public function createMeeting(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'start_time' => 'required|date_format:Y-m-d\TH:i:s\Z',
            'duration' => 'required|integer',
        ]);

        $meeting = $this->zoomService->createMeeting(
            $request->input('topic'),
            $request->input('start_time'),
            $request->input('duration')
        );

        return response()->json($meeting);
    }

    public function getMeetings()
    {
        return response()->json($this->zoomService->getScheduledMeetings());
    }

    public function addParticipant(Request $request, $meetingId)
    {
        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        return response()->json(
            $this->zoomService->addParticipantToMeeting(
                $meetingId,
                $request->email,
                $request->first_name,
                $request->last_name
            )
        );
    }
}