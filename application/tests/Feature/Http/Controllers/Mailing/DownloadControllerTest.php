<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Mailing;

use Tests\TestCase;
use App\Models\Mailing;
use Tests\TestsControllers;
use App\Policies\MailingPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DownloadControllerTest extends TestCase
{
    use DatabaseTransactions;
    use TestsControllers;

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
