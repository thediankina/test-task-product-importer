<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use \Illuminate\Contracts\View\View as ViewComponent;
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
     * @return ViewComponent|void
     * @throws Exception
     */
    public function import(Request $request)
    {
        User::authorized();
        $user = Session::get('user_id');

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
            if ($request->file('upload')->getClientOriginalExtension() != "csv") {
                abort(503);
            }
            $upload = $request->file('upload');
            $filename = $upload->getClientOriginalName();

            // Хранить CSV файл с именем <user_id>_<filename>
            $storage = $upload->storeAs('uploads', Session::get('user_id') . '_' . $filename);
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
                if (strlen($product['preview_text']) > 30) {
                    $product['preview_text'] = substr($product['detail_text'], 0, 30);
                }
                $parts = array_map('intval', explode(",", $product['price']));
                $result = Product::query()->updateOrCreate(
                    ['id' => $product['id'], 'user_id' => $user],
                    [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'code' => $product['code'],
                        'price' => $parts[0] + floatval($parts[1] / 100),
                        'preview_text' => strip_tags($product['preview_text']),
                        'detail_text' => $product['detail_text'],
                        'user_id' => $user,
                    ]
                );
                // Если запись только что создана
                if ($result->wasRecentlyCreated) {
                    $created++;
                }
                // Если зафиксированы изменения
                if (!empty($result->getChanges())) {
                    $updated++;
                }
            }

            // Вернуть результат импорта
            return View::make('result', [
                'created' => $created,
                'updated' => $updated,
                ]);

        } else {
            abort(503);
        }
    }
}
