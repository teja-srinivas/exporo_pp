<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Link;
use App\Models\User;
use Tests\Traits\TestsControllers;

class LinkControllerTest extends TestCase
{
    use TestsControllers;

    /** @test */
    public function it_lists_links(): void
    {
        /** @var Link $link */
        $link = factory(Link::class)->create();

        $this->be($this->createActiveUserWithPermission('management.affiliate.links.view'));

        $response = $this->get('/affiliate/links');

        $response->assertOk();
        $response->assertSee($link->title);
        $response->assertSee($link->url);
    }

    /** @test */
    public function it_lists_short_links(): void
    {
        /** @var Link $link */
        $link = factory(Link::class)->create();

        $user = $this->createActiveUserWithPermission(
            'management.affiliate.links.view',
            'features.link-shortener.links'
        );

        $this->be($user);

        $response = $this->get('/affiliate/links');

        $response->assertOk();
        $response->assertSee($link->userInstance->getShortenedUrl());
    }

    /** @test */
    public function it_hides_links_for_certain_users(): void
    {
        $cannotSee = $this->createActiveUserWithPermission('management.affiliate.links.view');

        $canSee = $this->createActiveUserWithPermission('management.affiliate.links.view');

        /** @var User $canSee2 */
        $canSee2 = factory(User::class)->create();

        /** @var Link $link */
        $link = factory(Link::class)->create();
        $link->users()->attach([$canSee->getKey(), $canSee2->getKey()]);

        $this->be($canSee);
        $response = $this->get('/affiliate/links');
        $response->assertOk();
        $response->assertSee($link->title);

        $this->be($cannotSee);
        $response = $this->get('/affiliate/links');
        $response->assertOk();
        $response->assertDontSee($link->title);
    }
}
