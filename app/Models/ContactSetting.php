<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ContactSetting extends Model
{
    protected $table = 'contacts_settings';

    protected $fillable = [
        'type',
        'label',
        'icon_file',
        'icon_class',
        'url',
        'value',
        'display_order',
        'is_active',
        'block',
        'custom_attributes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'custom_attributes' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBlock($query, string $block)
    {
        return $query->where('block', $block);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }

    public static function getActiveContacts(string $block)
    {
        return static::active()->block($block)->ordered()->get();
    }

    public function getIconUrlAttribute(): ?string
    {
        if (!$this->icon_file) {
            return null;
        }

        return asset('storage/' . $this->icon_file);
    }

    public function getIconExtensionAttribute(): ?string
    {
        if (!$this->icon_file) {
            return null;
        }

        return pathinfo($this->icon_file, PATHINFO_EXTENSION);
    }

    public function renderIcon(int $width = 20, int $height = 20): HtmlString
    {
        if ($this->icon_file) {
            $svgPath = storage_path('app/public/' . $this->icon_file);
            $extension = $this->icon_extension;

            if ($extension === 'svg' && file_exists($svgPath)) {
                $content = file_get_contents($svgPath);
                $content = $this->sanitizeSvg($content);
                $content = $this->addSvgDimensions($content, $width, $height);

                return new HtmlString($content);
            }

            if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                $url = $this->icon_url;

                return new HtmlString(
                    '<img src="' . e($url) . '" alt="' . e($this->label) . '" width="' . $width . '" height="' . $height . '" class="contact-icon">'
                );
            }
        }

        if ($this->icon_class) {
            return new HtmlString('<i class="' . e($this->icon_class) . '"></i>');
        }

        return new HtmlString('');
    }

    public function getCustomAttributesString(): HtmlString
    {
        if (empty($this->custom_attributes)) {
            return new HtmlString('');
        }

        $parts = [];
        foreach ($this->custom_attributes as $key => $value) {
            $parts[] = $key . '="' . e($value) . '"';
        }

        return new HtmlString(implode(' ', $parts));
    }

    public static function booted(): void
    {
        static::deleting(function (ContactSetting $setting) {
            if ($setting->icon_file) {
                $path = storage_path('app/public/' . $setting->icon_file);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        });
    }

    private function sanitizeSvg(string $svg): string
    {
        $svg = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $svg);
        $svg = preg_replace('/\bon\w+\s*=\s*"[^"]*"/i', '', $svg);
        $svg = preg_replace('/\bon\w+\s*=\s*\'[^\']*\'/i', '', $svg);
        $svg = preg_replace('/\bxlink:href\s*=\s*"[^#][^"]*"/i', '', $svg);

        return $svg;
    }

    private function addSvgDimensions(string $svg, int $width, int $height): string
    {
        $svg = preg_replace('/\swidth\s*=\s*["\'][^"\']*["\']/i', '', $svg);
        $svg = preg_replace('/\sheight\s*=\s*["\'][^"\']*["\']/i', '', $svg);

        $svg = preg_replace('/<svg/i', '<svg width="' . $width . '" height="' . $height . '"', $svg);
        if (!preg_match('/xmlns/i', $svg)) {
            $svg = preg_replace('/<svg/i', '<svg xmlns="http://www.w3.org/2000/svg"', $svg);
        }

        return $svg;
    }
}