<?php

namespace App\Enums;

enum ModelStatus: string
{
    case WaitingForCreate = 'waiting-for-create';
    case WaitingForUpdate = 'waiting-for-update';
    case WaitingForDelete = 'waiting-for-delete';

    public function label()
    {
        return match($this) {
            ModelStatus::WaitingForCreate => 'Waiting for creation',
            ModelStatus::WaitingForUpdate => 'Waiting for update',
            ModelStatus::WaitingForDelete => 'Waiting for deletion',
        };
    }
}