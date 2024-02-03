<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api', name: "api_")]
class UserController extends AbstractController
{
    #[Route('/users', name: 'api_create_user', methods: 'POST')]
    public function createUser(UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], 'true');
        }
        
        $user->setPassword($passwordHasher->hashPassword($user,$user->getPassword()));
        $user->setCustomer($this->getUser());
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
        
        return new JsonResponse(json_encode(["response"=>"User has been created"]), Response::HTTP_CREATED, [], true);
    }

    #[Route('/users', name: 'api_get_users', methods: 'GET')]
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 0);

        if ($page <= 0 or $limit < 0) {
            return throw new HttpException('404', "page need to be 1 or more and limit can be 0 or more");
        }

        /**
         * Create an ID cache
         */
        $idCache = "getAllUsers-" . $page . "-" . $limit;


        $jsonUsersList = $cache->get($idCache, function (ItemInterface $item) use ($userRepository, $page, $limit, $serializer) {
            $item->tag("UsersCache");

            if ($page > 0 and $limit == 0) {
                $productsList = $userRepository->findAllUsers($this->getUser());
            } else {
                $productsList = $userRepository->findAllWithPagination($page, $limit);
            }

            return $serializer->serialize($productsList, 'json',['groups' => 'getUsers']);
        });

        return new JsonResponse($jsonUsersList, Response::HTTP_OK, [], true);
    }

    #[Route('/users/{id}', name: 'api_get_user', methods: 'GET')]
    public function getOneUserById(int $id, UserRepository $userRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {
        /**
         * Create an ID cache
         */
        $idCache = "getOneUser-" . $id;

        $jsonUser = $cache->get($idCache, function (ItemInterface $item) use ($userRepository, $serializer, $id) {
            $item->tag("UsersCache");

            $user = $userRepository->findById($id,$this->getUser());

            if ($user) {
                return $serializer->serialize($user, 'json',['groups' => 'getUsers']);
            }
            return throw new HttpException('404', "The ID doesn't exists");
        });
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('/users/{id}', name: 'api_delete_user', methods: 'DELETE')]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface, TagAwareCacheInterface $cache): JsonResponse
    {
        $user = $userRepository->findOneById($id,$this->getUser());

        if ($user) {
            $cache->invalidateTags(['ProductsCache']);
            $entityManagerInterface->remove($user);
            $entityManagerInterface->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return throw new HttpException('404', "The ID doesn't exists");
    }
}
