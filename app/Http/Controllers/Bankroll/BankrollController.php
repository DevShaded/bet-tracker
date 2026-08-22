<?php

namespace App\Http\Controllers\Bankroll;

use App\Actions\Bankroll\StoreBankrollAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bankroll\BankrollRequest;
use App\Models\Bankroll\Bankroll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class BankrollController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Bankroll::class);

        dd(Bankroll::where('user_id', auth()->id())->get());
    }

    public function create(): Response
    {
        Gate::authorize('create', Bankroll::class);

        return Inertia::render('bankroll/Create');
    }

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

        dd($bankroll);
    }
}
