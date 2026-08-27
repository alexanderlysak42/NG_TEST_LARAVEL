<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\GameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageAController extends Controller
{
    public function __construct(private readonly GameService $gameService)
    {
    }

    public function show(string $token): View|Response
    {
        $registration = $this->findValidRegistration($token);
        if ($registration === null) {
            return $this->linkInvalid();
        }

        return view('page_a', [
            'registration' => $registration,
            'lastResult' => null,
            'history' => null,
        ]);
    }

    public function regenerate(string $token): RedirectResponse|Response
    {
        $registration = $this->findValidRegistration($token);
        if ($registration === null) {
            return $this->linkInvalid();
        }

        $registration->update([
            'token' => Registration::generateToken(),
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('page-a.show', ['token' => $registration->token]);
    }

    public function deactivate(string $token): View|Response
    {
        $registration = $this->findValidRegistration($token);
        if ($registration === null) {
            return $this->linkInvalid();
        }

        $registration->update(['is_active' => false]);

        return view('link_invalid');
    }

    public function play(string $token): View|Response
    {
        $registration = $this->findValidRegistration($token);
        if ($registration === null) {
            return $this->linkInvalid();
        }

        $result = $this->gameService->play();
        $registration->gameResults()->create($result);

        return view('page_a', [
            'registration' => $registration,
            'lastResult' => $result,
            'history' => null,
        ]);
    }

    public function history(string $token): View|Response
    {
        $registration = $this->findValidRegistration($token);
        if ($registration === null) {
            return $this->linkInvalid();
        }

        $history = $registration->gameResults()->latest('id')->take(3)->get();

        return view('page_a', [
            'registration' => $registration,
            'lastResult' => null,
            'history' => $history,
        ]);
    }

    private function findValidRegistration(string $token): ?Registration
    {
        $registration = Registration::where('token', $token)->first();

        if ($registration === null || !$registration->isValid()) {
            return null;
        }

        return $registration;
    }

    private function linkInvalid(): Response
    {
        return response()->view('link_invalid', [], 404);
    }
}
