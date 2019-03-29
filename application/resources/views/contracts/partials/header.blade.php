<table class="table table-sm m-0 border-bottom">
    <tbody>
    <tr>
        <td>Partner-ID</td>
        <td>
            <span>#{{ $user->getKey() }}</span>
        </td>
    </tr>
    <tr>
        <td>Name</td>
        <td>
            <strong>
                {{ $user->first_name }}
                {{ $user->last_name }}
            </strong>
        </td>
    </tr>
    @unless(empty($user->details->company))
        <tr>
            <td>Firma</td>
            <td>
                {{ $user->details->company }}
            </td>
        </tr>
    @endunless
    <tr>
        <td>Straße</td>
        <td>
            {{ $user->details->address_street }}
            {{ $user->details->address_number }}
        </td>
    </tr>
    @unless(empty($user->details->address_addition))
        <tr>
            <td>Adresszusatz</td>
            <td>
                {{ $user->details->address_addition }}
            </td>
        </tr>
    @endunless
    <tr>
        <td>PLZ</td>
        <td>
            {{ $user->details->address_zipcode }}
        </td>
    </tr>
    <tr>
        <td>Stadt</td>
        <td>
            {{ $user->details->address_city }}
        </td>
    </tr>
    </tbody>
</table>
