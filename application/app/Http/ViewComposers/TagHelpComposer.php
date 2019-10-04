<?php

declare(strict_types=1);

namespace App\Http\ViewComposers;

use App\Models\User;
use Illuminate\View\View;
use App\Helper\TagReplacer;
use Illuminate\Support\Facades\Auth;

class TagHelpComposer
{
    public function compose(View $view)
    {
        /** @var User $user */
        $user = Auth::user();
        $tags = collect(TagReplacer::getUserTags($user))
            ->mapWithKeys(function ($value, string $name) {
                return [TagReplacer::wrap($name) => $value];
            });

        $view->with('tags', $tags);
    }
}
