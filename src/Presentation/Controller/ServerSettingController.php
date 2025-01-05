<?php

namespace App\Presentation\Controller;

use App\Core\Application\Repository\ServerSettingRepositoryInterface;
use App\Core\Domain\DTO\ServerSettingDTO;
use App\Core\Domain\Entity\ServerSetting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/server-settings', 'api_server_settings_')]
final class ServerSettingController extends AbstractController
{
    #[Route('', 'list', methods: ['GET'])]
    public function list(
        ServerSettingRepositoryInterface $serverSettingRepository
    ): Response {
        return $this->json($serverSettingRepository->list(), Response::HTTP_OK, context: ['groups' => 'server-setting']);
    }

    #[Route('', 'post', methods: ['POST'])]
    public function post(
        #[MapRequestPayload]
        ServerSettingDTO $serverSettingDTO,
        ServerSettingRepositoryInterface $serverSettingRepository
    ): Response {
        $serverSetting = ServerSetting::create(
            $serverSettingDTO->key,
            $serverSettingDTO->type,
            $serverSettingDTO->description
        );

        $serverSettingRepository->save($serverSetting);

        return $this->json($serverSetting, Response::HTTP_CREATED, context: ['groups' => 'server-setting']);
    }

    #[Route('/{serverSetting}', 'put', methods: ['PUT'])]
    public function put(
        ServerSetting $serverSetting,
        #[MapRequestPayload]
        ServerSettingDTO $serverSettingDTO,
        ServerSettingRepositoryInterface $serverSettingRepository
    ): Response {
        $serverSetting->setKey($serverSettingDTO->key);
        $serverSetting->setType($serverSettingDTO->type);
        $serverSetting->setDescription($serverSettingDTO->description);

        $serverSettingRepository->save($serverSetting);

        return $this->json($serverSetting, Response::HTTP_OK, context: ['groups' => 'server-setting']);
    }

    #[Route('/{serverSetting}', 'delete', methods: ['DELETE'])]
    public function delete(
        ServerSetting $serverSetting,
        ServerSettingRepositoryInterface $serverSettingRepository
    ): Response {
        $serverSettingRepository->delete($serverSetting);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
