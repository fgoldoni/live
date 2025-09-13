<?php

namespace Core\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ReferralTrait
{
    public static function bootReferralTrait(): void
    {
        static::creating(function (Model $model) {
            $model->referral_code = $model->generateReferralCode();
        });
    }

    public function generateReferralCode(): string
    {
        $length = 10;
        do {
            $code = $this->randomString($length);
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    private function randomString(int $length): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max   = strlen($chars) - 1;
        $str   = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, $max)];
        }
        return $str;
    }

    public static function getUserIdByReferralCodeAndIncrementCount(string $code): ?int
    {
        $referrer = User::where('referral_code', $code)->first(['id']);
        if ($referrer) {
            $referrer->increment('referral_count');
            return $referrer->id;
        }
        return null;
    }

    protected function referralLink(): Attribute
    {
        return Attribute::get(fn (): string => route('register', [
            'ref' => $this->referral_code,
        ]));
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }
}
