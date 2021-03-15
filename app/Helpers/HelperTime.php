<?php

namespace App\Helpers;

class HelperTime {
    public function ReadableDate($created_at) {
        $today = date('Y-m-d H:i:s');
        $dift_s = strtotime($today) - strtotime($created_at);
        $dift_d = floor($dift_s / 86400);

        if($dift_d == 0){
          if ($dift_s < 10) {
            return ' Baru Saja';
          }elseif($dift_s < 60){
            return floor($dift_s).' Detik';
          }elseif ($dift_s < 3600) {
            return floor($dift_s / 60).' Menit';
          }elseif ($dift_s < 86400) {
            return floor($dift_s / 3600).' Jam';
          }
        }else {
          if ($dift_d == 1) {
            return ' Kemarin';
          }elseif ($dift_d <= 7) {
            return $dift_d .' Hari';
          }elseif ($dift_d <= 31) {
            return floor($dift_d / 7).' Minggu';
          }elseif ($dift_d <= 365) {
            return floor($dift_d / 31).' Bulan';
          }else {
            return date('jS F Y', strtotime($created_at));
          }
        }
    }
}
