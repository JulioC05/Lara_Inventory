<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'categoria' => 'Cervezas',
                'marca' => 'Cusqueña',
                'nombre' => 'Cusqueña Dorada 620ml',
                'descripcion' => 'Cerveza rubia premium',
                'contenido_ml' => 620,
                'graduacion_alcoholica' => 5.00,
                'codigo_barras' => '775000000001',
                'stock' => 250,
                'stock_minimo' => 20,
                'precio_compra' => 4.00,
                'margen_ganancia' => 30.00,
                'precio_venta' => 7.00,
            ],

            [
                'categoria' => 'Cervezas',
                'marca' => 'Corona',
                'nombre' => 'Corona Extra 355ml',
                'descripcion' => 'Cerveza lager importada',
                'contenido_ml' => 355,
                'graduacion_alcoholica' => 4.50,
                'codigo_barras' => '775000000002',
                'stock' => 280,
                'stock_minimo' => 15,
                'precio_compra' => 5.00,
                'margen_ganancia' => 35.00,
                'precio_venta' => 9.00,
            ],

            [
                'categoria' => 'Cervezas',
                'marca' => 'Heineken',
                'nombre' => 'Heineken Lata 473ml',
                'descripcion' => 'Cerveza lager europea',
                'contenido_ml' => 473,
                'graduacion_alcoholica' => 5.00,
                'codigo_barras' => '775000000003',
                'stock' => 280,
                'stock_minimo' => 15,
                'precio_compra' => 4.50,
                'margen_ganancia' => 35.00,
                'precio_venta' => 8.00,
            ],

            [
                'categoria' => 'Energizantes',
                'marca' => 'Red Bull',
                'nombre' => 'Red Bull Energy Drink 250ml',
                'descripcion' => 'Bebida energizante',
                'contenido_ml' => 250,
                'graduacion_alcoholica' => null,
                'codigo_barras' => '775000000004',
                'stock' => 250,
                'stock_minimo' => 10,
                'precio_compra' => 5.00,
                'margen_ganancia' => 40.00,
                'precio_venta' => 9.00,
            ],

            [
                'categoria' => 'Energizantes',
                'marca' => 'Monster',
                'nombre' => 'Monster Energy 473ml',
                'descripcion' => 'Bebida energizante importada',
                'contenido_ml' => 473,
                'graduacion_alcoholica' => null,
                'codigo_barras' => '775000000005',
                'stock' => 250,
                'stock_minimo' => 10,
                'precio_compra' => 7.00,
                'margen_ganancia' => 40.00,
                'precio_venta' => 12.00,
            ],

            [
                'categoria' => 'Whisky',
                'marca' => 'Johnnie Walker',
                'nombre' => 'Johnnie Walker Black Label 750ml',
                'descripcion' => 'Whisky escocés premium',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 40.00,
                'codigo_barras' => '775000000006',
                'stock' => 250,
                'stock_minimo' => 3,
                'precio_compra' => 95.00,
                'margen_ganancia' => 40.00,
                'precio_venta' => 140.00,
            ],

            [
                'categoria' => 'Whisky',
                'marca' => 'Jack Daniel\'s',
                'nombre' => 'Jack Daniel\'s Old No.7 750ml',
                'descripcion' => 'Whisky americano Tennessee',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 40.00,
                'codigo_barras' => '775000000007',
                'stock' => 260,
                'stock_minimo' => 3,
                'precio_compra' => 100.00,
                'margen_ganancia' => 40.00,
                'precio_venta' => 150.00,
            ],
            [
                'categoria' => 'Ron',
                'marca' => 'Havana Club',
                'nombre' => 'Havana Club Añejo 750ml',
                'descripcion' => 'Ron añejo cubano',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 37.50,
                'codigo_barras' => '775000000008',
                'stock' => 240,
                'stock_minimo' => 5,
                'precio_compra' => 45.00,
                'margen_ganancia' => 35.00,
                'precio_venta' => 70.00,
            ],

            [
                'categoria' => 'Ron',
                'marca' => 'Cartavio',
                'nombre' => 'Cartavio Black 750ml',
                'descripcion' => 'Ron peruano premium',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 40.00,
                'codigo_barras' => '775000000009',
                'stock' => 290,
                'stock_minimo' => 5,
                'precio_compra' => 35.00,
                'margen_ganancia' => 35.00,
                'precio_venta' => 55.00,
            ],

            [
                'categoria' => 'Ron',
                'marca' => 'Bacardí',
                'nombre' => 'Bacardí Carta Blanca 750ml',
                'descripcion' => 'Ron blanco clásico',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 37.50,
                'codigo_barras' => '775000000010',
                'stock' => 280,
                'stock_minimo' => 5,
                'precio_compra' => 40.00,
                'margen_ganancia' => 35.00,
                'precio_venta' => 62.00,
            ],
            [
                'categoria' => 'Vodka',
                'marca' => 'Absolut',
                'nombre' => 'Absolut Vodka 750ml',
                'descripcion' => 'Vodka sueco premium',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 40.00,
                'codigo_barras' => '775000000011',
                'stock' => 250,
                'stock_minimo' => 5,
                'precio_compra' => 55.00,
                'margen_ganancia' => 35.00,
                'precio_venta' => 85.00,
            ],

            [
                'categoria' => 'Vodka',
                'marca' => 'Smirnoff',
                'nombre' => 'Smirnoff Red 750ml',
                'descripcion' => 'Vodka clásico',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 37.50,
                'codigo_barras' => '775000000012',
                'stock' => 250,
                'stock_minimo' => 5,
                'precio_compra' => 38.00,
                'margen_ganancia' => 35.00,
                'precio_venta' => 60.00,
            ],
            [
                'categoria' => 'Vino Tinto',
                'marca' => 'Tacama',
                'nombre' => 'Tacama Gran Tinto 750ml',
                'descripcion' => 'Vino tinto peruano',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 13.50,
                'codigo_barras' => '775000000013',
                'stock' => 250,
                'stock_minimo' => 4,
                'precio_compra' => 28.00,
                'margen_ganancia' => 30.00,
                'precio_venta' => 42.00,
            ],

            [
                'categoria' => 'Vino Tinto',
                'marca' => 'Casillero del Diablo',
                'nombre' => 'Casillero del Diablo Cabernet Sauvignon 750ml',
                'descripcion' => 'Vino tinto chileno',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 13.50,
                'codigo_barras' => '775000000014',
                'stock' => 260,
                'stock_minimo' => 4,
                'precio_compra' => 35.00,
                'margen_ganancia' => 35.00,
                'precio_venta' => 55.00,
            ],
            [
                'categoria' => 'Vino Blanco',
                'marca' => 'Santiago Queirolo',
                'nombre' => 'Santiago Queirolo Blanco 750ml',
                'descripcion' => 'Vino blanco semiseco',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 12.50,
                'codigo_barras' => '775000000015',
                'stock' => 270,
                'stock_minimo' => 3,
                'precio_compra' => 30.00,
                'margen_ganancia' => 30.00,
                'precio_venta' => 46.00,
            ],
            [
                'categoria' => 'Vino Rosado',
                'marca' => 'Tabernero',
                'nombre' => 'Tabernero Rosado 750ml',
                'descripcion' => 'Vino rosado peruano',
                'contenido_ml' => 750,
                'graduacion_alcoholica' => 12.00,
                'codigo_barras' => '775000000016',
                'stock' => 290,
                'stock_minimo' => 3,
                'precio_compra' => 26.00,
                'margen_ganancia' => 30.00,
                'precio_venta' => 40.00,
            ],
            [
                'categoria' => 'Snacks',
                'marca' => 'Lays',
                'nombre' => 'Lays Clásicas 150g',
                'descripcion' => 'Papas fritas clásicas',
                'contenido_ml' => null,
                'graduacion_alcoholica' => null,
                'codigo_barras' => '775000000017',
                'stock' => 300,
                'stock_minimo' => 10,
                'precio_compra' => 4.00,
                'margen_ganancia' => 50.00,
                'precio_venta' => 8.00,
            ],

            [
                'categoria' => 'Snacks',
                'marca' => 'Pringles',
                'nombre' => 'Pringles Original 124g',
                'descripcion' => 'Papas crocantes',
                'contenido_ml' => null,
                'graduacion_alcoholica' => null,
                'codigo_barras' => '775000000018',
                'stock' => 280,
                'stock_minimo' => 8,
                'precio_compra' => 6.00,
                'margen_ganancia' => 45.00,
                'precio_venta' => 11.00,
            ],
        ];

        foreach ($productos as $producto) {

            $categoria = Categoria::where('nombre', $producto['categoria'])->first();

            $marca = Marca::where('nombre', $producto['marca'])->first();

            Producto::create([
                'categoria_id' => $categoria->id,
                'marca_id' => $marca->id,
                'user_id' => 1,

                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'contenido_ml' => $producto['contenido_ml'],
                'graduacion_alcoholica' => $producto['graduacion_alcoholica'],
                'codigo_barras' => $producto['codigo_barras'],

                'imagen' => 'productos/default-product.jpg',

                'stock' => $producto['stock'],
                'stock_minimo' => $producto['stock_minimo'],

                'precio_compra' => $producto['precio_compra'],
                'margen_ganancia' => $producto['margen_ganancia'],
                'precio_venta' => $producto['precio_venta'],

                'tipo_afectacion_igv' => 'gravado',
                'porcentaje_igv' => 18.00,
                'isc' => 0,

                'estado' => true,
            ]);
        }
    }
}
