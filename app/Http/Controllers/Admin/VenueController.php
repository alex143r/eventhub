<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVenueRequest;
use App\Http\Requests\Admin\UpdateVenueRequest;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): \Inertia\Response
    {
        $query = Venue::query();

        if (!empty($request->search)) {
            $query
                ->where(function ($query) use ($request) {
                    $query
                        ->where('name', 'LIKE', '%' . $request->search . '%');
                });
        }

        if (!empty($request->sortOption)) {
            $query->orderBy($request->sortOption, $request->order);
        }

        $venues = $query->get();

        return Inertia::render(
            'Venue/Index',
            [
                'venues' => $venues,
                'request' => $request,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVenueRequest $request
     * @return RedirectResponse
     */
    public function store(StoreVenueRequest $request): RedirectResponse
    {
        $venue = Venue::create($request->validated());


        return redirect()->route('admin::venues.edit', [
            'venue' => $venue
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Venue $venue
     * @return \Inertia\Response
     */
    public function edit(Venue $venue): \Inertia\Response
    {
        return Inertia::render(
            'Venue/Edit',
            [
                "venue" => $venue,
                'countries' => Country::list(),
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVenueRequest $request
     * @param Venue $venue
     * @return \Inertia\Response|RedirectResponse
     */
    public function update(UpdateVenueRequest $request, Venue $venue):  \Inertia\Response|RedirectResponse
    {
        $venue->update($request->validated());
        return redirect()->route('admin::venues.index')->with('success', "Successfully updated venue $venue->name");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Venue $venue
     * @return RedirectResponse
     */
    public function destroy(Venue $venue): RedirectResponse
    {
        $venue->delete();
        return back()->with('success', "Successfully deleted venue $venue->name");

    }

    public function search(Request $request): Collection|array
    {
        return Venue::query()
            ->where('name', 'LIKE', '%' . $request->search . '%')
            ->get();
    }
}
