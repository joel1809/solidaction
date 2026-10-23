<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Élément de la galerie d'un article : image ou vidéo, envoyée sur le
 * serveur (path) ou hébergée sur YouTube / Vimeo (url).
 */
class PostMedia extends Model
{
    protected $table = 'post_media';

    protected $fillable = ['post_id', 'type', 'path', 'url', 'caption', 'sort_order'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isEmbed(): bool
    {
        return filled($this->url);
    }

    /**
     * Adresse publique du fichier ou du lien externe.
     */
    public function getSrcAttribute(): string
    {
        return $this->isEmbed() ? $this->url : media($this->path);
    }

    /**
     * Vignette d'une vidéo YouTube, null pour les autres vidéos.
     */
    public function getThumbnailAttribute(): ?string
    {
        if ($this->type === 'image') {
            return media($this->path);
        }

        $id = static::youtubeId((string) $this->url);

        return $id ? 'https://img.youtube.com/vi/'.$id.'/hqdefault.jpg' : null;
    }

    public static function youtubeId(string $url): ?string
    {
        return preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $url, $m) ? $m[1] : null;
    }

    public static function isSupportedEmbed(string $url): bool
    {
        return static::youtubeId($url) !== null || preg_match('~vimeo\.com/(?:video/)?\d+~', $url) === 1;
    }
}
