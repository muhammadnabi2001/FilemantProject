<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Exports\PostExporter;
use App\Filament\Resources\Posts\PostResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListPosts extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(PostExporter::class)
                ->color('primary'),
            Action::make('deleteExport')
                ->label('Delete All Exports')
                ->action(function () {
                    Storage::disk('local')->deleteDirectory('filament_exports');
                    Storage::disk('local')->makeDirectory('filament_exports');
                })
                ->requiresConfirmation()
        ];
    }
}
