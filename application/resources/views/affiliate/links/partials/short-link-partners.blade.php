<div class="form-group row mb-0">
    <div class="col-sm-3 col-form-label">
        Freigeschaltet für Partner:
    </div>

    <div class="col-sm-9 col-form-label">
        <?php
            $oldIds = array_keys(old("user", []));
            $activeIds = !empty($oldIds) ? $oldIds : (isset($link) ? $link->users()->pluck('user_id')->toArray() : []);
        ?>

        @foreach($shortLinkPartners as $id => $name)
            @component('components.form.checkbox', [
                'name' => "user[{$id}]",
                'design' => 'switch',
                'default' => in_array($id, $activeIds),
            ])
                {{ $name }}
                <small class="text-muted ml-1">#{{ $id }}</small>
            @endcomponent
        @endforeach

        <small class="form-text text-muted">
            Nur Partner mit der <strong>@lang('permissions.link-shortener')</strong> Berechtigung erscheinen hier.<br>
            Falls keine Partner ausgewählt sind, ist der Link für alle sichtbar.
        </small>
    </div>
</div>
