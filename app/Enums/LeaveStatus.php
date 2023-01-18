<?php

namespace App\Enums;

enum LeaveStatus:string
{
  case WaitingApproval = 'waiting';
  case Approved = 'approved';
  case ApprovedWithNote = 'approved_with_note';
  case Rejected = 'rejected';
}