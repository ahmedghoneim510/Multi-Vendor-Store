<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MarkNotificationAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $notification_id=$request->query('notification_id');
        if($notification_id){
            $user=Auth::user();
            if($user){
                $notread=$user->unreadNotifications()->find($notification_id);
                if($notread){
                    $notread->markAsRead();
                }
            }
        }
        return $next($request);
    }
}
