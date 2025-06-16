<?php
require_once "./connect.php";

// 下載圖片的函數
function downloadImage($url, $product_id) {
    $image_name = basename($url);
    $upload_path = "./uploads/" . $image_name;
    
   
    
    // 下載圖片
    if (file_put_contents($upload_path, file_get_contents($url))) {
        return $image_name;
    }
    return false;
}

// 設定要抓取的商品分類
$categories = [
    1 => '客廳',
    2 => '餐廳/廚房',
    3 => '臥室',
    4 => '兒童房',
    5 => '辦公空間',
    6 => '收納用品'
];

// 設定風格
$styles = [
    '北歐簡約風',
    '現代極簡風',
    '工業復古風',
    '日式禪園風',
    '美式鄉村風',
    '法式優雅風',
    '輕奢設計風',
    '無印自然風'
];

// IKEA 商品資料
$ikea_products = [
    // 客廳家具 (20筆)
    [
        'name' => 'KALLAX 層架組',
        'description' => '多功能層架，可橫放或直放，適合收納和展示。',
        'price' => 1999,
        'quantity' => 40,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'EKTORP 三人座沙發',
        'description' => '舒適的三人座沙發，可拆洗的布套。',
        'price' => 12999,
        'quantity' => 20,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'LACK 邊桌',
        'description' => '輕巧實用的邊桌，適合各種空間。',
        'price' => 399,
        'quantity' => 100,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'POÄNG 扶手椅',
        'description' => '經典設計扶手椅，提供舒適支撐。',
        'price' => 3999,
        'quantity' => 30,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 電視櫃',
        'description' => '現代風格電視櫃，附收納空間。',
        'price' => 2999,
        'quantity' => 25,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'FRIHETEN 沙發床',
        'description' => '多功能沙發床，可展開作為床使用。',
        'price' => 15999,
        'quantity' => 15,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'LÖVBACKEN 茶几',
        'description' => '葉形設計茶几，增添空間趣味。',
        'price' => 1999,
        'quantity' => 35,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'STRANDMON 扶手椅',
        'description' => '高背扶手椅，提供舒適支撐。',
        'price' => 5999,
        'quantity' => 20,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'STOCKHOLM 2017 茶几',
        'description' => '大理石紋茶几，展現優雅風格。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 1,
        'style' => '輕奢設計風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'VIMLE 沙發',
        'description' => '模組化沙發，可自由組合。',
        'price' => 19999,
        'quantity' => 15,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'KIVIK 沙發',
        'description' => '寬敞舒適的沙發，適合家庭使用。',
        'price' => 24999,
        'quantity' => 10,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'LIDHULT 沙發',
        'description' => '高品質沙發，提供極致舒適。',
        'price' => 29999,
        'quantity' => 8,
        'category_id' => 1,
        'style' => '輕奢設計風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'NORDLI 床頭櫃',
        'description' => '現代風格床頭櫃，附收納空間。',
        'price' => 2499,
        'quantity' => 40,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'HEMNES 電視櫃',
        'description' => '實木電視櫃，提供充足收納空間。',
        'price' => 3999,
        'quantity' => 30,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 電視櫃組合',
        'description' => '模組化電視櫃組合，可自由搭配。',
        'price' => 5999,
        'quantity' => 20,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'KALLAX 層架組合',
        'description' => '多功能層架組合，適合收納和展示。',
        'price' => 2999,
        'quantity' => 35,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'LACK 層架',
        'description' => '輕巧實用的層架，適合各種空間。',
        'price' => 699,
        'quantity' => 80,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'BILLY 書櫃',
        'description' => '經典書櫃，可自由組合。',
        'price' => 2499,
        'quantity' => 45,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'FJÄLLBO 層架',
        'description' => '工業風格層架，展現獨特魅力。',
        'price' => 1999,
        'quantity' => 30,
        'category_id' => 1,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'KALLAX 收納櫃',
        'description' => '多功能收納櫃，可橫放或直放。',
        'price' => 2499,
        'quantity' => 40,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],

    // 餐廳/廚房 (20筆)
    [
        'name' => 'BJÖRKUDDEN 餐桌',
        'description' => '實木餐桌，可延伸設計，適合家庭使用。',
        'price' => 5999,
        'quantity' => 25,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'METOD 廚房系統',
        'description' => '模組化廚房系統，可依需求組合。',
        'price' => 29999,
        'quantity' => 15,
        'category_id' => 2,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'INGATORP 餐椅',
        'description' => '舒適的餐椅，適合長時間用餐。',
        'price' => 1999,
        'quantity' => 50,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'VADHOLMA 廚房推車',
        'description' => '多功能廚房推車，可移動使用。',
        'price' => 3999,
        'quantity' => 30,
        'category_id' => 2,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'KUNGSFORS 廚房收納架',
        'description' => '模組化廚房收納系統，可自由組合。',
        'price' => 2499,
        'quantity' => 40,
        'category_id' => 2,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'BJÖRKUDDEN 餐椅',
        'description' => '實木餐椅，搭配餐桌使用。',
        'price' => 1999,
        'quantity' => 45,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'INGATORP 餐桌',
        'description' => '可延伸餐桌，適合家庭使用。',
        'price' => 7999,
        'quantity' => 20,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'VADHOLMA 廚房島',
        'description' => '多功能廚房島，提供額外工作空間。',
        'price' => 8999,
        'quantity' => 15,
        'category_id' => 2,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'KUNGSFORS 廚房掛架',
        'description' => '廚房收納掛架，節省空間。',
        'price' => 1499,
        'quantity' => 60,
        'category_id' => 2,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'BJÖRKUDDEN 長凳',
        'description' => '實木長凳，可作為座位或收納使用。',
        'price' => 2999,
        'quantity' => 30,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'INGATORP 餐邊櫃',
        'description' => '實木餐邊櫃，提供額外收納空間。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'VADHOLMA 廚房收納櫃',
        'description' => '實木廚房收納櫃，展現自然質感。',
        'price' => 5999,
        'quantity' => 20,
        'category_id' => 2,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'KUNGSFORS 廚房收納系統',
        'description' => '模組化廚房收納系統，可自由組合。',
        'price' => 3499,
        'quantity' => 35,
        'category_id' => 2,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'BJÖRKUDDEN 吧台椅',
        'description' => '實木吧台椅，適合廚房島使用。',
        'price' => 1499,
        'quantity' => 40,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'INGATORP 餐椅組合',
        'description' => '四張餐椅組合，適合家庭使用。',
        'price' => 7999,
        'quantity' => 20,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'VADHOLMA 廚房收納架組合',
        'description' => '廚房收納架組合，提供充足收納空間。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 2,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'KUNGSFORS 廚房收納系統組合',
        'description' => '廚房收納系統組合，可自由搭配。',
        'price' => 6999,
        'quantity' => 20,
        'category_id' => 2,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'BJÖRKUDDEN 餐桌組合',
        'description' => '餐桌和四張餐椅組合，適合家庭使用。',
        'price' => 9999,
        'quantity' => 15,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'INGATORP 餐邊櫃組合',
        'description' => '餐邊櫃和收納架組合，提供充足收納空間。',
        'price' => 7999,
        'quantity' => 20,
        'category_id' => 2,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'VADHOLMA 廚房島組合',
        'description' => '廚房島和收納架組合，提供額外工作空間。',
        'price' => 12999,
        'quantity' => 10,
        'category_id' => 2,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'KUNGSFORS 廚房收納系統組合',
        'description' => '廚房收納系統組合，可自由搭配。',
        'price' => 8999,
        'quantity' => 15,
        'category_id' => 2,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],

    // 臥室 (20筆)
    [
        'name' => 'MALM 床架',
        'description' => '簡約設計床架，附收納空間。',
        'price' => 7999,
        'quantity' => 30,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'PAX 衣櫃系統',
        'description' => '可自由組合的衣櫃系統，提供多種收納方案。',
        'price' => 15999,
        'quantity' => 20,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'HEMNES 床頭櫃',
        'description' => '實木床頭櫃，提供額外收納空間。',
        'price' => 2999,
        'quantity' => 35,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'SLÄKT 床墊',
        'description' => '舒適的記憶棉床墊，提供良好支撐。',
        'price' => 8999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 化妝台',
        'description' => '附鏡子的化妝台，提供充足收納空間。',
        'price' => 3999,
        'quantity' => 30,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'NORDLI 床架',
        'description' => '現代風格床架，附收納空間。',
        'price' => 9999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'PLATSA 衣櫃',
        'description' => '模組化衣櫃，可自由組合。',
        'price' => 6999,
        'quantity' => 30,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'HEMNES 床頭櫃組合',
        'description' => '床頭櫃和收納架組合，提供充足收納空間。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'SLÄKT 床墊組合',
        'description' => '床墊和床包組合，提供舒適睡眠體驗。',
        'price' => 11999,
        'quantity' => 20,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 化妝台組合',
        'description' => '化妝台和收納架組合，提供充足收納空間。',
        'price' => 5999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'NORDLI 床架組合',
        'description' => '床架和床頭櫃組合，提供充足收納空間。',
        'price' => 12999,
        'quantity' => 20,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'PLATSA 衣櫃組合',
        'description' => '衣櫃和收納架組合，提供充足收納空間。',
        'price' => 8999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'HEMNES 床頭櫃組合',
        'description' => '床頭櫃和收納架組合，提供充足收納空間。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'SLÄKT 床墊組合',
        'description' => '床墊和床包組合，提供舒適睡眠體驗。',
        'price' => 11999,
        'quantity' => 20,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 化妝台組合',
        'description' => '化妝台和收納架組合，提供充足收納空間。',
        'price' => 5999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'NORDLI 床架組合',
        'description' => '床架和床頭櫃組合，提供充足收納空間。',
        'price' => 12999,
        'quantity' => 20,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'PLATSA 衣櫃組合',
        'description' => '衣櫃和收納架組合，提供充足收納空間。',
        'price' => 8999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'HEMNES 床頭櫃組合',
        'description' => '床頭櫃和收納架組合，提供充足收納空間。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'SLÄKT 床墊組合',
        'description' => '床墊和床包組合，提供舒適睡眠體驗。',
        'price' => 11999,
        'quantity' => 20,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 化妝台組合',
        'description' => '化妝台和收納架組合，提供充足收納空間。',
        'price' => 5999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
//卉
    // 兒童房 (10筆)
    [
        'name' => 'STUVA 兒童收納櫃',
        'description' => '色彩繽紛的兒童收納櫃，安全圓角設計。',
        'price' => 2999,
        'quantity' => 35,
        'category_id' => 4,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'SUNDVIK 兒童床',
        'description' => '可延伸的兒童床，陪伴孩子成長。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 4,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'FLISAT 兒童書櫃',
        'description' => '開放式兒童書櫃，培養收納習慣。',
        'price' => 1999,
        'quantity' => 40,
        'category_id' => 4,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'MAMMUT 兒童桌椅組',
        'description' => '適合兒童使用的桌椅組，安全穩固。',
        'price' => 1499,
        'quantity' => 50,
        'category_id' => 4,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'BUSUNGE 兒童衣櫃',
        'description' => '兒童專用衣櫃，附掛衣桿和收納空間。',
        'price' => 2999,
        'quantity' => 30,
        'category_id' => 4,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'STUVA 兒童床',
        'description' => '可延伸的兒童床，陪伴孩子成長。',
        'price' => 3999,
        'quantity' => 35,
        'category_id' => 4,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'SUNDVIK 兒童書櫃',
        'description' => '開放式兒童書櫃，培養收納習慣。',
        'price' => 2499,
        'quantity' => 30,
        'category_id' => 4,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'FLISAT 兒童桌椅組',
        'description' => '適合兒童使用的桌椅組，安全穩固。',
        'price' => 1999,
        'quantity' => 40,
        'category_id' => 4,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'MAMMUT 兒童衣櫃',
        'description' => '兒童專用衣櫃，附掛衣桿和收納空間。',
        'price' => 3499,
        'quantity' => 25,
        'category_id' => 4,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'BUSUNGE 兒童收納櫃',
        'description' => '色彩繽紛的兒童收納櫃，安全圓角設計。',
        'price' => 2499,
        'quantity' => 35,
        'category_id' => 4,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],

    // 辦公空間 (5筆)
    [
        'name' => 'BEKANT 辦公桌',
        'description' => '人體工學辦公桌，可調整高度。',
        'price' => 6999,
        'quantity' => 30,
        'category_id' => 5,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'MARKUS 辦公椅',
        'description' => '符合人體工學的辦公椅，提供良好支撐。',
        'price' => 4999,
        'quantity' => 40,
        'category_id' => 5,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'ALEX 抽屜櫃',
        'description' => '辦公收納抽屜櫃，提供充足收納空間。',
        'price' => 2499,
        'quantity' => 45,
        'category_id' => 5,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'LÅNESPELARE 電競椅',
        'description' => '專業電競椅，提供舒適支撐。',
        'price' => 5999,
        'quantity' => 25,
        'category_id' => 5,
        'style' => '工業復古風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'MICKE 書桌',
        'description' => '簡約書桌，附收納空間。',
        'price' => 1999,
        'quantity' => 50,
        'category_id' => 5,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],

    // 收納用品 (5筆)
    [
        'name' => 'SKUBB 收納盒',
        'description' => '多用途收納盒，可折疊收納。',
        'price' => 299,
        'quantity' => 100,
        'category_id' => 6,
        'style' => '無印自然風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'SAMLA 收納箱',
        'description' => '堅固耐用的收納箱，可堆疊使用。',
        'price' => 399,
        'quantity' => 80,
        'category_id' => 6,
        'style' => '無印自然風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'TJENA 收納盒',
        'description' => '環保材質收納盒，適合文件收納。',
        'price' => 199,
        'quantity' => 120,
        'category_id' => 6,
        'style' => '無印自然風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'KUGGIS 收納箱',
        'description' => '可折疊收納箱，節省空間。',
        'price' => 499,
        'quantity' => 60,
        'category_id' => 6,
        'style' => '無印自然風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'SOCKERBIT 收納盒',
        'description' => '多層收納盒，適合小物收納。',
        'price' => 299,
        'quantity' => 90,
        'category_id' => 6,
        'style' => '無印自然風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'MALM 書桌',
        'description' => '簡約設計書桌，附收納空間。',
        'price' => 4999,
        'quantity' => 30,
        'category_id' => 5,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'PAX 衣櫃',
        'description' => '模組化衣櫃，提供多種收納方案。',
        'price' => 15999,
        'quantity' => 20,
        'category_id' => 3,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'HEMNES 床架',
        'description' => '實木床架，提供舒適睡眠。',
        'price' => 9999,
        'quantity' => 25,
        'category_id' => 3,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'LACK 茶几',
        'description' => '輕巧實用的茶几，適合各種空間。',
        'price' => 399,
        'quantity' => 100,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'POÄNG 扶手椅',
        'description' => '經典設計扶手椅，提供舒適支撐。',
        'price' => 3999,
        'quantity' => 30,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 電視櫃',
        'description' => '現代風格電視櫃，附收納空間。',
        'price' => 2999,
        'quantity' => 25,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'FRIHETEN 沙發床',
        'description' => '多功能沙發床，可展開作為床使用。',
        'price' => 15999,
        'quantity' => 15,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'LÖVBACKEN 茶几',
        'description' => '葉形設計茶几，增添空間趣味。',
        'price' => 1999,
        'quantity' => 35,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'STRANDMON 扶手椅',
        'description' => '高背扶手椅，提供舒適支撐。',
        'price' => 5999,
        'quantity' => 20,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
    [
        'name' => 'STOCKHOLM 2017 茶几',
        'description' => '大理石紋茶几，展現優雅風格。',
        'price' => 4999,
        'quantity' => 25,
        'category_id' => 1,
        'style' => '輕奢設計風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
    ],
    [
        'name' => 'VIMLE 沙發',
        'description' => '模組化沙發，可自由組合。',
        'price' => 19999,
        'quantity' => 15,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
    ],
    [
        'name' => 'KIVIK 沙發',
        'description' => '寬敞舒適的沙發，適合家庭使用。',
        'price' => 24999,
        'quantity' => 10,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
    ],
    [
        'name' => 'LIDHULT 沙發',
        'description' => '高品質沙發，提供極致舒適。',
        'price' => 29999,
        'quantity' => 8,
        'category_id' => 1,
        'style' => '輕奢設計風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
    ],
    [
        'name' => 'NORDLI 床頭櫃',
        'description' => '現代風格床頭櫃，附收納空間。',
        'price' => 2499,
        'quantity' => 40,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
    ],
    [
        'name' => 'HEMNES 電視櫃',
        'description' => '實木電視櫃，提供充足收納空間。',
        'price' => 3999,
        'quantity' => 30,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
    ],
    [
        'name' => 'BRIMNES 電視櫃組合',
        'description' => '模組化電視櫃組合，可自由搭配。',
        'price' => 5999,
        'quantity' => 20,
        'category_id' => 1,
        'style' => '現代極簡風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
    ],
    [
        'name' => 'KALLAX 層架組合',
        'description' => '多功能層架組合，適合收納和展示。',
        'price' => 2999,
        'quantity' => 35,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
    ],
    [
        'name' => 'LACK 層架',
        'description' => '輕巧實用的層架，適合各種空間。',
        'price' => 699,
        'quantity' => 80,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
    ],
    [
        'name' => 'BILLY 書櫃',
        'description' => '經典書櫃，可自由組合。',
        'price' => 2499,
        'quantity' => 45,
        'category_id' => 1,
        'style' => '北歐簡約風',
        'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
    ],
        [
            'name' => 'MALM 床架',
            'description' => '簡約設計床架，附收納空間。',
            'price' => 7999,
            'quantity' => 30,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
        ],
        [
            'name' => 'PAX 衣櫃系統',
            'description' => '可自由組合的衣櫃系統，提供多種收納方案。',
            'price' => 15999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
        ],
        [
            'name' => 'HEMNES 床頭櫃',
            'description' => '實木床頭櫃，提供額外收納空間。',
            'price' => 2999,
            'quantity' => 35,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
        ],
        [
            'name' => 'SLÄKT 床墊',
            'description' => '舒適的記憶棉床墊，提供良好支撐。',
            'price' => 8999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
        ],
        [
            'name' => 'BRIMNES 化妝台',
            'description' => '附鏡子的化妝台，提供充足收納空間。',
            'price' => 3999,
            'quantity' => 30,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
        ],
        [
            'name' => 'NORDLI 床架',
            'description' => '現代風格床架，附收納空間。',
            'price' => 9999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
        ],
        [
            'name' => 'PLATSA 衣櫃',
            'description' => '模組化衣櫃，可自由組合。',
            'price' => 6999,
            'quantity' => 30,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
        ],
        [
            'name' => 'HEMNES 床頭櫃組合',
            'description' => '床頭櫃和收納架組合，提供充足收納空間。',
            'price' => 4999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
        ],
        [
            'name' => 'SLÄKT 床墊組合',
            'description' => '床墊和床包組合，提供舒適睡眠體驗。',
            'price' => 11999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
        ],
        [
            'name' => 'BRIMNES 化妝台組合',
            'description' => '化妝台和收納架組合，提供充足收納空間。',
            'price' => 5999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
        ],
        [
            'name' => 'NORDLI 床架組合',
            'description' => '床架和床頭櫃組合，提供充足收納空間。',
            'price' => 12999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
        ],
        [
            'name' => 'PLATSA 衣櫃組合',
            'description' => '衣櫃和收納架組合，提供充足收納空間。',
            'price' => 8999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
        ],
        [
            'name' => 'HEMNES 床頭櫃組合',
            'description' => '床頭櫃和收納架組合，提供充足收納空間。',
            'price' => 4999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
        ],
        [
            'name' => 'SLÄKT 床墊組合',
            'description' => '床墊和床包組合，提供舒適睡眠體驗。',
            'price' => 11999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
        ],
        [
            'name' => 'BRIMNES 化妝台組合',
            'description' => '化妝台和收納架組合，提供充足收納空間。',
            'price' => 5999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
        ],
        [
            'name' => 'NORDLI 床架組合',
            'description' => '床架和床頭櫃組合，提供充足收納空間。',
            'price' => 12999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
        ],
        [
            'name' => 'PLATSA 衣櫃組合',
            'description' => '衣櫃和收納架組合，提供充足收納空間。',
            'price' => 8999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
        ],
        [
            'name' => 'HEMNES 床頭櫃組合',
            'description' => '床頭櫃和收納架組合，提供充足收納空間。',
            'price' => 4999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/669/1366928_PE957217_S4.jpg'
        ],
        [
            'name' => 'SLÄKT 床墊組合',
            'description' => '床墊和床包組合，提供舒適睡眠體驗。',
            'price' => 11999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380103_PE961614_S4.jpg'
        ],
        [
            'name' => 'BRIMNES 化妝台組合',
            'description' => '化妝台和收納架組合，提供充足收納空間。',
            'price' => 5999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/443/1144344_PE881791_S4.jpg'
        ],
        [
            'name' => 'NORDLI 床架組合',
            'description' => '床架和床頭櫃組合，提供充足收納空間。',
            'price' => 12999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/265/1326591_PE944300_S4.jpg'
        ],
        [
            'name' => 'PLATSA 衣櫃組合',
            'description' => '衣櫃和收納架組合，提供充足收納空間。',
            'price' => 8999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/463/1146316_PE882984_S4.jpg'
        ],
        [
            'name' => 'HEMNES 床頭櫃組合',
            'description' => '床頭櫃和收納架組合，提供充足收納空間。',
            'price' => 4999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/860/1386024_PE963590_S4.jpg'
        ],
        [
            'name' => 'SLÄKT 床墊組合',
            'description' => '床墊和床包組合，提供舒適睡眠體驗。',
            'price' => 11999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257458_PE925835_S4.jpg'
        ],
        [
            'name' => 'BRIMNES 化妝台組合',
            'description' => '化妝台和收納架組合，提供充足收納空間。',
            'price' => 5999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/574/1257459_PE925836_S4.jpg'
        ],
        [
            'name' => 'NORDLI 床架組合',
            'description' => '床架和床頭櫃組合，提供充足收納空間。',
            'price' => 12999,
            'quantity' => 20,
            'category_id' => 3,
            'style' => '北歐簡約風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/457/1245761_PE921756_S4.jpg'
        ],
        [
            'name' => 'PLATSA 衣櫃組合',
            'description' => '衣櫃和收納架組合，提供充足收納空間。',
            'price' => 8999,
            'quantity' => 25,
            'category_id' => 3,
            'style' => '現代極簡風',
            'image' => 'https://www.ikea.com.tw/dairyfarm/tw/images/801/1380105_PE961616_S4.jpg'
        ],
    ];

// 插入商品資料
$sql_product = "INSERT INTO products (user_id, name, category_id, description, price, quantity, style) VALUES (?, ?, ?, ?, ?, ?, ?)";
$sql_image = "INSERT INTO product_img (product_id, img) VALUES (?, ?)";

try {
    $stmt_product = $pdo->prepare($sql_product);
    $stmt_image = $pdo->prepare($sql_image);
    
    foreach ($ikea_products as $product) {
        // 插入商品資料
        $stmt_product->execute([
            1, // user_id
            $product['name'],
            $product['category_id'],
            $product['description'],
            $product['price'],
            $product['quantity'],
            $product['style']
        ]);
        
        // 取得剛插入的商品ID
        $product_id = $pdo->lastInsertId();
        
        // 下載圖片並保存到uploads資料夾
        $image_name = downloadImage($product['image'], $product_id);
        if ($image_name) {
            // 插入商品圖片路徑到資料庫
            $stmt_image->execute([
                $product_id,
                $image_name  // 只存檔名，不存完整路徑
            ]);
        } else {
            echo "無法下載圖片: " . $product['image'] . "\n";
        }
    }
    
    echo "成功插入 " . count($ikea_products) . " 筆商品資料";
    
} catch (PDOException $e) {
    echo "錯誤: " . $e->getMessage();
}
?> 