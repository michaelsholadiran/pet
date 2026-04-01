<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Settings extends Page implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Store Settings';

    protected static ?string $title = 'Store Settings';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'payment_gateway' => Setting::get('payment', 'gateway', 'stripe'),
            'tax_rate' => Setting::get('tax', 'rate', '0'),
            'logo_url' => Setting::get('branding', 'logo_url', ''),
            'primary_color' => Setting::get('branding', 'primary_color', '#f59e0b'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment')
                    ->schema([
                        Select::make('payment_gateway')
                            ->options([
                                'stripe' => 'Stripe',
                                'paystack' => 'Paystack',
                                'flutterwave' => 'Flutterwave',
                            ])
                            ->required(),
                    ]),
                Section::make('Tax')
                    ->schema([
                        TextInput::make('tax_rate')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                    ]),
                Section::make('Branding')
                    ->schema([
                        TextInput::make('logo_url')
                            ->url()
                            ->placeholder('https://...'),
                        TextInput::make('primary_color')
                            ->label('Primary color (hex)'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        Setting::set('payment', 'gateway', $data['payment_gateway'] ?? '');
        Setting::set('tax', 'rate', $data['tax_rate'] ?? '0');
        Setting::set('branding', 'logo_url', $data['logo_url'] ?? '');
        Setting::set('branding', 'primary_color', $data['primary_color'] ?? '#f59e0b');
        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
