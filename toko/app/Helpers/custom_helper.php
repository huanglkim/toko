<?php

use App\Models\Barang;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Str;


function formatRupiah($angka)
{
    return 'Rp. ' . number_format($angka, 0, ',', '.');
}
function formatTanggal($date)
{
    return Carbon::parse($date)->locale('id')->translatedFormat('d F Y');
}
function excerpt($text, $length = 100)
{
    return Str::limit($text, $length, '...');
}
function formatNama($name)
{
    return ucwords(strtolower($name));
}
function badgeStatus($status)
{
    $colors = [
        'V' => 'green',
        'X' => 'red',
        '?' => 'yellow',
    ];

    return '<span style="color: ' . ($colors[$status] ?? 'gray') . ';">' . ucfirst($status) . '</span>';
}
function cleanString($text)
{
    return preg_replace('/[^A-Za-z0-9 ]/', '', $text);
}
function waktuRelatif($date)
{
    return Carbon::parse($date)->locale('id')->diffForHumans();
}
function calculate_age($birthDate)
{
    return \Carbon\Carbon::parse($birthDate)->age;
}
function slugify($string)
{
    return Str::slug($string);
}
function array_to_object($array)
{
    return (object) $array;
}
function is_empty_array($array)
{
    return empty($array) || count($array) == 0;
}
function generate_random_string($length = 16)
{
    return Str::random($length);
}
function is_valid_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
function generate_unique_token()
{
    return Str::random(60);
}
function is_active_route($route)
{
    return request()->routeIs($route) ? 'active' : '';
}
function word_count($string)
{
    return str_word_count($string);
}
function to_uppercase($string)
{
    return strtoupper($string);
}
function trim_spaces($string)
{
    return trim($string);
}
function contains_word($string, $word)
{
    return strpos($string, $word) !== false;
}
function format_time($time)
{
    return \Carbon\Carbon::parse($time)->format('h:i A');
}
function generate_random_number($min, $max)
{
    return rand($min, $max);
}
function convert_timezone($datetime, $timezone = 'Asia/Jakarta')
{
    return \Carbon\Carbon::parse($datetime)->setTimezone($timezone);
}
function format_datetime($datetime, $format = 'Y-m-d H:i:s')
{
    return \Carbon\Carbon::parse($datetime)->format($format);
}
function is_logged_in()
{
    return auth()->check();
}
function current_url()
{
    return url()->current();
}
function get_user_ip()
{
    return request()->ip();
}
function gravatar_url($email, $size = 80)
{
    $hashedEmail = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/{$hashedEmail}?s={$size}&d=mp";
}

function flash_message($type = 'success', $message = '')
{
    session()->flash('message', $message);
    session()->flash('message_type', $type);
}
function generate_random_color()
{
    return '#' . dechex(rand(0x000000, 0xffffff));
}
function count_category_items($categoryId)
{
    return \App\Models\Barang::where('barang', $categoryId)->count();
}
function email_exists($email)
{
    return App\Models\Users::where('username', $email)->exists();
}
// function is_super_admin()
// {
//     return auth()->user() && auth()->user()->hasRole('super-admin');
// }
function generate_url($route, $params = [])
{
    return route($route, $params);
}
function generate_uuid()
{
    return Str::uuid()->toString();
}
function upload_file($file, $path)
{
    $filename = time() . '-' . $file->getClientOriginalName();
    $file->move(public_path($path), $filename);
    return $path . '/' . $filename;
}
function is_updated_recently($model, $minutes = 60)
{
    return $model->updated_at->gt(now()->subMinutes($minutes));
}
function get_day_of_week($date)
{
    $day = \Carbon\Carbon::parse($date)->format('l');
    $daysInIndonesian = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    return $daysInIndonesian[$day] ?? $day;
}
function sort_array_by_column(array $array, $column, $ascending = true)
{
    usort($array, function ($a, $b) use ($column, $ascending) {
        return ($ascending ? $a[$column] <=> $b[$column] : $b[$column] <=> $a[$column]);
    });
    return $array;
}
function contains_number($string)
{
    return preg_match('/\d/', $string) > 0;
}
function is_user_online($user)
{
    return $user->last_activity && $user->last_activity->gt(now()->subMinutes(5));
}
function status_text($status)
{
    return $status ? 'Aktif' : 'Non-Aktif';
}
function time_elapsed($datetime)
{
    return \Carbon\Carbon::parse($datetime)->locale('id')->diffForHumans();
}
function generate_random_url($path = '', $length = 32)
{
    return url($path . '/' . Str::random($length));
}
function default_image($image, $default = 'default.jpg')
{
    return $image ? asset('storage/images/' . $image) : asset('storage/images/' . $default);
}
function contains_keyword($string, $keyword)
{
    return strpos($string, $keyword) !== false;
}
function format_12hr_time($datetime)
{
    return \Carbon\Carbon::parse($datetime)->format('h:i A');
}
function calculate_total_payment($items, $tax = 0, $discount = 0)
{
    $total = collect($items)->sum('price');
    $total += $total * ($tax / 100);
    $total -= $total * ($discount / 100);
    return $total;
}
function flash_notification($type = 'success', $message = '')
{
    session()->flash('notification', ['type' => $type, 'message' => $message]);
}
function calculate_discount($price, $discountPercentage)
{
    return $price * ($discountPercentage / 100);
}
function is_image($file)
{
    $mimeType = mime_content_type($file);
    return in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif']);
}
function days_in_month($month, $year)
{
    return \Carbon\Carbon::create($year, $month, 1)->daysInMonth;
}
function is_local_ip($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && (
            strpos($ip, '127.') === 0 || strpos($ip, '10.') === 0 || strpos($ip, '192.168.') === 0
        );
    }
    