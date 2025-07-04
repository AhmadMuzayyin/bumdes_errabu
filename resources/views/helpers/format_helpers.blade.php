@php
/**
 * Helper function untuk memformat nominal dengan aman
 * Menangani kasus dimana nilai sudah berformat string "Rp. xx.xxx"
 * 
 * @param mixed $value Nilai yang akan diformat
 * @return string Nilai yang sudah diformat "Rp xx.xxx"
 */
function formatNominalSafe($value) {
    // Jika string dan sudah berformat "Rp. xx.xxx", tampilkan apa adanya
    if (is_string($value) && strpos($value, 'Rp') !== false) {
        return $value;
    }
    
    // Coba ekstrak nilai numerik jika string
    if (is_string($value)) {
        $value = (float) preg_replace('/[^0-9]/', '', $value);
    }
    
    // Format nilai numerik
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

/**
 * Helper function untuk mengekstrak nilai numerik dari string berformat mata uang
 * 
 * @param mixed $value Nilai yang akan diekstrak
 * @return float Nilai numerik
 */
function extractNumeric($value) {
    // Jika string dan sudah berformat "Rp. xx.xxx", ekstrak nilai numeriknya
    if (is_string($value) && (strpos($value, 'Rp') !== false || strpos($value, '.') !== false)) {
        return (float) preg_replace('/[^0-9]/', '', $value);
    }
    
    // Jika null, return 0
    if (is_null($value)) {
        return 0;
    }
    
    // Return nilai sebagai float
    return (float) $value;
}
@endphp
