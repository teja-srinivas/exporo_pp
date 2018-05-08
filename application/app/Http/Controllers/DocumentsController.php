<?php

namespace App\Http\Controllers;

use App\Agb;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index()
    {
        $documents = collect(); // TODO we will have some later (I hope)

        // Always add the AGB
        $documents = $documents->merge(auth()->user()->agbs()->latest()->get()->map(function (Agb $agb) {
            return [
                'type' => __('AGB'),
                'title' => $agb->name,
                'link' => route('agbs.download', $agb),
                'created_at' => $agb->pivot->created_at,
            ];
        }));

        return view('documents.index', compact('documents'));
    }
}
