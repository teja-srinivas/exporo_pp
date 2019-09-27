<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Mailing;

use Tests\TestCase;
use App\Models\Mailing;
use App\Policies\MailingPolicy;
use Tests\Traits\TestsControllers;

class DownloadControllerTest extends TestCase
{
    use TestsControllers;

    /**
     * @test
     */
    public function it_only_allows_users_with_view_permission()
    {
        $this->be($this->createActiveUser());

        $reponse = $this->get(route('affiliate.mails.download', 1));

        $reponse->assertForbidden();
    }

    /**
     * @test
     */
    public function it_streams_html_downloads()
    {
        $this->be($this->createActiveUserWithPermission(MailingPolicy::PERMISSION.'.view'));

        $mailing = factory(Mailing::class)->create();

        $response = $this->get(route('affiliate.mails.download', $mailing));

        $this->assertStreamEquals($mailing->html, $response->baseResponse);
    }

}
