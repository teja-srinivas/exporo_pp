<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campaign;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize(Campaign::class);

        return view('campaigns.index', [
            'campaigns' => Campaign::query()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize(Campaign::class);

        return view('campaigns.create', [
            'users' => $this->getUsers(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Campaign  $campaign
     * @return \Illuminate\View\View
     */
    public function show(Campaign $campaign)
    {
        $user = Auth::user();
        $users = $campaign->users->map(static function ($campaignUser) {
            return $campaignUser->id;
        })->all();

        if (
            !$campaign->all_users
            && (
                (($campaign->is_blacklist && in_array($user->id, $users))
                || (!$campaign->is_blacklist && !in_array($user->id, $users)))
            )
        ) {
            abort(404);
        }

        $campaign->image_url = $campaign->getImageDownloadUrl();
        $campaign->document_url = $campaign->getDocumentDownloadUrl();
        $campaign->link = $campaign->url !== null ? $campaign->userInstance->toHtml() : null;

        return view('campaigns.show', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign $campaign
     * @return \Illuminate\View\View
     */
    public function edit(Campaign $campaign)
    {
        $this->authorize(Campaign::class);

        $campaign->image_url = $campaign->getImageDownloadUrl();
        $campaign->document_url = $campaign->getDocumentDownloadUrl();
        $campaign->selection = $campaign->users->map(static function ($user) {
            return $user->id;
        });

        return view('campaigns.edit', [
            'campaign' => $campaign,
            'users' => $this->getUsers(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Campaign $campaign)
    {
        $this->authorize(Campaign::class);

        if (isset($campaign->image_url)) {
            $image = $campaign->getImageDownloadUrl();
            Storage::disk($campaign->disk)->delete($image);
        }

        if (isset($campaign->document_url)) {
            $document = $campaign->getImageDownloadUrl();
            Storage::disk($campaign->disk)->delete($document);
        }

        $campaign->instances()->delete();
        $campaign->users()->detach();
        $campaign->delete();

        return redirect()->route('campaigns.index');
    }

    private function getUsers()
    {
        $userQuery = null;

        if (Auth::user()->can('delete', new User())) {
            $userQuery = User::withTrashed();
        }

        $userRepository = new UserRepository();

        return $userRepository->forTableView($userQuery);
    }
}
