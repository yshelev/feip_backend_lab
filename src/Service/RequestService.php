<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateRequestDto;
use App\Dto\UpdateRequestDto;
use App\Entity\Request;
use App\Repository\HouseRepository;
use App\Repository\RequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class RequestService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestRepository $requestRepository,
        private readonly HouseRepository $houseRepository,
        private readonly UserRepository $userRepository,
        private readonly SummerHouseService $summerHouseService
    ) {
    }

    public function createEntity(CreateRequestDto $createRequestDto): object|null
    {
        $house = $this->houseRepository->find($createRequestDto->houseId);
        $user = $this->userRepository->findByPhoneNumber($createRequestDto->phoneNumber);

        $request = Request::create(
            $createRequestDto->comment,
            $user,
            $house
        );

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $request;
    }

    public function changeRequestComment(int $id, string $comment): object|null
    {
        $request = $this->requestRepository->find($id);
        $request->setComment($comment);
        $this->entityManager->flush();

        return $request;
    }

    public function replaceRequest(UpdateRequestDto $requestDto): object|null
    {
        $request = $this->requestRepository->find($requestDto->id);
        $house = $this->houseRepository->find($requestDto->houseId);
        $user = $this->userRepository->findByPhoneNumber($requestDto->phoneNumber);

        $request->setComment($requestDto->comment);
        $request->setUser($user);
        $request->setHouse($house);
        $this->entityManager->flush();

        return $request;
    }

    public function getOneRequestById(int $id): object|null
    {
        $request = $this->requestRepository->find($id);

        return $request;
    }
}
