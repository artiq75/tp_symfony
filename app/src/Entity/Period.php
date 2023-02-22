<?php

namespace App\Entity;

class Period
{
  public const CAMPING_OPEN_DATE = [
    'start' => [
      'days' => 05,
      'month' => 05
    ],
    'end' => [
      'days' => 10,
      'month' => 10
    ]
  ];

  public const CAMPING_HIGHT_SEASON_DATE = [
    'start' => [
      'days' => 21,
      'month' => 06
    ],
    'end' => [
      'days' => 31,
      'month' => 8
    ]
  ];

  public static function isCampingOpen(): bool
  {
    $now = new \DateTime();

    return
      $now->format('d') >= self::CAMPING_OPEN_DATE['start']['days'] &&
      $now->format('m') >= self::CAMPING_OPEN_DATE['start']['month'] &&
      $now->format('d') <= self::CAMPING_OPEN_DATE['start']['days'] &&
      $now->format('m') <= self::CAMPING_OPEN_DATE['start']['month'];
  }
}