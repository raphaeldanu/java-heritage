<?php

namespace App\Enums;

enum LeaveType:string{
  case Tahunan = 'annual';
  case Menikah = 'menikah';
  case Melahirkan = 'melahirkan';
  case KelahiranAnak = 'kelahiran_anak';
  case Khitanan = 'khitanan';
  case Baptis = 'baptis';
  case GugurKandung = 'gugur_kandung';
  case KemalanganAtauKematian = 'kemalangan_atau_kematian';
  case TidakDibayar = 'unpaid_leave';
}