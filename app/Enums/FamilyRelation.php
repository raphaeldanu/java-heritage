<?php
namespace App\Enums;

enum FamilyRelation: string
{
  case Suami = 'husband';
  case Istri = 'wife';
  case Anak = 'child';
}