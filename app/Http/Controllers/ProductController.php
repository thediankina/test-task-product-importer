<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Контроллер для управления товарами
 */
class ProductController extends Controller
{
    /**
     * Выполнить импорт товаров
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function import(Request $request): \Illuminate\Contracts\View\View
    {
        //$user = Session::get('user_id');
        $user = 2;
        $products = [];
        $attributes = [
            'id',
            'name',
            'code',
            'price',
            'preview_text',
            'detail_text',
        ];

        if ($request->hasFile('upload')) {
            $upload = $request->file('upload');
            $filename = $upload->getClientOriginalName();
            $storage = $upload->storeAs('uploads', Session::get('user_id') . $filename);
            $file = fopen(storage_path('app/' . $storage), "r");
            $data = [];
            while (!feof($file)) {
                $values = fgetcsv($file, 0, ";");
                $data[] = $values;
            }
            fclose($file);
            for ($index = 1; $index < count($data)-1; $index++) {
                $products[$index-1] = array_combine($attributes, $data[$index]);
            }
            $created = 0;
            $updated = 0;
            foreach ($products as $product) {
                $parts = array_map('intval', explode(",", $product['price']));
                $result = Product::query()->updateOrCreate(
                    [
                        'id' => $product['id'],
                        'user_id' => $user,
                    ], [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'code' => $product['code'],
                        'price' => $parts[0] + floatval($parts[1] / 100),
                        'preview_text' => $product['preview_text'],
                        'detail_text' => $product['detail_text'],
                        'user_id' => $user,
                    ]
                );
                if ($result->wasRecentlyCreated) {
                    $created++;
                }
                if (!empty($result->getChanges())) {
                    $updated++;
                }
            }
            return View::make('result', ['created' => $created, 'updated' => $updated]);
        } else {
            abort(503);
        }
    }
}
