<?php

declare(strict_types=1);

namespace App\Http\Controllers\Domains;

use App\Http\Controllers\Controller;
use App\Http\Requests\Domains\CheckDomainNowRequest;
use App\Http\Requests\Domains\CreateDomainRequest;
use App\Http\Requests\Domains\DestroyDomainRequest;
use App\Http\Requests\Domains\ShowDomainRequest;
use App\Http\Requests\Domains\UpdateCheckSettingsRequest;
use App\Http\Requests\Domains\UpdateDomainRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Src\Domains\Application\CheckAll\CheckAllDomainsCommand;
use Src\Domains\Application\CheckAll\CheckAllDomainsCommandHandler;
use Src\Domains\Application\CheckNow\CheckDomainNowCommandHandler;
use Src\Domains\Application\Create\CreateDomainCommandHandler;
use Src\Domains\Application\Delete\DeleteDomainCommandHandler;
use Src\Domains\Application\List\ListDomainsQuery;
use Src\Domains\Application\List\ListDomainsQueryHandler;
use Src\Domains\Application\Show\ShowDomainQueryHandler;
use Src\Domains\Application\Update\UpdateDomainCommandHandler;
use Src\Monitoring\Application\UpdateSettings\UpdateCheckSettingsCommandHandler;

final class DomainController extends Controller
{
    public function __construct(
        private readonly CreateDomainCommandHandler $create_handler,
        private readonly UpdateDomainCommandHandler $update_handler,
        private readonly DeleteDomainCommandHandler $delete_handler,
        private readonly ListDomainsQueryHandler $list_handler,
        private readonly ShowDomainQueryHandler $show_handler,
        private readonly UpdateCheckSettingsCommandHandler $settings_handler,
        private readonly CheckDomainNowCommandHandler $check_now_handler,
        private readonly CheckAllDomainsCommandHandler $check_all_handler
    ) {}

    public function index(): View
    {
        $domains = $this->list_handler->handle(
            new ListDomainsQuery(user_id: (int) auth()->id())
        );

        return view('domains.index', compact('domains'));
    }

    public function create(): View
    {
        return view('domains.create');
    }

    public function store(CreateDomainRequest $request): RedirectResponse
    {
        $domain_id = $this->create_handler->handle($request->makeCreateDomainCommand());

        return redirect()->route('domains.show', $domain_id)
            ->with('success', 'Success');
    }

    public function show(ShowDomainRequest $request): View
    {
        $domain = $this->show_handler->handle($request->makeShowDomainQuery());

        abort_if($domain === null, 404);

        return view('domains.show', ['domain' => $domain]);
    }

    public function edit(ShowDomainRequest $request): View
    {
        $domain = $this->show_handler->handle($request->makeShowDomainQuery());

        abort_if($domain === null, 404);

        return view('domains.edit', ['domain' => $domain]);
    }

    public function update(UpdateDomainRequest $request, int $domain): RedirectResponse
    {
        $this->update_handler->handle($request->makeUpdateDomainCommand());

        return redirect()->route('domains.show', $domain)
            ->with('success', 'Success.');
    }

    public function destroy(DestroyDomainRequest $request): RedirectResponse
    {
        $this->delete_handler->handle($request->makeDeleteDomainCommand());

        return redirect()->route('domains.index')
            ->with('success', 'Deleted.');
    }

    public function updateSettings(UpdateCheckSettingsRequest $request, int $domain): RedirectResponse
    {
        $this->settings_handler->handle($request->makeUpdateCheckSettingsCommand());

        return redirect()->route('domains.show', $domain)
            ->with('success', 'Updated');
    }

    public function checkNow(CheckDomainNowRequest $request): RedirectResponse
    {
        $this->check_now_handler->handle($request->makeCheckDomainNowCommand());

        return redirect()->route('domains.show', (int) $request->route('domain'))
            ->with('success', 'On queue');
    }

    public function checkAll(): RedirectResponse
    {
        $this->check_all_handler->handle(
            new CheckAllDomainsCommand(user_id: (int)auth()->id())
        );

        return redirect()->route('domains.index')
            ->with('success', 'On queue');
    }
}
