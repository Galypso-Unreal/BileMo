<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FetchLinks
{

    public function __construct(
        private UrlGeneratorInterface $router,
    ) {
    }

    public function generateLinks(string $name, $id): array
    {
        try {
            $self = $this->router->generate("api_" . $name, ['id' => $id]);
        } catch (\Throwable $th) {
        }

        try {
            $create = $this->router->generate("api_create_" . $name);
        } catch (\Throwable $th) {
        }

        try {
            $update = $this->router->generate("api_update_" . $name, ['id' => $id]);
        } catch (\Throwable $th) {
        }

        try {
            $delete = $this->router->generate("api_delete_" . $name, ['id' => $id]);
        } catch (\Throwable $th) {
        }






        $array = [
            "_links" => []
        ];

        if (isset($self)) {
            $array["_links"]['self'] = $self;
        }
        if (isset($create)) {
            $array["_links"]['create'] = $create;
        }
        if (isset($update)) {
            $array["_links"]['update'] = $update;
        }
        if (isset($delete)) {
            $array["_links"]['delete'] = $delete;
        }


        return $array;
    }

    public function generatePaginationLinks($name, $limit, $page, $repository): array
    {

        try {

            $page_prev = $page - 1;
            if ($page_prev > 0) {
                $previous_page = $this->router->generate("api_" . $name, ["limit" => $limit, "page" => $page_prev]);
            }
        } catch (\Throwable $th) {
        }

        try {
            $next_page = $this->router->generate("api_" . $name, ["limit" => $limit, "page" => $page + 1]);
        } catch (\Throwable $th) {
        }

        $array = [
            "_links" => []
        ];


        if (isset($previous_page)) {

            $array["_links"]['previous_page'] = $previous_page;
        }

        if (isset($next_page) && !empty($repository)) {
            $array["_links"]['next_page'] = $next_page;
        }


        return $array;
    }

    public function merge(array $array1, array $array2, string $name = null)
    {

        if (isset($name)) {
            $array = array(
                $name => $array1,
            );

            return $array + $array2;
        } else {

            return $array1 + $array2;
        }
    }
}
