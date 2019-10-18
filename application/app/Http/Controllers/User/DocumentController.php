<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\Agb;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(User $user, Request $request)
    {
        $this->authorizeViewingUser($user, $request);

        $documents = $user->documents->map(static function (Document $document) {
            return [
                'type' => 'Dokument',
                'title' => $document->name,
                'link' => $document->getDownloadUrl(),
                'created_at' => $document->created_at,
            ];
        });

        $agbs = $user->agbs->map(static function (Agb $agb) {
            return [
                'type' => __('AGB'),
                'title' => $agb->name,
                'link' => $agb->getDownloadUrl(),
                'created_at' => $agb->pivot->created_at,
            ];
        });

        return response()->view('users.documents', [
            'documents' => collect()
                ->merge($documents)
                ->merge($agbs)
                ->sortByDesc('created_at'),
        ]);
    }
}
