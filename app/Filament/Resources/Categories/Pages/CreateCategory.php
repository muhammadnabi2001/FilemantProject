<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Yangi category yaratildi')
            ->body('Category: ' . $this->record->id)
            ->success()
            ->sendToDatabase(Auth::user())
            ;
    }
}
