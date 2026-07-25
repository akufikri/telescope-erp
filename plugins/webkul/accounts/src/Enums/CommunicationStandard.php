<?php

namespace Webkul\Account\Enums;

use Filament\Support\Contracts\HasLabel;

enum CommunicationStandard: string implements HasLabel
{
    case TELESCOPE = 'webkul';

    case EUROPEAN = 'european';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TELESCOPE => __('accounts::enums/communication-standard.telescope'),
            self::EUROPEAN => __('accounts::enums/communication-standard.european'),
        };
    }

    public static function options(): array
    {
        return [
            self::TELESCOPE->value => __('accounts::enums/communication-standard.telescope'),
            self::EUROPEAN->value => __('accounts::enums/communication-standard.european'),
        ];
    }
}
