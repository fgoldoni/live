<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\VerifyMagicLinkAndLogin;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;

final readonly class VerifyAndConsumeMagicLinkController
{
    private const string ROUTE_DASHBOARD = 'dashboard';

    private const string ROUTE_LOGIN = 'login';

    private const string MSG_ALREADY = 'This magic link was already used. You are signed in.';

    private const string MSG_INVALID = 'This magic link is invalid or already used.';

    public function __construct(
        private VerifyMagicLinkAndLogin $verifyMagicLinkAndLogin,
        private StatefulGuard $statefulGuard,
        private Redirector $redirector,
        private UrlGenerator $urlGenerator,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(Request $request, int $user, string $token): RedirectResponse
    {
        try {
            $this->verifyMagicLinkAndLogin->execute($user, $token);

            return $this->redirector->intended(
                $this->urlGenerator->route(self::ROUTE_DASHBOARD)
            );
        } catch (ValidationException $validationException) {
            if ($this->statefulGuard->check() && (int) $this->statefulGuard->id() === $user) {
                return $this->redirector
                    ->intended($this->urlGenerator->route(self::ROUTE_DASHBOARD))
                    ->with('status', __(self::MSG_ALREADY));
            }

            $this->logger->warning('Magic link consumption failed', [
                'user_id' => $user,
                'ip'      => $request->ip(),
                'message' => $validationException->getMessage(),
                'errors'  => $validationException->errors(),
            ]);

            return $this->redirector
                ->to($this->urlGenerator->route(self::ROUTE_LOGIN))
                ->withErrors([
                    'magic' => $validationException->errors()['token'][0] ?? __(self::MSG_INVALID),
                ]);
        }
    }
}
