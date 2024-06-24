<?php

namespace App\Support\Helper;

use App\Enums\Responses\ResponseTypesEnum;
use App\Enums\Settings\SettingsEnum;
use App\Enums\Times\TimesEnum;
use App\Models\OrderDetail;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Services\Contracts\SendMethodServiceInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Support\Cart\Cart;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class OrderHelper
{
    /**
     * @param int $province
     * @param int $city
     * @param Cart $cart
     * @param int $sendMethodId
     * @return array Returns an array by following structure:
     *               <code>
     *                   [
     *                     'type' => string,
     *                     'message' => string|null,
     *                     'data' => int|null,
     *                     'status' => int,
     *                   ]
     *               </code>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function shippingPriceCalculation(
        int  $province,
        int  $city,
        Cart $cart,
        int  $sendMethodId
    ): array
    {
        $type = ResponseTypesEnum::SUCCESS->value;
        $message = null;
        $data = null;
        $status = ResponseCodes::HTTP_OK;

        /**
         * @var SettingServiceInterface $settingService
         */
        $settingService = app()->get(SettingServiceInterface::class);
        /**
         * @var CityPostPriceServiceInterface $postPriceService
         */
        $postPriceService = app()->get(CityPostPriceServiceInterface::class);
        /**
         * @var WeightPostPriceServiceInterface $weightPostPriceService
         */
        $weightPostPriceService = app()->get(WeightPostPriceServiceInterface::class);
        /**
         * @var SendMethodServiceInterface $sendMethodService
         */
        $sendMethodService = app()->get(SendMethodServiceInterface::class);

        // need to enter province and city to determine post price
        if ($province <= 0 || $city <= 0) {
            $type = ResponseTypesEnum::WARNING->value;

            return compact('type', 'message', 'data', 'status');
        }

        // also cart name will validate in cart helper internally
        $cartCalc = $cart->calculations();

        $weight = $cartCalc->totalWeight();
        $price = $cartCalc->totalDiscountedPrice();
        $shipments = $cartCalc->shipmentCount();

        $neededSettings = $settingService->getSpecificSettings([
            SettingsEnum::STORE_PROVINCE,
            SettingsEnum::STORE_CITY,
            SettingsEnum::DEFAULT_POST_PRICE,
            SettingsEnum::MIN_FREE_POST_PRICE,
        ]);

        $storeProvince = $neededSettings->firstWhere('name', SettingsEnum::STORE_PROVINCE->value)['value'] ?: null;
        $storeCity = $neededSettings->firstWhere('name', SettingsEnum::STORE_CITY->value)['value'] ?: null;
        $defaultPostPrice = $neededSettings->firstWhere('name', SettingsEnum::DEFAULT_POST_PRICE->value)['value'] ?: null;
        $minPriceForFreePostPrice = $neededSettings->firstWhere('name', SettingsEnum::MIN_FREE_POST_PRICE->value)['value'] ?: null;

        // check post price according to send method
        $sendMethod = $sendMethodService->getSpecificMethod($sendMethodId);
        if (!is_null($sendMethod)) {
            if (!$sendMethod->determine_price_by_shop_location) {
                $data = $sendMethod->apply_number_of_shipments_on_price
                    ? $sendMethod->price * $shipments
                    : $sendMethod->price;

                return compact('type', 'message', 'data', 'status');
            }
        }

        // check post price according to store place
        if (
            !is_null($storeProvince) &&
            !is_null($storeCity) &&
            $province == $storeProvince &&
            $city == $storeCity
        ) {
            if (!is_null($minPriceForFreePostPrice) && $price > $minPriceForFreePostPrice) {
                $data = 0;

                return compact('type', 'message', 'data', 'status');
            } elseif (!is_null($defaultPostPrice)) {
                $data = $defaultPostPrice * $shipments;

                return compact('type', 'message', 'data', 'status');
            }
        }

        // if user's place is not as store place, let's determine other parameters
        $cityPostPrice = $postPriceService->getPostPriceByCityId($city);
        $weightPostPrice = $weightPostPriceService->getPostPriceByWeight($weight);

        if (is_null($cityPostPrice) && is_null($weightPostPrice)) {
            // if there is no post price, return default post price
            if (!is_null($defaultPostPrice)) {
                $data = $defaultPostPrice * $shipments;

                return compact('type', 'message', 'data', 'status');
            } else { // otherwise, there is no way to determine post price
                $type = ResponseTypesEnum::ERROR->value;
                $message = 'خطا در محاسبه هزینه ارسال';
                $status = ResponseCodes::HTTP_BAD_REQUEST;

                return compact('type', 'message', 'data', 'status');
            }
        }

        // if one of post prices are null, return other one as post price
        if (is_null($cityPostPrice)) {
            $data = $weightPostPrice->post_price * $shipments;

            return compact('type', 'message', 'data', 'status');
        } elseif (is_null($weightPostPrice)) {
            $data = $cityPostPrice->post_price * $shipments;

            return compact('type', 'message', 'data', 'status');
        }

        // if both city and weight post price are available, we need to have an average determination system
        // in here we just return max of both post prices
        $data = max($cityPostPrice->post_price, $weightPostPrice->post_price) * $shipments;

        return compact('type', 'message', 'data', 'status');
    }

    /**
     * @param int $paymentChunksCount
     * @return int
     */
    public static function getReservationTime(int $paymentChunksCount): int
    {
        $reservationType = config('market.order.reservation_time');

        // default time to reserve
        $time = TimesEnum::MINUTES_10->value;

        if (!is_null($reservationType)) {
            $neededReservationTime = config('market.order.reservation_times.' . $reservationType);

            // if specified type has been declared
            if (!is_null($neededReservationTime)) {
                // is type and array value, it means time is depend on payment chunks count
                if (is_array($neededReservationTime)) {
                    // is time declared according to number of chunks
                    if (
                        isset($neededReservationTime[$paymentChunksCount]) &&
                        intval($neededReservationTime[$paymentChunksCount])
                    ) {
                        $time = intval($neededReservationTime[$paymentChunksCount]);
                    } elseif ( // or is time declared for a larger number of chunks
                        isset($neededReservationTime['n']) &&
                        intval($neededReservationTime['n'])
                    ) {
                        $time = intval($neededReservationTime['n']);
                    }
                } elseif (intval($neededReservationTime)) { // otherwise it is just a singular number
                    $time = intval($neededReservationTime);
                }
            }
        }

        return $time;
    }

    /**
     * @param OrderDetail $detail
     * @return int Return remaining pay time in seconds. It returns 0 if it can't pay anymore
     */
    public static function calculateRemainedPayTime(OrderDetail $detail): int
    {
        $remainedTime = 0;
        $maxReservationTime = config('market.order.max_reservation_time', 0);
        $maxReservationTime = intval($maxReservationTime);

        $orderTimePassed = $detail->ordered_at ? now()->diffInSeconds($detail->ordered_at) : 0;

        // first check if order time noe exceed max reservation time
        if ($maxReservationTime > 0 && $orderTimePassed > 0 && $orderTimePassed < $maxReservationTime) {
            $reservedRow = $detail->reservedOrders()->orderByDesc('expires_at')->first();
            $expireTimePassed = !empty($reservedRow?->expires_at)
                ? now()->diffInSeconds($reservedRow?->expires_at, false)
                : 0;

            // check for reservation expire time
            if (!is_null($reservedRow) && $expireTimePassed > 0) {
                $remainedTime = $expireTimePassed;
            } else { // otherwise check for complete or partial payment, it any check passed order time
                $completePayment = $detail->hasCompletePaid();
                $partialPayment = $detail->hasAnyPaid();

                if ($completePayment || $partialPayment) {
                    $remainedTime = $orderTimePassed;
                }
            }
        }

        return $remainedTime;
    }
}
