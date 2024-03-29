<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=$request->user();
        if($user instanceof User){ // check if user is not admin
            $user->ForceFill([ // we insert into table without put it in fillable in model
                'last_active_at'=>Carbon::now(),
            ])->save();
        }
        return $next($request);
    }
}
