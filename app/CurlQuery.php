<?php

use GuzzleHttp\Client;

class CurlQuery
{
    /**
     * Getting data from the b24
     *
     * @param string $queryUrl
     * @param string $queryData
     * @return array
     */
    public static function exec($queryUrl, $queryData)
    {
        try {
            $client = new Client([
                'base_uri' => $queryUrl,
            ]);

            $response = $client->post('', [
                'debug' => false,
                'verify' => false,
                'body' => $queryData
            ]);

            $output = json_decode($response->getBody()->getContents(), 1);

            return $output;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
