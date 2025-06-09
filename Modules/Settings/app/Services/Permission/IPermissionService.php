<?php

namespace Modules\Settings\Services\Permission;

interface IPermissionService
{
    public function list();

    public function update(int $id, array $data): array;

    public function get(int $id): array;
}
