<?php

namespace App\Libraries;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ResponseBase 
{
    public static function success($dataResponse): JsonResponse
    {
        $response = [];

        $data = isset($dataResponse['data']) ? $dataResponse['data'] : null;

        if($data != null)
        {
            $encoder = new JsonEncoder();

            // $dateCallback = function ($innerObject) {
            //     return $innerObject instanceof \DateTime ? $innerObject->format('Y-m-d H:i:s') : '';
            // };

            // $defaultContext = [
            //     AbstractNormalizer::CALLBACKS => [
            //         'createdAt' => $dateCallback,
            //         'updatedAt' => $dateCallback
            //     ],
            // ];

            $normalizer = new GetSetMethodNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, null, null, []);

            $serializer = new Serializer([$normalizer], [$encoder]);

            $normalizerData = $serializer->normalize($dataResponse['data'],null,[AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','updatedAt','roles']]);
        }

        $response['status'] = 'success';
        $response['code']   = 200;
        $response['data']   = $data != null ? $normalizerData : $data;

        return new JsonResponse($response,Response::HTTP_OK);
    }

    public static function error()
    {

    }
}