<?php

namespace App\Casts;

use HTMLPurifier_Config;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CleanHtmlCast implements CastsAttributes
{
    /**
     * @var array
     */
    protected array $allowedHtml = [
        'img[src|alt|title|width|height|style|data-mce-src|data-mce-json]',
        'figure', 'figcaption',
        'video[src|type|width|height|poster|preload|controls]', 'source[src|type|srcset|media]',
        'audio[autoplay|controls|loop|muted|src|preload|type|controlsList]',
        'picture',
        'caption',
        'track[kind|src|srclang|label]',
        'a[href|title|target]',
        'iframe[width|height|src|frameborder|allowfullscreen]',
        'strong', 'b', 'i', 'u', 'em', 'br', 'font',
        'h1[style]', 'h2[style]', 'h3[style]', 'h4[style]', 'h5[style]', 'h6[style]',
        'p[style]', 'div[style]', 'center', 'address[style]',
        'span[style]', 'pre[style]',
        'ul', 'ol', 'li',
        'table[width|height|border|style]', 'th[width|height|border|style]',
        'tbody[style]', 'tfoot[style]', 'thead[style]',
        'tr[width|height|border|style]', 'td[width|height|border|style]', 'th[width|height|border|style]',
        'hr',
        'abbr', 'blockquote', 'cite', 'code',
        'article', 'aside', 'bdi', 'bdo',
        'col', 'colgroup',
        'data', 'datalist',
        'dd', 'del', 'details', 'dfn', 'dl', 'dt',
        'hgroup', 'ins', 'kbd', 'label', 'legend', 'mark',
        'meter', 'nav', 'object',
        'optgroup', 'option',
        'progress', 'q', 'rp', 'rt', 'section',
        'small', 'sub', 'summary', 'sup',
        'svg', 'time', 'var', 'wbr',
    ];

    /**
     * @var HTMLPurifier_Config
     */
    protected HTMLPurifier_Config $config;

    public function __construct()
    {
        $this->config = HTMLPurifier_Config::createDefault();
        $this->config->set('HTML.Allowed', implode(',', $this->allowedHtml));
    }

    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return clean($value, $this->config);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return clean($value, $this->config);
    }
}
