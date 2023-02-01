<?php

namespace App\Enums;

enum TaxStatus:string{
  case TidakKawin = 'TK0';
  case TidakKawinSatuTanggungan = 'TK1';
  case TidakKawinDuaTanggungan = 'TK2';
  case TidakKawinTigaTanggungan = 'TK3';
  case Kawin = 'K0';
  case KawinAnakSatu = 'K1';
  case KawinAnakDua = 'K2';
  case KawinAnakTiga = 'K3';
}