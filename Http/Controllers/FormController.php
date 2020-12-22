<?php

namespace Modules\Laralite\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Laralite\Mail\FormSubmitted;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mail;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
    public function submit(Request $request)
    {
        if ($request->has('form')) {
            Mail::to('digital@trapmusicmuseum.us')->send(new FormSubmitted($request->get('form')));

            return new JsonResponse([
                'success' => true,
                'message' => 'Email sent',
            ], Response::HTTP_OK);
        }

        return redirect('/');
    }
}
