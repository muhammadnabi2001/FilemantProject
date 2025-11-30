<?php

namespace App\Filament\Resources\Transports\Schemas;

use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('registration_number')
                    ->required(),
                TextInput::make('driver_name')
                    ->default(null),
                TextInput::make('latitude'),

                TextInput::make('longitude'),

                Section::make('Select Location')
                    ->collapsible()
                    ->schema([
                        Map::make('location')
                            ->label('Choose on map')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: 41.311081, longitude: 69.240562) // Tashkent
                            ->draggable(true)
                            ->clickable(true)
                            ->zoom(12)
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->showMarker(true)
                            ->markerColor('#3b82f6')
                            ->afterStateUpdated(function ($set, ?array $state) {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            })
                            ->afterStateHydrated(function ($state, $record, $set) {
                                $set('location', [
                                    'lat' => $record?->latitude ?? 41.311081,
                                    'lng' => $record?->longitude ?? 69.240562,
                                ]);
                            }),
                    ])->columnSpanFull(),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance'])
                    ->default('inactive')
                    ->required(),
                TextInput::make('capacity')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
