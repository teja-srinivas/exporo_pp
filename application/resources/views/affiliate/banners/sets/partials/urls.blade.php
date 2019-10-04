@php($data = [
    'values' => old('urls', $set->links->map(function (App\Models\BannerLink $link) {
        return [
            'id' => $link->getKey(),
            'title' => $link->title,
            'url' => $link->url,
        ];
    })),
    'errors' => $errors->get('urls.*'),
])

<vue v-cloak class="cloak-fade" data-is="url-input" data-props='@json($data)'></vue>
