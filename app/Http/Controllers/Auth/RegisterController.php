<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Src\Auth\Application\Register\RegisterCommandHandler;

final class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterCommandHandler $handler
    ) {}

    public function showForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->handler->handle($request->makeRegisterCommand());

        Auth::login($user);

        return redirect()->route('domains.index');
    }
}
