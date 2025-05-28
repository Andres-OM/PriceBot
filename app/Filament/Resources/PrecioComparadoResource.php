<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrecioComparadoResource\Pages;
use App\Filament\Resources\PrecioComparadoResource\RelationManagers;
use App\Models\PrecioComparado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;




class PrecioComparadoResource extends Resource
{
    protected static ?string $model = PrecioComparado::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-up-down';

    public static function getLabel(): string
    {
        return 'Ajustar Precios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Ajustar Precios';
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ean')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nombre')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $shortenedName = strlen($state) > 30 ? substr($state, 0, 30) . '...' : $state;
                        return $shortenedName;
                    }),
                TextColumn::make('precioPricebot')
                    ->label('Precio Pricebot')
                    ->sortable(),
                TextColumn::make('precioMasBajo')
                    ->sortable(),
                TextColumn::make('tienda')
                    ->sortable(),
                TextColumn::make('precioBase')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        return $record->precioMasBajo > $record->precioBase
                            ? "<span style='font-weight: bold;'>{$state}</span>"
                            : $state;
                    })
                    ->html(),    
            ])
            ->actions([
                Action::make('ajustarPrecioIndividual')
                    ->label('Ajustar Precio')
                    ->button()
                    ->tooltip('El precio de Pricebot cambia al precio más bajo comparado en caso de ser superior al precio de base.') 
                    ->action(function ($record, $data) {
                        $precioMasBajo = $record->precioMasBajo;
                        $ean = $record->ean;
            
                        // Actualizar el precio en la tabla wpfrk_postmeta
                        $updatedMeta = DB::table('wpfrk_postmeta')
                            ->join('wpfrk_postmeta as meta_sku', function($join) use ($ean) {
                                $join->on('wpfrk_postmeta.post_id', '=', 'meta_sku.post_id')
                                    ->where('meta_sku.meta_key', '=', '_sku');
                            })
                            ->where('wpfrk_postmeta.meta_key', '=', '_price')
                            ->where('meta_sku.meta_value', '=', $ean)
                            ->update(['wpfrk_postmeta.meta_value' => $precioMasBajo]);
            
                        // Actualizar el precio en la tabla precios para la tienda Pricebot
                        $updatedPrecios = DB::table('precios')
                            ->where('ean', $ean)
                            ->where('tienda', 'Pricebot')
                            ->whereDate('fecha_creacion', today())
                            ->update(['precio' => $precioMasBajo]);
            
                        if ($updatedMeta && $updatedPrecios) {
                            Notification::make()
                                ->title('Precio modificado de forma exitosa.')
                                ->success()
                                ->body('El precio ha sido actualizado correctamente.')
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Error al modificar el precio.')
                                ->danger()
                                ->body('No se pudo actualizar el precio.')
                                ->send();
                        }
                    })
                    ->visible(fn ($record) => $record->precioMasBajo > $record->precioBase),
            ])
            ->headerActions([
                Action::make('ajustarPreciosConjunto')
                    ->label('Ajustar todos los precios')
                    ->tooltip('Los precios de Pricebot cambian al precio más bajo comparado en caso de ser superior al precio de base.')
                    ->action(function () {
                        $records = PrecioComparado::whereColumn('precioMasBajo', '>', 'precioBase')
                                                    ->get();
            
                        foreach ($records as $record) {
                            $precioMasBajo = $record->precioMasBajo;
                            $ean = $record->ean;
            
                            // Actualiza el precio en wpfrk_postmeta
                            DB::table('wpfrk_postmeta')
                                ->join('wpfrk_postmeta as meta_sku', function($join) {
                                    $join->on('wpfrk_postmeta.post_id', '=', 'meta_sku.post_id')
                                         ->where('meta_sku.meta_key', '=', '_sku');
                                })
                                ->where('wpfrk_postmeta.meta_key', '=', '_price')
                                ->where('meta_sku.meta_value', '=', $ean)
                                ->update(['wpfrk_postmeta.meta_value' => $precioMasBajo]);
            
                            // Actualiza el precio en la tabla precios para la tienda Pricebot con fecha de creación de hoy
                            DB::table('precios')
                                ->where('ean', $ean)
                                ->where('tienda', 'Pricebot')
                                ->whereDate('fecha_creacion', today())
                                ->update(['precio' => $precioMasBajo]);
                        }
            
                        // Notificación de éxito
                        Notification::make()
                            ->title('Todos los precios han sido ajustados correctamente.')
                            ->success()
                            ->send();
                    })
            ]);
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrecioComparados::route('/'),
            #'create' => Pages\CreatePrecioComparado::route('/create'),
            #'edit' => Pages\EditPrecioComparado::route('/{record}/edit'),
        ];
    }    
}
