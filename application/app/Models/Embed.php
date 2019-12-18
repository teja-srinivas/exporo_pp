<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Embed extends Model
{
    public static $placeholders = [
        'equity' => [
            "Laufende Ausschüttungen \n zwischen 3-6 % pro Jahr",
            "Beteiligung an der Wertentwicklung",
            "Persönlicher Ansprechpartner",
            "Hohe Transparenz",
        ],
        'finance' => [
            "Geprüfte Projekte von \n Top-Immobilienexperten",
            "Jede Woche neue exklusive Projekte \n beim Marktführer",
            "Persönlicher Ansprechpartner",
            "Immobilienportfolio ab 500 €",
        ],
    ];

    public static $linkTitles = [
        'Startseite' => null,
        'Alle Projekte' => null,
        'Exporo Bestand Projekte' => 'equity',
        'Exporo Finanzierung Projekte' => 'finance',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
