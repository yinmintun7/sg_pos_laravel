<?php

namespace App\Repository\User;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function getUsers();
    public function getUserById(int $id);
    public function update($user);
    public function delete(int $id);
}
