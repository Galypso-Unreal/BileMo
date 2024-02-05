<?php 

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FetchLinks
{

    public function __construct(
        private UrlGeneratorInterface $router,
    ) {
    }

    public function generateLinks (string $name, $id): array
    {
        try {
            $self = $this->router->generate("api_".$name,['id'=>$id]);
        } catch (\Throwable $th) {
            
        }

        try {
            $create = $this->router->generate("api_create_".$name);
        } catch (\Throwable $th) {
            
        }

        try {
            $update = $this->router->generate("api_update_".$name,['id'=>$id]);
        } catch (\Throwable $th) {
            
        }

        try {
            $delete = $this->router->generate("api_delete_".$name,['id'=>$id]);
        } catch (\Throwable $th) {
            
        }
        
        
        
        
        

        $array = [
            "_links"=> []
        ];

        if(isset($self)){
            $array["_links"]['self'] = $self;
        }
        if(isset($create)){
            $array["_links"]['create'] = $create;
        }
        if(isset($update)){
            $array["_links"]['update'] = $update;
        }
        if(isset($delete)){
            $array["_links"]['delete'] = $delete;
        }
        

        return $array;
    }

    public function merge($array1, $array2){

        return $array1 + $array2;

    }
}