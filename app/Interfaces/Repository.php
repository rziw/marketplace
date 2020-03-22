<?php
namespace App\Interfaces;

interface Repository
{
    public function get($id);
    public function list();
}
