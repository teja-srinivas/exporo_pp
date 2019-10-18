<?php

namespace App\Services;

use DocRaptor\Doc;
use DocRaptor\DocApi;
use Illuminate\Support\Str;
use DocRaptor\PrinceOptions;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Config\Repository;

class PdfGenerator
{
    /** @var Application */
    protected $app;

    /** @var DocApi */
    protected $api;

    /** @var bool */
    protected $test;

    public function __construct(Application $app, DocApi $api, Repository $config)
    {
        $this->app = $app;
        $this->api = $api;
        $this->test = $config->get('services.docraptor.test');
    }

    /**
     * @param string $route The route to render
     * @return string the PDF content as string
     * @throws \DocRaptor\ApiException
     * @throws \Exception During rendering the document content
     */
    public function render(string $route): string
    {
        // Run an internal request to generate the raw HTML content
        $response = $this->app->handle(Request::create($route));

        // Send the request to DocRaptor - including the content
        $prince = new PrinceOptions();
        $prince->setDisallowModify(true);
        $prince->setOwnerPassword(Str::random());

        $doc = new Doc();
        $doc->setTest($this->test);
        $doc->setPrinceOptions($prince);
        $doc->setDocumentType('pdf');
        $doc->setDocumentContent($response->getContent());

        return $this->api->createDoc($doc);
    }
}
