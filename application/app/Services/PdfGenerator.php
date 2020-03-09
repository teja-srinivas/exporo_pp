<?php

declare(strict_types=1);

namespace App\Services;

use DocRaptor\Doc;
use DocRaptor\DocApi;
use DocRaptor\Configuration;
use Illuminate\Support\Str;
use DocRaptor\PrinceOptions;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class PdfGenerator
{
    /** @var Application */
    protected $app;

    /** @var DocApi */
    protected $api;

    /** @var bool */
    protected $test = true;

    protected $apiKey = "YOUR_API_KEY_HERE";

    public function __construct(Application $app, DocApi $api)
    {
        $this->app = $app;
        $this->api = $api;
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

        $configuration = Configuration::getDefaultConfiguration();
        $configuration->setUsername($this->apiKey);

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
