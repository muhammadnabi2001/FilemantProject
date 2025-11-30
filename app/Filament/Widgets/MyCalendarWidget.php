<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Guava\Calendar\Enums\CalendarViewType;
// use Filament\Widgets\Widget;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;

class MyCalendarWidget extends CalendarWidget
{
    // protected CalendarViewType $calendarView = CalendarViewType::ResourceTimeGridWeek;
    protected function getEvents(FetchInfo $info): Collection|array
    {
        return [
            CalendarEvent::make()
                ->title('My first calendar')
                ->start(today())
                ->end(today()),
        ];
    }
}
