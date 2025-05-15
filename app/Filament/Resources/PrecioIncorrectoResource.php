<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrecioIncorrectoResource\Pages;
use App\Filament\Resources\PrecioIncorrectoResource\RelationManagers;
use App\Models\PrecioIncorrecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class PrecioIncorrectoResource extends Resource
{
    protected static ?string $model = PrecioIncorrecto::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function getLabel(): string
    {
        return 'Precios Incorrectos';
    }

    public static function getNavigationLabel(): string
    {
        $recordCount = PrecioIncorrecto::count();
        $titulo = 'Precio Por Debajo';
        
        if ($recordCount > 0) {
            $titulo = 'Precio Por Debajo ' . strval($recordCount);
        }
        
        return $titulo;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table    {        
        return $table
            ->columns([
                TextColumn::make('ean')->label('EAN'),
                TextColumn::make('nombre')->label('Nombre'),
                TextColumn::make('precioTodofriki')->label('Precio Todofriki'),
                TextColumn::make('precioBase')->label('Precio Base'),
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
            'index' => Pages\ListPrecioIncorrectos::route('/'),
            #'create' => Pages\CreatePrecioIncorrecto::route('/create'),
            #'edit' => Pages\EditPrecioIncorrecto::route('/{record}/edit'),
        ];
    }    

    
}
