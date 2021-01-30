<?php

namespace MiladRahimi\PhpRouter\Services;

use Psr\Http\Message\ResponseInterface;

class HttpPublisher implements Publisher
{
    /**
     * @inheritdoc
     */
    public function publish($content): void
    {
        $content = empty($content) ? null : $content;

        $output = fopen('php://output', 'a');

        if ($content instanceof ResponseInterface) {
            http_response_code($content->getStatusCode());

            foreach ($content->getHeaders() as $name => $values) {
                @header($name . ': ' . $content->getHeaderLine($name));
            }

            fwrite($output, $content->getBody());
        } elseif (is_scalar($content)) {
            fwrite($output, $content);
        } elseif ($content === null) {
            fwrite($output, '');
        } else {
            fwrite($output, json_encode($content));
        }

        fclose($output);
    }
}
