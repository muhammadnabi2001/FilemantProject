<?php

namespace App\Filament\Widgets;

use App\Models\MyEvent;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\Actions\DeleteAction;
use Guava\Calendar\Filament\Actions\EditAction;
use Guava\Calendar\Filament\Actions\ViewAction;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MyCalendarWidget extends CalendarWidget
{
    // protected CalendarViewType $calendarView = CalendarViewType::ListWeek;

    // protected CalendarViewType $calendarView = CalendarViewType::ResourceTimeGridWeek;

    // protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected bool $dayMaxEvents = true;

    protected bool $dateClickEnabled = true;
    protected bool $eventClickEnabled = true;

    protected bool $dateSelectEnabled = true;

    protected function getEventClickContextMenuActions(): array
    {
        return [
            $this->viewAction(),
            $this->editAction(),
            $this->deleteAction(),
        ];
    }

    protected function getDateClickContextMenuActions(): array
    {
        return [
            CreateAction::make('create')
                ->model(MyEvent::class)
                ->mountUsing(fn($arguments, $form) => $form->fill([
                    'title' => null,
                    'start' => data_get($arguments, 'dateStr'),
                    'end'   => data_get($arguments, 'dateStr'),
                ]))
                ->schema([
                    TextInput::make('title')
                        ->required(),
                    DateTimePicker::make('start')
                        ->required(),
                    DateTimePicker::make('end')
                        ->required(),
                ])
                ->after(fn() => $this->refreshRecords())
        ];
    }
    
    public function editAction(): EditAction
    {
        return EditAction::make('edit')
            ->modal(true)

            ->record(function ($arguments) {
                $key = data_get($arguments, 'data.event.extendedProps.key');
                $model = data_get($arguments, 'data.event.extendedProps.model');

                if ($key && $model && class_exists($model)) {
                    return $model::find($key);
                }

                $id = data_get($arguments, 'data.event.id');
                if (is_string($id) && preg_match('/\{generated-(\d+)\}/', $id, $m)) {
                    $id = $m[1];
                }

                if ($id) {
                    return MyEvent::find($id);
                }

                throw new \RuntimeException('Event record not found for EDIT');
            })

            ->schema([
                TextInput::make('title')->required(),
                DateTimePicker::make('start')->required(),
                DateTimePicker::make('end')->required(),
            ])

            ->after(fn() => $this->refreshRecords());
    }
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
                ->action(function (array $data) {
                    MyEvent::create([
                        'title' => $data['title'],
                        'start' => $data['start'],
                        'end' => $data['end'],
                    ]);
                    $this->refreshRecords();
                })
        ];
    }
    public function deleteAction(): DeleteAction
    {
        return parent::deleteAction();
    }
    protected function getEvents(FetchInfo $info): Collection|array
    {
        return MyEvent::get();
    }
    public function viewAction(): ViewAction
    {
        return ViewAction::make()
            ->label('View')
            ->modal(true)
            ->record(function ($arguments) {
                $key = data_get($arguments, 'data.event.extendedProps.key');
                $model = data_get($arguments, 'data.event.extendedProps.model');

                if ($key && $model && class_exists($model)) {
                    return $model::find($key);
                }

                $id = data_get($arguments, 'data.event.id');
                if (is_string($id) && preg_match('/\{generated-(\d+)\}/', $id, $m)) {
                    $id = $m[1]; // "3"
                }
                if ($id) {
                    return MyEvent::find($id);
                }
                throw new \RuntimeException('Event record not found');
            })
            ->schema([
                TextInput::make('title')->required(),
                DateTimePicker::make('start')->required(),
                DateTimePicker::make('end')->required(),
            ])
            ->after(fn() => $this->refreshRecords());
    }
    public function authorize($ability, $arguments = [])
    {
        return true;
    }
}
