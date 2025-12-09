<?php

namespace App\Services;

use Illuminate\Support\Collection;

class EmaarPayloadBuilder
{
    public function buildDailyPayload(
        string $unitNo,
        string $leaseCode,
        string $salesDate,
        int $transactionCount,
        float $netSales,
        array $channelData
    ): array {
        return [
            'SalesDataCollection' => [
                'SalesInfo' => [
                    [
                        'UnitNo' => $unitNo,
                        'LeaseCode' => $leaseCode,
                        'SalesDate' => $salesDate,
                        'TransactionCount' => (string) $transactionCount,
                        'NetSales' => (string) $netSales,
                        'FandBSplit' => [
                            $this->buildFandBSplit($channelData),
                        ],
                    ],
                ],
            ],
        ];
    }

    public function buildMonthlyPayload(
        string $unitNo,
        string $leaseCode,
        string $salesDateFrom,
        string $salesDateTo,
        int $transactionCount,
        float $totalSales,
        array $channelData
    ): array {
        return [
            'SalesDataCollection' => [
                'SalesInfo' => [
                    [
                        'UnitNo' => $unitNo,
                        'LeaseCode' => $leaseCode,
                        'SalesDateFrom' => $salesDateFrom,
                        'SalesDateTo' => $salesDateTo,
                        'TransactionCount' => (string) $transactionCount,
                        'TotalSales' => (string) $totalSales,
                        'Remarks' => 'Remarks',
                        'FandBSplit' => [
                            $this->buildFandBSplit($channelData),
                        ],
                    ],
                ],
            ],
        ];
    }

    private function buildFandBSplit(array $channelData): array
    {
        return [
            'Ch_Zomato' => 0,
            'Ch_Deliveroo' => 0,
            'Ch_DineIn' => (string) ($channelData['Ch_DineIn']['amount'] ?? 0),
            'Ch_Talabat' => (string) ($channelData['Ch_Talabat']['amount'] ?? 0),
            'Ch_CleanEatMe' => 0,
            'Ch_Noon' => 0,
            'Ch_MunchOn' => 0,
            'Ch_CareemNOW' => 0,
            'Ch_EatEasy' => 0,
            'Ch_UberEat' => 0,
            'Ch_OwnDelivery' => 0,
            'Ch_NowNow' => 0,
            'Ch_Amazon' => 0,
            'Ch_CofeApp' => 0,
            'Ch_Instashop' => 0,
            'Ch_Tawseel' => 0,
            'Ch_Kitopi' => 0,
            'Ch_ChatFood' => 0,
            'Ch_EMAREAT' => 0,
            'Ch_Foodate' => 0,
            'Ch_CoffeePik' => 0,
            'Ch_Drivu' => (string) ($channelData['Ch_Drivu']['amount'] ?? 0),
            'Ch_Littlemees' => 0,
            'Ch_Swan' => 0,
            'Ch_JoiGifts' => 0,
            'Ch_Zomatocnt' => 0,
            'Ch_Deliveroocnt' => 0,
            'Ch_DineIncnt' => (string) ($channelData['Ch_DineIn']['count'] ?? 0),
            'Ch_Talabatcnt' => (string) ($channelData['Ch_Talabat']['count'] ?? 0),
            'Ch_CleanEatMecnt' => 0,
            'Ch_Nooncnt' => 0,
            'Ch_MunchOncnt' => 0,
            'Ch_CareemNOWcnt' => 0,
            'Ch_EatEasycnt' => 0,
            'Ch_UberEatcnt' => 0,
            'Ch_OwnDeliverycnt' => 0,
            'Ch_NowNowcnt' => 0,
            'Ch_Amazoncnt' => 0,
            'Ch_CofeAppcnt' => 0,
            'Ch_Instashopcnt' => 0,
            'Ch_Tawseelcnt' => 0,
            'Ch_Kitopicnt' => 0,
            'Ch_ChatFoodcnt' => 0,
            'Ch_EMAREATcnt' => 0,
            'Ch_Foodatecnt' => 0,
            'Ch_CoffeePikcnt' => 0,
            'Ch_Drivucnt' => (string) ($channelData['Ch_Drivu']['count'] ?? 0),
            'Ch_Littlemeescnt' => 0,
            'Ch_Swancnt' => 0,
            'Ch_JoiGiftscnt' => 0,
        ];
    }
}

