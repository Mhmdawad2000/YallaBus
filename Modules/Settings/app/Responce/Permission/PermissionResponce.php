<?php

namespace Modules\Settings\Responce\Permission;

class PermissionResponce
{

    public array $data = [];


    public function __construct(array $array)
    {
        $i = 0;
        foreach ($array as $item) {
            $this->data[$i]['id'] = $item->id;
            $this->data[$i]['name'] = $item->name;
            $this->data[$i]['changeable_name'] = $item->changeable_name;
            $i++;
        }
    }
}

