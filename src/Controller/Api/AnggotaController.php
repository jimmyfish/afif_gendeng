<?php

namespace App\Controller\Api;

use App\Libraries\ResponseBase;
use App\Repository\AnggotaRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AnggotaController extends AbstractController
{
    private $anggotaRepository;

    public function __construct(AnggotaRepository $anggotaRepository)
    {
        $this->anggotaRepository = $anggotaRepository;
    }

    public function index(): JsonResponse
    {
        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => 'welcome to api'
        ];
        return new JsonResponse($data,Response::HTTP_OK);
    }

    public function me(UserInterface $anggota): JsonResponse
    {
        $response = [
            'status' => 'success',
            'code' => 200,
            'data' => $anggota
        ];

        return ResponseBase::success($response);
    }

    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $email = $data['email'];
        $password = $data['password'];
        $phoneNumber = $data['phone_number'];

        $dataAnggota = $this->anggotaRepository->saveAnggota($firstName,$lastName,$email,$password,['ROLE_USER'],$phoneNumber);

        $response = [
            'status' => 'success',
            'code' => 200,
            'data' => $dataAnggota
        ];

        return ResponseBase::success($response);
    }
}