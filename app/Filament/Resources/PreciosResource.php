<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreciosResource\Pages;
use App\Filament\Resources\PreciosResource\RelationManagers;
use App\Models\Precio;
use App\Models\PrecioAgrupado;
use Filament\Forms;
use Filament\Forms\Form;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ButtonColumn;
use App\Filament\Resources\PreciosResource\Widgets\PriceHistoryChart;
use App\Exports\PreciosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


class PreciosResource extends Resource
{
    
    protected static ?string $model = PrecioAgrupado::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    public static function getLabel(): string
    {
        return 'Precio';
    }

    public static function getNavigationLabel(): string
    {
        return 'Precios';
    }


    public static function table(Table $table): Table
    {
        return $table->columns([
            
            TextColumn::make('ean')
                ->sortable()
                ->searchable(),
            TextColumn::make('nombre')
                ->sortable() 
                ->searchable()
                
                ->formatStateUsing(function ($state, $record) {
                $productNameSlug = strtolower(str_replace(' ', '-', $state));
                $productNameSlug = Str::slug($productNameSlug, '-'); 
                $url = "https://www.todofriki.com/producto/{$productNameSlug}";
                return '<a href="'. htmlspecialchars($url) .'" target="_blank" style="color: #1a73e8;">'. (strlen($state) > 40 ? substr($state, 0, 40) . '...' : $state) .'</a>';
                })
                ->html(),
            TextColumn::make('fecha_creacion')
                ->label('Fecha')
                ->date('d-m-Y')
                ->sortable() 
                ->searchable(),
            TextColumn::make('precios_concatenados')
                ->html()
                ->label('Precio registro')
                ->tooltip('El precio de Todofriki se muestra en verde si es el más bajo, y en rojo si cualquier otro precio es más bajo.')
                ->formatStateUsing(function ($state) {
                    $lines = explode(', ', $state);
                    $prices = collect($lines)->mapWithKeys(function ($line) {
                        list($store, $price) = explode(': ', $line);
                        return [$store => floatval(trim($price))];
                    });
            
                    $todofrikiPrice = $prices->get('Todofriki', 0); // Obtener el precio de Todofriki, o 0 si no está definido
                    $otherPrices = $prices->except(['Todofriki']); // Excluir a Todofriki para calcular los precios de otras tiendas
                    
                    $minPrice = $otherPrices->min(); // El precio mínimo de las otras tiendas
            
                    $html = $prices->map(function ($price, $store) use ($todofrikiPrice, $minPrice) {
                        if ($store === 'Todofriki') {
                            // Cambiar el color basado en la comparación con otros precios
                            $color = ($todofrikiPrice <= $minPrice) ? 'green' : 'red';
                        } else {
                            $color = 'black'; // Los precios de otras tiendas siempre en negro
                        }
                        return "<div style='margin-bottom: 4px; color: $color;'>"
                               . "<span style='font-weight: bold;'>$store:</span> "
                               . "<span>$price €</span>"
                               . "</div>";
                    })->join('');
            
                    return $html;
                }),
            TextColumn::make('last_price_modification')
                ->label('Última Modificación')
                ->html(),
            
        ])
        ->defaultSort('fecha_creacion', 'desc')
        ->filters([
                Filter::make('Desde')
                ->form([
                    DatePicker::make('desde')
                        ->label('Desde la fecha')
                        ->default(Carbon::today())
                        ->placeholder('DD-MM-YYYY')
                        ->reactive(),
                ])
                ->query(function (Builder $query, array $data) {
                    if (isset($data['desde'])) {
                        $query->whereDate('fecha_creacion', '>=', $data['desde']?? Carbon::today()->toDateString());
                    }
                })
                ->indicateUsing(function ($data) {
                    if (!empty($data['desde'])) {
                        return 'Desde: ' . $data['desde'];
                    }
                    return null;
                }),
            
            Filter::make('Hasta')
                ->form([
                    DatePicker::make('hasta')
                        ->label('Hasta la fecha')
                        ->placeholder('DD-MM-YYYY')
                        ->reactive(),
                ])
                ->query(function (Builder $query, array $data) {
                    if (isset($data['hasta'])) {
                        $query->whereDate('fecha_creacion', '<=', $data['hasta']);
                    }
                })
                ->indicateUsing(function ($data) {
                    if (!empty($data['hasta'])) {
                        return 'Hasta: ' . $data['hasta'];
                    }
                    return null;
                }),
            ])
            ->actions([
                Action::make('viewChart')
                    ->label('Ver gráfica')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn ($record): string => route('chart.show', ['ean' => $record->ean]))
                    ->button()
                    
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Exportar Excel')
                    ->action(function () {
                        return Excel::download(new PreciosExport, 'precios.xlsx');
                    })
                    ->icon('heroicon-o-arrow-down-tray')
            ]);                      
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrecios::route('/'),
            #'create' => Pages\CreatePrecios::route('/create'),
            #'edit' => Pages\EditPrecios::route('/{record}/edit'),
        ];
    } 
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Quitar botón de creación
    }
    
}

