<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRegistrationLinkException;
use App\Models\Registration;
use App\Services\GameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Random\RandomException;

class PageAController extends Controller
{
    public function __construct(private readonly GameService $gameService) {}

    /**
     * @throws InvalidRegistrationLinkException
     */
    public function show(string $token): View
    {
        $registration = $this->findValidRegistration($token);

        return view('page_a', [
            'registration' => $registration,
            'lastResult' => null,
            'history' => null,
        ]);
    }

    /**
     * @throws InvalidRegistrationLinkException
     * @throws RandomException
     */
    public function regenerate(string $token): RedirectResponse
    {
        $registration = $this->findValidRegistration($token);

        $registration->update([
            'token' => Registration::generateToken(),
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('page-a.show', ['token' => $registration->token]);
    }

    /**
     * @throws InvalidRegistrationLinkException
     */
    public function deactivate(string $token): View
    {
        $registration = $this->findValidRegistration($token);

        $registration->update(['is_active' => false]);

        return view('link_invalid');
    }

    /**
     * @throws InvalidRegistrationLinkException
     * @throws RandomException
     */
    public function play(string $token): View
    {
        $registration = $this->findValidRegistration($token);

        $result = $this->gameService->play();
        $registration->gameResults()->create($result);

        return view('page_a', [
            'registration' => $registration,
            'lastResult' => $result,
            'history' => null,
        ]);
    }

    /**
     * @throws InvalidRegistrationLinkException
     */
    public function history(string $token): View
    {
        $registration = $this->findValidRegistration($token);

        $history = $registration->gameResults()->latest('id')->take(3)->get();

        return view('page_a', [
            'registration' => $registration,
            'lastResult' => null,
            'history' => $history,
        ]);
    }

    /**
     * @throws InvalidRegistrationLinkException
     */
    private function findValidRegistration(string $token): Registration
    {
        $registration = Registration::where('token', $token)->first();

        if ($registration === null || ! $registration->isValid()) {
            throw new InvalidRegistrationLinkException;
        }

        return $registration;
    }
}
