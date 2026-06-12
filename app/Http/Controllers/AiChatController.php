<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function chat()
    {
        return view('components.ai-chat');
    }

    public function send(Request $request)
    {
        $userMessage = $request->input('message');
        if (! $userMessage) {
            return response()->json(['status' => 'error', 'reply' => 'Pesan kosong.'], 400);
        }

        $apiKey = env('GEMINI_API_KEY') ?: config('services.gemini.key');

        if (! $apiKey) {
            return $this->getDynamicFallback($userMessage);
        }

        try {
            // 1. Tarik daftar nama produk yang ready untuk bahan rekomendasi
            $availableProducts = Product::where('current_stock', '>', 0)->pluck('name')->toArray();
            $itemsList = implode(', ', $availableProducts);

            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

            // 2. Prompt dibuat fokus pada percakapan montir yang menyebutkan produk toko
            $systemInstruction = "Kamu adalah 'Mechanix', asisten montir virtual gaul dari PartLyfe. Jawab chat user seputar otomotif dengan ramah, santai, singkat, dan solutif menggunakan bahasa Indonesia.\n\n"
                . "RULES UTAMA:\n"
                . "- Sebutkan nama barang dari daftar produk toko kita jika relevan dengan keluhan atau pertanyaan user.\n"
                . "- JANGAN merekomendasikan produk di luar list toko jika list toko memiliki barang yang cocok.\n\n"
                . "DAFTAR PRODUK YANG TERSEDIA DI TOKO KITA:\n"
                . $itemsList;

            $response = Http::withoutVerifying()->timeout(12)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Pertanyaan User: " . $userMessage],
                        ],
                    ],
                ],
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $systemInstruction],
                    ],
                ],
            ]);

            if ($response->failed()) {
                return $this->getDynamicFallback($userMessage);
            }

            $data = $response->json();
            $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (! $aiReply) {
                return $this->getDynamicFallback($userMessage);
            }

            // 3. DETEKSI PRODUK SECARA OTOMATIS BERDASARKAN JAWABAN GEMINI
            $productsData = [];
            $lowerReply = strtolower($aiReply);
            
            // Cari kecocokan kata kunci produk dari database untuk dimunculkan di card box
            foreach ($availableProducts as $productName) {
                $lowerProdName = strtolower($productName);
                // Jika Gemini menyebutkan nama produk tersebut di dalam teks jawabannya, kita ambil produknya!
                if (strpos($lowerReply, $lowerProdName) !== false || $this->checkKeywordOverlap($lowerReply, $lowerProdName)) {
                    $matchedProduct = Product::with(['prices', 'images'])
                        ->where('name', $productName)
                        ->where('current_stock', '>', 0)
                        ->first();
                    
                    if ($matchedProduct) {
                        $productsData[] = $this->formatProduct($matchedProduct);
                    }
                }
                
                // Batasi maksimal 3 card produk saja yang tampil agar tidak kepenuhan
                if (count($productsData) >= 3) {
                    break;
                }
            }

            // Jika user nanya produk tapi deteksi teks gagal, cari alternatif manual via query nama
            if (empty($productsData) && (strpos(strtolower($userMessage), 'oli') !== false || strpos(strtolower($userMessage), 'mpx') !== false)) {
                $fallbackOli = Product::with(['prices', 'images'])
                    ->where('name', 'LIKE', '%oli%')
                    ->orWhere('name', 'LIKE', '%mpx%')
                    ->where('current_stock', '>', 0)
                    ->limit(2)
                    ->get();
                $productsData = $fallbackOli->map(fn ($p) => $this->formatProduct($p))->toArray();
            }

            // Ubah markdown **bold** ke tag HTML strong
            $aiReplyHtml = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $aiReply);

            return response()->json([
                'status' => 'success',
                'reply' => nl2br($aiReplyHtml),
                'products' => array_values($productsData),
            ]);

        } catch (\Exception $e) {
            return $this->getDynamicFallback($userMessage);
        }
    }

    // Fungsi pembantu untuk mencocokkan kata kunci parsial (misal: "oli mpx" cocok dengan "oli mpx 2")
    private function checkKeywordOverlap($reply, $productName) {
        $words = explode(' ', $productName);
        foreach ($words as $word) {
            if (strlen($word) > 3 && strpos($reply, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    private function formatProduct($product)
    {
        $price = $product->prices->where('price_level', 1)->first();
        $image = $product->images->first();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand,
            'sku' => $product->item_code,
            'price' => $price ? number_format($price->price, 0, ',', '.') : '0',
            'stock' => $product->current_stock,
            'image' => $image ? asset('storage/'.$image->image_path) : null,
            'url' => route('produk.show', $product->id),
            'available' => $product->current_stock > 0,
        ];
    }

    private function getDynamicFallback($userMessage)
    {
        $lowerMsg = strtolower($userMessage);
        $productsData = [];
        
        $reply = "Halo Bos! Di PartLyfe kita menyediakan berbagai sparepart roda dua premium. Kamu lagi cari komponen spesifik apa nih buat motor kesayanganmu? Biar Mechanix bantu cek stoknya! ЁЯЫая╕П";

        // Query cerdas pada fallback: Jika user sebut oli, tampilkan oli asli dari DB!
        if (strpos($lowerMsg, 'oli') !== false || strpos($lowerMsg, 'mpx') !== false) {
            $reply = "Untuk produk oli mesin terbaik di PartLyfe, kami sangat merekomendasikan varian oli AHM MPX asli Honda untuk menjaga performa mesin tetap stabil dan irit, Bos!";
            $products = Product::with(['prices', 'images'])->where('name', 'LIKE', '%oli%')->orWhere('name', 'LIKE', '%mpx%')->limit(2)->get();
            $productsData = $products->map(fn ($p) => $this->formatProduct($p))->toArray();
        } elseif (strpos($lowerMsg, 'rem') !== false || strpos($lowerMsg, 'kampas') !== false) {
            $reply = "Safety nomor satu, Bos! Kampas rem kita punya kualitas cengkeraman pakem dan tahan panas.";
            $products = Product::with(['prices', 'images'])->where('name', 'LIKE', '%kampas%')->orWhere('name', 'LIKE', '%rem%')->limit(2)->get();
            $productsData = $products->map(fn ($p) => $this->formatProduct($p))->toArray();
        }

        return response()->json([
            'status' => 'success',
            'reply' => $reply,
            'products' => array_values($productsData)
        ]);
    }
}