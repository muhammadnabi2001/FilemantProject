<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->native(false)
                    ->required(),
                TextInput::make('user_id')
                    ->default(Auth::id())
                    ->required()
                    ->readOnly()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('excerpt')
                    ->default(null),
                MarkdownEditor::make('body')
                    ->toolbarButtons([
                        ['bold', 'italic', 'strike', 'link'],
                        ['heading'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['table',],
                        ['undo', 'redo'],
                    ])
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->multiple()
                    ->disk('public')
                    ->directory('form-attachments')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                        return Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                            . '-'
                            . uniqid()
                            . '.'
                            . $file->getClientOriginalExtension();
                    })
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->imageEditorEmptyFillColor('#15d832ff')
                    ->reorderable()
                    ->openable()
                    ->downloadable()
                    ->minSize(512)
                    ->maxSize(30730)
                    ->minFiles(1)
                    ->maxFiles(10)
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
