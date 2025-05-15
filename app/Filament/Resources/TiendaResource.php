<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiendaResource\Pages;
use App\Filament\Resources\TiendaResource\RelationManagers;
use App\Models\Tienda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn; 
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;


class TiendaResource extends Resource
{
    protected static ?string $model = Tienda::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->required(),
            Forms\Components\TextInput::make('URL')
                ->url()
                ->label('URL'),
            Forms\Components\TextInput::make('nombrePrecio')
                ->label('Nombre del campo Precio'),
        ]);
    }
    public static function table(Table $table): Table
{
    return $table->columns([
        TextColumn::make('nombre')
            ->label('Nombre')
            ->tooltip('Será el nombre que aparece en la categoría Precios.')
            ->sortable()
            ->searchable()
            ->formatStateUsing(fn ($state) => '<strong>' . e($state) . '</strong>')
            ->html(), 

        TextColumn::make('URL')
            ->label('URL')
            ->tooltip('Debes introducir la dirección de la web cuando busca un producto. Termina siempre en = ')
            ->sortable()
            ->searchable()
            ->limit(40) 
            ->formatStateUsing(fn ($state) => strlen($state) > 40 ? substr($state, 0, 40) . '...' : $state),

        TextColumn::make('nombrePrecio')
            ->label('Nombre del campo Precio')
            ->tooltip('Debes de inspeccionar la web para introducir el nombre del campo que contiene el precio.')
            ->sortable()
            ->searchable(),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Editar Tienda')
                    ->modalWidth('lg'), // Puedes personalizar el ancho del modal si es necesario
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
            ])
            ->defaultSort('nombre', 'asc');
}
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTiendas::route('/'),
            'create' => Pages\CreateTienda::route('/create'),
            'edit' => Pages\EditTienda::route('/{record}/edit'),
        ];
    }    
}
