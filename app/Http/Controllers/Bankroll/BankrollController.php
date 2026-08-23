<?php

namespace App\Http\Controllers\Bankroll;

use App\Actions\Bankroll\GetBankrollAction;
use App\Actions\Bankroll\StoreBankrollAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bankroll\BankrollRequest;
use App\Models\Bankroll\Bankroll;
use App\Queries\Bankroll\GetBankrollStatsQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class BankrollController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Bankroll::class);

        return Inertia::render('bankroll/Index', [
            'stats' => GetBankrollStatsQuery::handle($request->user()),
            'bankrolls' => GetBankrollAction::handle($request->user())->load([
                'transactions' => fn ($query) => $query->latest('occurred_at'),
            ]),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Bankroll::class);

        return Inertia::render('bankroll/Create');
    }

    /**
     * @throws \Throwable
     */
    public function store(BankrollRequest $request): RedirectResponse
    {
        Gate::authorize('create', Bankroll::class);

        StoreBankrollAction::handle(
            $request->user(),
            $request->validated()
        );

        return redirect()->route('bankroll.index');
    }

    public function show(Bankroll $bankroll)
    {
        Gate::authorize('view', $bankroll);

        return $bankroll->load('transactions');
    }
}
