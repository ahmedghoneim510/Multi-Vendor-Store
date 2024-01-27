<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next ,...$types): Response // we can take another parameter like type
    {
        $user=$request->user();
        if(!$user){
            return to_route('login');
        }
        if(!in_array($user->type,$types)){
            abort(403);
        }
        return $next($request);
    }
}
