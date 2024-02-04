<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
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
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

#[Route('/api', name: "api_")]
class UserController extends AbstractController
{
    #[OA\Response(
        response: 201,
        description: 'Create an user'
    )]
    #[OA\Parameter(
        name: 'firstname',
        description: 'The firstname of user you want to add',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'lastname',
        description: 'The lastname of user you want to add',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'email',
        description: 'The email of user you want to add',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'password',
        description: 'The password (without encrypt) of user you want to add',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'customer',
        description: 'The field is an auto value of your customer id',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    #[Route('/users', name: 'api_create_user', methods: 'POST')]
    /**
     * This function creates a user by deserializing the request content, validating the user data,
     * hashing the password, and persisting the user in the database.
     * 
     */
    public function createUser(UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json',['groups' => 'createUser']), JsonResponse::HTTP_BAD_REQUEST, [], 'true');
        }
        
        $user->setPassword($passwordHasher->hashPassword($user,$user->getPassword()));
        $user->setCustomer($this->getUser());
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
        
        return new JsonResponse(json_encode(["response"=>"User has been created"]), Response::HTTP_CREATED, [], true);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns all users of API relative with a client',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['getUsers']))
        )
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        description: 'The field used to get number of page do you want to recive',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        required: false,
        description: 'The field used to get number of users do you want per page',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    #[Route('/users', name: 'api_get_users', methods: 'GET')]
    /**
     * This function retrieves a list of users from a repository, serializes it into JSON format, and
     * returns it as a JSON response.
     */
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

    #[OA\Response(
        response: 200,
        description: 'Returns an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['getUsers']))
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: false,
        description: 'The field used to get the user you want to recive',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    #[Route('/users/{id}', name: 'api_get_user', methods: 'GET')]
    /**
     * This function retrieves a user by their ID from a repository, serializes it into JSON format,
     * and caches the result using a cache service.
     */
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

    #[OA\Response(
        response: 204,
        description: 'Delete an user'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: false,
        description: 'The field used to get the user you want to delete',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    #[Route('/users/{id}', name: 'api_delete_user', methods: 'DELETE')]
    /**
     * The deleteUser function deletes a user from the database and invalidates the cache for products.
     * 
     */
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface, TagAwareCacheInterface $cache): JsonResponse
    {
        $user = $userRepository->findOneById($id,$this->getUser());

        if ($user) {
            $cache->invalidateTags(['UsersCache']);
            $entityManagerInterface->remove($user);
            $entityManagerInterface->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return throw new HttpException('404', "The ID doesn't exists");
    }
}
