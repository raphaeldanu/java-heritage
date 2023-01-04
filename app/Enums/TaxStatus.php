<?php

namespace App\Enums;

enum TaxStatus:string{
  case Single = 'B';
  case Menikah = 'K0';
  case MenikahAnakSatu = 'K1';
  case MenikahAnakDua = 'K2';
  case MenikahAnakTiga = 'K3';
}