<?php
namespace App\Service\Excel;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\HeaderUtils;
use function symfony\component\string\u;

trait Api {
    public function returnExcel(string $name="",string $fileContent='')
    {
        $response = new Response($fileContent);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('max-age', '0');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $name.' (services).xlsx',
            u($name)->ascii()   
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
}
