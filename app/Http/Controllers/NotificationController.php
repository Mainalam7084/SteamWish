<?php

namespace App\Http\Controllers;

use App\Models\PriceNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController
{
    /**
     * Página completa de notificaciones.
     */
    public function index(): View
    {
        $notifications = Auth::user()
            ->priceNotifications()
            ->latest()
            ->get();

        // Marcar todas como leídas al visitar la página
        Auth::user()
            ->priceNotifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('pages.notifications', compact('notifications'));
    }

    /**
     * API: últimas 5 notificaciones para el dropdown de la navbar.
     */
    public function preview(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['notifications' => [], 'unread' => 0]);
        }

        $user = Auth::user();

        $notifications = $user->priceNotifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($n) => [
                'id'               => $n->id,
                'appid'            => $n->appid,
                'game_name'        => $n->game_name,
                'game_image'       => $n->game_image,
                'old_price'        => $n->old_price_formatted,
                'new_price'        => $n->new_price_formatted,
                'discount_percent' => $n->discount_percent,
                'is_unread'        => $n->isUnread(),
                'created_at'       => $n->created_at->diffForHumans(),
            ]);

        $unread = $user->priceNotifications()->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $notifications,
            'unread'        => $unread,
        ]);
    }

    /**
     * Marcar una notificación como leída.
     */
    public function markRead(int $id): JsonResponse
    {
        $notification = Auth::user()
            ->priceNotifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['ok' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas.
     */
    public function markAllRead(): JsonResponse
    {
        Auth::user()
            ->priceNotifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
