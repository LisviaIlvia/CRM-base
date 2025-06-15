<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Zoom;

class ZoomService
{
    protected $clientId;
    protected $clientSecret;
    protected $accountId;
    protected $apiUrl;
    protected $tokenUrl;

    public function __construct()
    {
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->accountId = config('services.zoom.account_id');
        $this->apiUrl = config('services.zoom.api_url');
        $this->tokenUrl = config('services.zoom.token_url');
    }

    /**
     * Richiede un access token usando Server-to-Server OAuth
     */
    public function requestAccessToken()
    {
        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        ])->post($this->tokenUrl, [
            'grant_type' => 'account_credentials',
            'account_id' => $this->accountId,
        ]);

        $data = $response->json();

        if (isset($data['access_token'])) {
            $expiresAt = now()->addSeconds($data['expires_in']);

            Zoom::updateOrCreate([], [
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'],
                'expires_at' => $expiresAt,
            ]);

            return $data['access_token'];
        }

        Log::error("Errore nella richiesta del token Zoom: ", $data);
        return null;
    }

    /**
     * Restituisce un access token valido (rinnova se necessario)
     */
    public function getValidAccessToken()
    {
        $token = Zoom::first();

        if (!$token || now()->greaterThan($token->expires_at)) {
            return $this->requestAccessToken();
        }

        return $token->access_token;
    }

    public function getUserId()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            Log::error("Errore: Nessun access token disponibile.");
            return null;
        }

        $response = Http::withToken($accessToken)->get('https://api.zoom.us/v2/users/me');

        if ($response->failed()) {
            Log::error("Errore API Zoom: " . $response->body());
            return null;
        }

        $data = $response->json();
        return $data['id'] ?? null;
    }

    /**
     * Crea un meeting su Zoom
     */
    public function createMeeting($topic, $startTime, $duration = 30)
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            Log::error("Errore: Nessun access token disponibile.");
            return response()->json(['error' => 'Token non disponibile'], 401);
        }

        $userId = "me"; 

        $response = Http::withToken($accessToken)->post($this->apiUrl . "/users/{$userId}/meetings", [
            "topic" => $topic,
            "type" => 2, // Scheduled Meeting
            "start_time" => $startTime,
            "duration" => $duration,
            "timezone" => "Europe/Rome",
            "password" => "123456",
            "agenda" => "Meeting Zoom",
            "settings" => [
                "host_video" => true,
                "participant_video" => true,
                "mute_upon_entry" => true,
                "waiting_room" => true,
                "approval_type" => 2,
                "audio" => "voip",
                "auto_recording" => "cloud"
            ]
        ]);

        return $response->json();
    }

    public function getScheduledMeetings()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            Log::error("Errore: Nessun access token disponibile.");
            return response()->json(['error' => 'Token non disponibile'], 401);
        }

        $response = Http::withToken($accessToken)->get($this->apiUrl . '/users/me/meetings');

        return $response->json();
    }

    public function addParticipantToMeeting($meetingId, $email, $firstName, $lastName)
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            Log::error("Errore: Nessun access token disponibile.");
            return response()->json(['error' => 'Token non disponibile'], 401);
        }

        $response = Http::withToken($accessToken)->post($this->apiUrl . "/meetings/{$meetingId}/registrants", [
            "email" => $email,
            "first_name" => $firstName,
            "last_name" => $lastName
        ]);

        return $response->json();
    }
}