<?php

namespace App\Services;

use DocRaptor\Doc;
use DocRaptor\DocApi;
use DocRaptor\PrinceOptions;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $request = Request::create($route);
        $response = $this->app->handle($request);

        // Send the request to DocRaptor - including the content
        $prince = new PrinceOptions();
        $prince->setCssDpi(300);
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