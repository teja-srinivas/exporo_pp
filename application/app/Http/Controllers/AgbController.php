<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Agb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AgbController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Agb::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Agb::query()
            ->leftJoin('agb_user', 'agb_user.agb_id', 'agbs.id')
            ->latest()
            ->groupBy('agbs.id')
            ->selectRaw('count(user_id) as users')
            ->selectRaw('agbs.*')
            ->get();

        return response()->view('agbs.index', compact('list', 'canDelete'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('agbs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(Agb::TYPES)],
            'name' => 'required|string',
            'effective_from' => 'required|date',
            'file' => 'required|mimes:pdf',
        ]);

        $agb = DB::transaction(static function () use ($data, $request) {
            // Replace the old file, if exists
            $agb = new Agb($data);
            $agb->filename = $request['type'];
            Storage::disk('s3')->put(Agb::DIRECTORY.'/'.$request['name'], $request->file('file')->get());
            $agb->is_default = $request->has('is_default');

            $agb->saveOrFail();

            User::query()
                ->get()
                ->each(static function (User $user) use ($agb) {
                    $activeAgb = $user->activeAgbByType($agb->type);

                    if ($activeAgb === null) {
                        return;
                    }

                    $user->agbs()->attach($agb);

                    DB::table('agb_user')
                        ->where('user_id', $user->id)
                        ->where('agb_id', $agb->id)
                        ->update([
                            'created_at' => $activeAgb->pivot->created_at,
                            'updated_at' => $activeAgb->pivot->created_at,
                        ]);
                });

            return $agb;
        });

        flash_success('AGB wurden angelegt');

        return redirect()->route('agbs.edit', $agb);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agb  $agb
     * @param  UserRepository  $userRepository
     * @return \Illuminate\Http\Response
     */
    public function show(Agb $agb, UserRepository $userRepository)
    {
        $users = $userRepository->forTableView($agb->users()->getQuery());

        return response()->view('agbs.show', compact('agb', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agb $agb
     * @return \Illuminate\Http\Response
     */
    public function edit(Agb $agb)
    {
        return response()->view('agbs.edit', compact('agb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Agb $agb
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request, Agb $agb)
    {
        $data = $this->validate($request, [
            'type' => ['required', Rule::in(Agb::TYPES)],
            'name' => 'required|string',
            'effective_from' => 'required|date',
            'file' => 'nullable|mimes:pdf',
        ]);

        // Replace the old file, if exists
        if ($request->has('file')) {
            $oldFilename = $agb->filename;
            $filename = $request['name'];
            Storage::disk('s3')->put(Agb::DIRECTORY.'/'.$filename, $request->file('file')->get());

            if (! empty($oldFilename)) {
                Storage::delete($oldFilename);
            }
        }

        $agb->fill($data);
        $agb->is_default = $request->has('is_default');

        $agb->saveOrFail();

        flash_success();

        return redirect()->route('agbs.edit', $agb);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agb  $agb
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Agb $agb)
    {
        //abort_unless($agb->canBeDeleted(), Response::HTTP_LOCKED, 'Users may have already accepted these AGB');

        $agb->delete();
        Storage::delete($agb->filename);

        return redirect()->route('agbs.index');
    }

    /**
     * Responds with a file download for the given AGB.
     * Errors if the file does not exist.
     *
     * @param Agb $agb
     * @return Response
     */
    public function download(Agb $agb): Response
    {
        $expiration = now()->addHour();
        $url = Storage::disk('s3')->temporaryUrl(Agb::DIRECTORY.'/'.$agb->name, $expiration, [
            'ResponseContentDisposition' => "attachment; filename=\"{$agb->getReadableFilename()}\"",
        ]);

        return response()->redirectTo($url);
    }

    /**
     * Finds and redirects to the latest AGB of the given type.
     *
     * @param string $type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function latest(string $type = '')
    {
        $agb = Agb::current($type);

        abort_if($agb === null, 404);

        return redirect($agb->getDownloadUrl());
    }
}
