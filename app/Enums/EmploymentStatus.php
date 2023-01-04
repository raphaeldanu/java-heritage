<?php

namespace App\Enums;

enum EmploymentStatus:string
{
  case Permanent = 'permanent';
  case Contract = 'contract';
  case DailyWorker = 'daily_worker';
  case Resigned = 'resigned';
}