<?php
namespace App\Interfaces;

interface Repository
{
    public function findByUser(int $orderId, int $userId);
    public function list();
}
