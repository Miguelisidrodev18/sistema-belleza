<?php

namespace App\Enums;

enum MaterialAttachmentType: string
{
    case Pdf      = 'pdf';
    case Video    = 'video';
    case Image    = 'image';
    case Document = 'document';
    case Link     = 'link';
    case Archive  = 'archive';
    case Other    = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Pdf      => 'PDF',
            self::Video    => 'Video',
            self::Image    => 'Imagen',
            self::Document => 'Documento',
            self::Link     => 'Enlace',
            self::Archive  => 'Archivo comprimido',
            self::Other    => 'Otro',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pdf      => '📄',
            self::Video    => '🎥',
            self::Image    => '🖼️',
            self::Document => '📝',
            self::Link     => '🔗',
            self::Archive  => '📦',
            self::Other    => '📎',
        };
    }
}
