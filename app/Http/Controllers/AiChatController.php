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

        $searchKeywords = $this->extractProductKeywords($userMessage);

        if (! empty($searchKeywords)) {
            $products = $this->searchProducts($searchKeywords);

            if ($products->isNotEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'reply' => 'Berikut rekomendasi sparepart yang cocok untuk kebutuhanmu:',
                    'products' => $products->map(fn ($p) => $this->formatProduct($p)),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'reply' => 'Maaf, saya tidak menemukan sparepart yang cocok. Coba kata kunci lain seperti: filter udara, kampas rem, oli motor, dll.',
            ]);
        }

        return $this->getAiResponse($userMessage);
    }

    private function extractProductKeywords($message)
    {
        $keywords = [];
        $lowerMessage = strtolower($message);

        $sparepartKeywords = [
            'filter' => ['filter udara', 'filter', 'filter oil', 'filter oli'],
            'oli' => ['oli', 'oli motor', 'sae', 'gear oil', 'mineral'],
            'kampas' => ['kampas', 'kampas rem', 'brake pad', 'brake lining'],
            'busi' => ['busi', 'busi motor', 'spark plug', 'ngk', 'denso'],
            'ban' => ['ban', 'ban motor', 'tire', 'tube', 'velg'],
            'aki' => ['aki', 'aki motor', 'battery', 'accu'],
            'lampu' => ['lampu', 'lampu motor', 'led', 'spotlight', 'tidak bisa menyala', 'mati', 'rusak'],
            'kabel' => ['kabel', 'kabel busi', 'kabel tangga'],
            'chain' => ['chain', 'roda gig', 'set chain', 'roller', 'sprocket'],
            'carbu' => ['carburator', 'carbu', 'jet', 'needle'],
            'ganti' => ['ganti', 'tuning', 'replace', 'service'],
            'motor' => ['motor', 'honda', 'yamaha', 'suzuki', 'kawasaki'],
            'vario' => ['vario', 'beat', 'scoopy', 'aerox'],
            'starter' => ['starter', 'tidak bisa menyala', 'dinamo', 'kopling'],
            'klakson' => ['klakson', 'klakson motor', 'horn', 'suara'],
        ];

        foreach ($sparepartKeywords as $key => $patterns) {
            foreach ($patterns as $pattern) {
                if (strpos($lowerMessage, $pattern) !== false) {
                    $keywords[] = $key;
                    break;
                }
            }
        }

        return $keywords;
    }

    private function searchProducts($keywords)
    {
        $query = Product::with(['prices', 'images', 'category']);

        $query->where(function ($q) use ($keywords) {
            $first = true;
            foreach ($keywords as $keyword) {
                if ($first) {
                    $q->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('brand', 'LIKE', "%{$keyword}%")
                        ->orWhere('item_code', 'LIKE', "%{$keyword}%");
                    $first = false;
                } else {
                    $q->orWhere('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('brand', 'LIKE', "%{$keyword}%")
                        ->orWhere('item_code', 'LIKE', "%{$keyword}%");
                }
            }
        });

        $query->where('current_stock', '>', 0);

        return $query->inRandomOrder()->limit(4)->get();
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

    private function getAiResponse($userMessage)
    {
        $apiKey = config('services.gemini.key') ?: env('GEMINI_API_KEY');

        if (! $apiKey) {
            return $this->getFallbackResponse($userMessage);
        }

        try {
            $response = Http::withoutVerifying()->timeout(10)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='.$apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $this->buildSystemPrompt($userMessage)],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                return $this->getFallbackResponse($userMessage);
            }

            $data = $response->json();

            if (isset($data['error'])) {
                return $this->getFallbackResponse($userMessage);
            }

            $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Waduh, mekanik AI lagi bengong nih.';
            $aiReplyHtml = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $aiReply);

            return response()->json([
                'status' => 'success',
                'reply' => nl2br($aiReplyHtml),
            ]);

        } catch (\Exception $e) {
            return $this->getFallbackResponse($userMessage);
        }
    }

    private function getFallbackResponse($userMessage)
    {
        $lowerMsg = strtolower($userMessage);
        $responses = [
            'busi' => 'Busi rusak bisa jadi penyebab motor tidak bisa menyala. Rekomendasi: ganti busi dengan NGK atau Denso.',
            'aki' => 'Aki habis atau lemah? Coba cek with voltmeter, bila <12V sebaiknya diganti.',
            'starter' => 'Starter dinamo mungkin bermasalah. Cek kabel starter dan konektornya.',
            'lampu' => 'Tidak ada listrik bisa dari busi atau aki. Periksa terlebih dahulu.',
            'klakson' => 'Klakson tidak bunyi biasanya karena sistem charging tidak berfungsi. Cek aki, stator, atau regulator.',
        ];

        foreach ($responses as $key => $response) {
            if (strpos($lowerMsg, $key) !== false) {
                return response()->json(['status' => 'success', 'reply' => $response]);
            }
        }

        return response()->json([
            'status' => 'success',
            'reply' => 'Motor tidak bisa menyala biasanya masalah: busi, aki, atau kabel. Coba cek dulu busi dan aki, kalau masih error silakan hubungi service terdekat.',
        ]);
    }

    private function buildSystemPrompt($userMessage)
    {
        return "Kamu adalah seorang asisten mekanik virtual yang pintar, ramah, dan gaul dari PartLyfe. Kamu bisa ngomong santai dan natural, tapi HANYA seputar sepeda motor dan sparepart roda dua. Jawablah dengan singkat, padat, dan helpful. 

RULES PENTING:
- Kamu BISA ajak ngomong tentang apa saja yang BERHUBUNGAN DENGAN SEPEDA MOTOR (tips perawatan, jenis motor, merk, problem solving, sparepart, dll)
- Kamu TIDAK BOLEH menjawab pertanyaan di LUAR topik sepeda motor
- Jika ditanya topik di luar motor, jawab dengan santai: 'Maaf bro, saya ini mekanik virtual, jadi cuma bisa obrolan seputar motor aja. Tanya aja soal motor, perawatan, atau sparepart!'
- Gunakan bahasa Indonesia yang santai, pakai emoji sesekali biar fun
- Kalau ada kesempatan rekomendasikan produk dari PartLyfe yang relevan

Pertanyaan pelanggan: ".$userMessage;
    }
}
