<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ProductoController extends Controller
{

    public function index()
    {   // funcion para listar los datos de la tabla categoria verificando que los datos esten activos (existan) 
        try{
            $productos = Producto:: with('categoria')->where('estado', 'A')->get();
            return response()->json([
                'sucess' => true,
                'message' =>'listado de productos',
                'data'=> $productos
            ], 200);
        }  catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los productos',
                'data' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    
    {
        try {
            //Validamos los datos de entrada
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:150',
                'categoria_id' => 'required|exists:t_categorias,id',
                'stock' => 'required|integer|min:0',
                'precio' => 'required|numeric|min:0',
                'estado' => ['required', Rule::in(['A','I'])]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al obtener los Productos',
                    'data' => $validator->errors()
                ], 422);
            }

            $producto = Producto::create($request->all());
            $producto->load('categoria');

            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear producto'
            ], 500);
        }
    }
       
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try { 
            $producto = Producto::with('categoria')->find($id);
            if (!$producto) {
                return response()->json([
                 'success' => false,
                 'message' => 'producto no encontrado'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'detalle del producto',
                'data' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener producto'
            ], 500);
        }    
    
    }   
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:255',
                'categoria_id' => 'sometimes|required|exists:t_categorias,id',
                'stock' => 'sometimes|required|integer|min:0',
                'precio' => 'sometimes|required|numeric|min:0',
                'estado' => ['sometimes', 'required', Rule::in(['A', 'I'])]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación'
                ], 422);
            }

            $producto->update($request->all());
            $producto->load('categoria');

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar producto'
            ], 500);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            // Cambiar estado a Inactivo en lugar de eliminar
            $producto->update(['estado' => 'I']);

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente (estado inactivo)'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar producto'
            ], 500);
        }
    }

    // -- Inicio Juan_Perez ---//

    // Paginacion Simple 
    public function paginarSimple($cantidad)
    {
        try {
            $productos = Producto::with('categoria')
                ->where('estado', 'A')
                ->paginate($cantidad);

            return response()->json([
                'success' => true,
                'message' => 'Productos paginados',
                'data' => $productos->items(),
                'pagination' => [
                    'total' => $productos->total(),
                    'per_page' => $productos->perPage(),
                    'current_page' => $productos->currentPage(),
                    'last_page' => $productos->lastPage(),
                    'from' => $productos->firstItem(),
                    'to' => $productos->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la paginación'
            ], 500);
        }
    }

    // Paginacion avanzada
    public function paginarAvanzada($cantidad, $pagina)
    {
        try {
            $productos = Producto::with('categoria')
                ->where('estado', 'A')
                ->paginate($cantidad, ['*'], 'page', $pagina);

            return response()->json([
                'success' => true,
                'message' => "Mostrando página {$pagina} con {$cantidad} productos",
                'data' => $productos->items(),
                'pagination' => [
                    'total' => $productos->total(),
                    'per_page' => $productos->perPage(),
                    'current_page' => $productos->currentPage(),
                    'last_page' => $productos->lastPage(),
                    'from' => $productos->firstItem(),    
                    'to' => $productos->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la paginación'
            ], 500);
        }
    }

    // --- Final Juan_Perez ----

    public function actualizarStock(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cantidad' => 'required|integer|not_in:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación'
                ], 422);
            }

            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $cantidad = $request->cantidad;
            $stockActual = $producto->stock;
            $operacion = '';

            // Si la cantidad es MENOR que el stock actual, RESTAMOS
            if ($cantidad < $stockActual) {
                $producto->stock = $stockActual - $cantidad;
                $operacion = 'resta';
            } 
            // Si la cantidad es MAYOR O IGUAL al stock actual, SUMAMOS
            else {
                $producto->stock = $stockActual + $cantidad;
                $operacion = 'suma';
            }

            $producto->save();
            $producto->load('categoria');

            return response()->json([
                'success' => true,
                'message' => "Stock actualizado mediante {$operacion}",
                'data' => [
                    'producto' => $producto,
                    'stock_anterior' => $stockActual,
                    'stock_nuevo' => $producto->stock,
                    'cantidad_operada' => $cantidad,
                    'operacion_realizada' => $operacion
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar stock'
            ], 500);
        }
    }
}
