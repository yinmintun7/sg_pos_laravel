<?php

namespace App\Repository\Setting;

interface SettingRepositoryInterface
{
    public function create(array $data);
    public function getSetting();
    public function update($category);
    public function delete(int $id);
}
