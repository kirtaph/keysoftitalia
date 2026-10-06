<?php
declare(strict_types=1);

namespace KeySoftItalia;

require_once __DIR__ . '/BackendValidation.php';

final class ProductImport
{
    public static function currency(string $value): string
    {
        $value = preg_replace('/[\s\x{00a0}€]/u', '', trim($value));
        if (str_contains($value, ',')) {
            // Italian thousands separators are accepted only in groups of three.
            if (!preg_match('/^(?:\d+|\d{1,3}(?:\.\d{3})+),\d{1,2}$/D', $value)) {
                throw new \InvalidArgumentException('Prezzo CSV non valido.');
            }
            $value = str_replace(['.', ','], ['', '.'], $value);
        } elseif (preg_match('/^\d{1,3}(?:\.\d{3})+$/D', $value)) {
            $value = str_replace('.', '', $value);
        }
        return BackendValidation::money($value === '' ? '0' : $value, 'price');
    }

    public static function products(string $json): array
    {
        try { $products = json_decode($json, true, 512, JSON_THROW_ON_ERROR); }
        catch (\JsonException $e) { throw new \InvalidArgumentException('Elenco prodotti non valido.', 0, $e); }
        if (!is_array($products) || !array_is_list($products) || !$products || count($products) > 5000) {
            throw new \InvalidArgumentException('Seleziona da 1 a 5000 prodotti.');
        }
        $seen = [];
        foreach ($products as &$product) {
            if (!is_array($product)) throw new \InvalidArgumentException('Prodotto non valido.');
            BackendValidation::scalarFields($product);
            foreach (['sku' => 40, 'brand' => 100, 'model' => 160, 'device_type' => 50, 'color' => 40, 'short_desc' => 255] as $field => $length) {
                $value = trim((string)($product[$field] ?? ''));
                if (mb_strlen($value) > $length || (in_array($field, ['sku', 'brand', 'model', 'device_type'], true) && $value === '')) {
                    throw new \InvalidArgumentException('Campo prodotto non valido: ' . $field . '.');
                }
                $product[$field] = $value;
            }
            $skuKey = mb_strtolower($product['sku']);
            if (isset($seen[$skuKey])) throw new \InvalidArgumentException('SKU duplicato: ' . $product['sku'] . '.');
            $seen[$skuKey] = true;
            $product['price'] = BackendValidation::money($product['price'] ?? null, 'price');
            foreach (['storage' => 65535, 'qty' => 2147483647] as $field => $max) {
                $value = (string)($product[$field] ?? '0');
                if (!ctype_digit($value) || strlen($value) > 10 || (float)$value > $max) {
                    throw new \InvalidArgumentException('Campo prodotto non valido: ' . $field . '.');
                }
                $product[$field] = (int)$value;
            }
            if (!in_array($product['grade'] ?? '', ['Nuovo', 'Expo', 'A+', 'A', 'B', 'C'], true)) {
                throw new \InvalidArgumentException('Grado prodotto non valido.');
            }
            $product['full_desc'] = (string)($product['full_desc'] ?? '');
        }
        unset($product);
        return $products;
    }
}
