<?php

namespace App\Helpers;

class FlashAlert
{
    /**
     * Ajouter un message de succès
     */
    public static function success(string $message, ?string $title = null): void
    {
        session()->flash('flash_alert', [
            'type' => 'success',
            'message' => $message,
            'title' => $title ?? 'Succès',
        ]);
    }

    /**
     * Ajouter un message d'erreur
     */
    public static function error(string $message, ?string $title = null): void
    {
        session()->flash('flash_alert', [
            'type' => 'error',
            'message' => $message,
            'title' => $title ?? 'Erreur',
        ]);
    }

    /**
     * Ajouter un message d'information
     */
    public static function info(string $message, ?string $title = null): void
    {
        session()->flash('flash_alert', [
            'type' => 'info',
            'message' => $message,
            'title' => $title ?? 'Information',
        ]);
    }

    /**
     * Ajouter un message d'avertissement
     */
    public static function warning(string $message, ?string $title = null): void
    {
        session()->flash('flash_alert', [
            'type' => 'warning',
            'message' => $message,
            'title' => $title ?? 'Avertissement',
        ]);
    }

    /**
     * Récupérer tous les messages flash
     */
    public static function get(): array
    {
        return session()->get('flash_alert', []);
    }

    /**
     * Vérifier s'il y a des messages
     */
    public static function has(): bool
    {
        return session()->has('flash_alert');
    }
}
