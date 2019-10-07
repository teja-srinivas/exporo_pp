<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Mailing;

use Tests\TestCase;
use App\Models\Mailing;
use App\Policies\MailingPolicy;
use Tests\Traits\TestsControllers;

class PreviewControllerTest extends TestCase
{
    use TestsControllers;

    /**
     * @test
     */
    public function it_only_allows_users_with_view_permission()
    {
        $this->be($this->createActiveUser());

        $reponse = $this->get(route('affiliate.mails.preview', 1));

        $reponse->assertForbidden();
    }

    /**
     * @test
     */
    public function it_replaces_variables_for_mail_previews()
    {
        $user = $this->createActiveUserWithPermission(MailingPolicy::PERMISSION.'.view');

        $this->be($user);

        $mailing = factory(Mailing::class)->create([
            'html' => '<b>ID ${partnerid}</b>, Your ${partnername} (<a href="//track.me/${reflink}"/>click here</a>',
        ]);

        $response = $this->get(route('affiliate.mails.preview', $mailing));
        $expected = '<b>ID '.$user->getKey().'</b>, Your '.$user->first_name.' '.$user->last_name
            .' (<a href="//track.me/?a_aid='.$user->getKey().'"/>click here</a>';

        $this->assertEquals($expected, $response->content());
    }

}
