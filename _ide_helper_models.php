<?php

declare(strict_types=1);

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Models{
    use Illuminate\Support\Carbon;

    /**
     * @property int $id
     * @property string $name
     * @property string $image
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereImage($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereUpdatedAt($value)
     */
    class Brand extends \Eloquent {}
}

namespace App\Models{
    use Illuminate\Support\Carbon;

    /**
     * @property int $id
     * @property int $car_model_id
     * @property string $license_plate
     * @property string $color
     * @property bool $is_available
     * @property int $km
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereCarModelId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereColor($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereIsAvailable($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereKm($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereLicensePlate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Car whereUpdatedAt($value)
     */
    class Car extends \Eloquent {}
}

namespace App\Models{
    use Illuminate\Support\Carbon;

    /**
     * @property int $id
     * @property int $brand_id
     * @property string $uuid
     * @property string $name
     * @property string $image
     * @property int $doors
     * @property int $seats
     * @property bool $airbags
     * @property bool $abs
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereAbs($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereAirbags($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereBrandId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereDoors($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereImage($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereSeats($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|CarModel whereUpdatedAt($value)
     */
    class CarModel extends \Eloquent {}
}

namespace App\Models{
    use Illuminate\Support\Carbon;

    /**
     * @property int $id
     * @property string $name
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
     */
    class Client extends \Eloquent {}
}

namespace App\Models{
    use Illuminate\Support\Carbon;

    /**
     * @property int $id
     * @property int $car_id
     * @property int $client_id
     * @property string $start_date
     * @property string $end_date
     * @property int $day_price_cents
     * @property int $initial_km
     * @property int $final_km
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereCarId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereClientId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereDayPriceCents($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereFinalKm($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereInitialKm($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|Rental whereUpdatedAt($value)
     */
    class Rental extends \Eloquent {}
}

namespace App\Models{
    use Illuminate\Notifications\DatabaseNotification;
    use Illuminate\Notifications\DatabaseNotificationCollection;
    use Illuminate\Support\Carbon;

    /**
     * @property int $id
     * @property string $name
     * @property string $email
     * @property Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $remember_token
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     *
     * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
     */
    class User extends \Eloquent {}
}
