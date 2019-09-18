<?php


namespace App\Repositories;


abstract class CoreRepository
{
    protected function makeTree(array $data, $key, int $parent = 0)
    {
        $array = [];

        foreach ($data as $item) {
            if ($item['parent_id'] == $parent) {
                $children = $this->makeTree($data, $key, $item['id']);
                if ($children) {
                    $item[$key] = $children;
                }

                $array[] = $item;
            }
        }

        return $array;
    }
}
