<?php

function get_curl($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result, true);
}

function get_surah()
{
    $result = get_curl("https://api.quran.sutanlab.id/surah");
    $surah = [];
    $i = 0;
    foreach ($result["data"] as $result) {
        $surah[$i]["no_surah"] = $result["number"];
        $surah[$i]["nama_surah"] = $result["name"]["transliteration"]["id"];
        $surah[$i]["arti_surah"] = $result["name"]["translation"]["id"];
        $surah[$i]["turun_surah"] = $result["revelation"]["id"];
        $surah[$i]["ayat_surah"] = $result["numberOfVerses"];
        $i++;
    }
    return $surah;
}

function get_ayat($surah_id)
{
    $result = get_curl("https://api.quran.sutanlab.id/surah/" . $surah_id . "");
    $result = $result["data"];
    $surah = [];
    $surah["no_surah"] = $result["number"];
    $surah["nama_surah"] = $result["name"]["transliteration"]["id"];
    $surah["nama_surah_arab"] = $result["name"]["short"];
    $surah["arti_surah"] = $result["name"]["translation"]["id"];
    $surah["turun_surah"] = $result["revelation"]["id"];
    $surah["ayat_surah"] = $result["numberOfVerses"];
    $surah["tafsir_surah"] = $result["tafsir"]["id"];
    $surah["bismillah"] = $result["preBismillah"];

    if ($result["preBismillah"]) {
        $surah["text_bismillah"] = $result["preBismillah"]["text"]["arab"];
        $surah["arti_bismillah"] = $result["preBismillah"]["translation"]["id"];
        $surah["audio_bismillah"] = $result["preBismillah"]["audio"]["primary"];
    }

    $i = 0;
    foreach ($result["verses"] as $result) {
        $surah["ayat"][$i]["no_ayat"] = $result["number"]["inSurah"];
        $surah["ayat"][$i]["text_ayat"] = $result["text"]["arab"];
        $surah["ayat"][$i]["arti_ayat"] = $result["translation"]["id"];
        $surah["ayat"][$i]["audio_ayat"] = $result["audio"]["primary"];
        $surah["ayat"][$i]["tafsir_ayat"] = $result["tafsir"]["id"]["short"];
        $i++;
    }

    return $surah;
}
