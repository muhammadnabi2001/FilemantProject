<?php

namespace App\Filament\Widgets;

use App\Models\MyEvent;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
// use Filament\Widgets\Widget;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;

class MyCalendarWidget extends CalendarWidget
{
    // protected CalendarViewType $calendarView = CalendarViewType::ResourceTimeGridWeek;
    public function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Create Event')
            ->modal(true)
            ->model(MyEvent::class)
            ->schema([
                TextInput::make('title')
                ->required(),
                DateTimePicker::make('start')
                ->required(),
                DateTimePicker::make('end')
                ->required(),
            ])
            ->action(function(array $data){
                MyEvent::create([
                    'title'=>$data['title'],
                    'start'=>$data['start'],
                    'end'=>$data['end'],
                ]);
                $this->refreshRecords();
            })
        ];
    }
    protected function getEvents(FetchInfo $info): Collection|array
    {
        return MyEvent::get();
    }
}
