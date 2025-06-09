<?php

namespace Modules\Settings\Services\Role;
use Spatie\Permission\Models\Role;

interface IRoleService
{
    public function list();
    public function create(array $data): Role;

    public function update(int $id, array $data): array;

    public function delete(int $id): array;

    public function get(int $id): array;
}
