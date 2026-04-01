<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Support\RichEditorAttachments;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function bundleMathSummary(callable $get): string
    {
        if (($get('catalog_type') ?? Product::CATALOG_SIMPLE) !== Product::CATALOG_BUNDLE) {
            return '—';
        }

        $lines = $get('bundle_items') ?? [];
        $original = 0;

        foreach ($lines as $row) {
            $pid = $row['component_product_id'] ?? null;
            if (! $pid) {
                continue;
            }
            $component = Product::query()->find($pid);
            if (! $component) {
                continue;
            }
            $original += Product::componentSellingPriceMinor($component) * max(1, (int) ($row['quantity'] ?? 1));
        }

        $sell = (int) round((float) ($get('price') ?? 0));

        if ($original <= 0) {
            return 'Add included products to see “if bought separately” total, savings, and percent off.';
        }

        $savings = max(0, $original - $sell);
        $pct = $original > 0 ? round(100 * $savings / $original, 1) : 0.0;

        return 'Original total (if bought separately): ₦'.number_format($original, 2)
            .' · Savings vs bundle price: ₦'.number_format($savings, 2).' ('.$pct.'%)'
            .' · Your bundle price: ₦'.number_format($sell, 2);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Core details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug($state ?? ''))),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                TextInput::make('sku')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Select::make('catalog_type')
                                    ->label('Catalog type')
                                    ->options(Product::catalogTypes())
                                    ->default(Product::CATALOG_SIMPLE)
                                    ->required()
                                    ->live(),
                            ]),
                        RichEditorAttachments::configure(
                            RichEditor::make('description')
                                ->columnSpanFull(),
                            'rich-content/products',
                            RichEditorAttachments::richToolbar(),
                        ),
                        TextInput::make('short_description')
                            ->label('Short description')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        TextInput::make('category')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Pricing & stock')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('₦')
                                    ->live(onBlur: true),
                                TextInput::make('sale_price')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('₦'),
                                TextInput::make('stock_quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(fn (callable $get) => ($get('catalog_type') ?? Product::CATALOG_SIMPLE) === Product::CATALOG_BUNDLE
                                        ? 'Bundles often use 0. Sellable quantity is derived from included products unless “Allow partial stock” is on.'
                                        : null),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Bundle composition')
                    ->description('Fixed kit: set the bundle price above. Original total and savings update from the lines below.')
                    ->schema([
                        Repeater::make('bundle_items')
                            ->label('Included products')
                            ->schema([
                                Select::make('component_product_id')
                                    ->label('Product')
                                    ->options(fn () => Product::query()
                                        ->where(function ($q) {
                                            $q->where('catalog_type', Product::CATALOG_SIMPLE)
                                                ->orWhereNull('catalog_type');
                                        })
                                        ->orderBy('name')
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live(onBlur: true),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add product')
                            ->reorderable(false)
                            ->live(onBlur: true),
                        Toggle::make('allow_partial_stock')
                            ->label('Allow partial stock')
                            ->helperText('If off, the bundle is unavailable when any line item lacks enough stock.')
                            ->default(false),
                        Placeholder::make('bundle_math')
                            ->label('Comparison & savings')
                            ->content(fn (callable $get) => self::bundleMathSummary($get))
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (callable $get) => ($get('catalog_type') ?? Product::CATALOG_SIMPLE) === Product::CATALOG_BUNDLE)
                    ->collapsed(false),

                Section::make('Main image')
                    ->schema([
                        FileUpload::make('image_url')
                            ->label('Product image')
                            ->image()
                            ->directory('product-images')
                            ->columnSpanFull(),
                    ]),

                Section::make('Puppy-specific attributes')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('age_min_weeks')
                                    ->label('Min age (weeks)')
                                    ->numeric()
                                    ->minValue(0),
                                TextInput::make('age_max_weeks')
                                    ->label('Max age (weeks)')
                                    ->numeric()
                                    ->minValue(0),
                                Select::make('breed_size')
                                    ->options(Product::breedSizes())
                                    ->placeholder('Select size'),
                                Select::make('product_type')
                                    ->options(Product::productTypes())
                                    ->placeholder('Select type'),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }
}
