@php($data = [
    'values' => old('urls', $set->links->map(function (App\BannerLink $link) {
        return [
            'id' => $link->getKey(),
            'key' => $link->title,
            'value' => $link->url,
        ];
    })),
    'errors' => $errors->get('urls.*'),
])

<vue v-cloak class="cloak-fade" data-is="url-input" data-props='@json($data)'></vue>
