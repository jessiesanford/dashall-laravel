<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function getPage($page)
    {
        switch ($page) {
            case 'faq':
                return view('misc/faq', [
                    'title' => 'FAQ',
                ]);
                break;
            case 'terms':
                return view('misc/terms', [
                    'title' => 'Terms of Service',
                ]);
                break;
            case 'contact':
                return view('misc/contact', [
                    'title' => 'Contact Us',
                ]);
                break;
            case 'apply':
                return view('misc/apply', [
                    'title' => 'Apply to Drive',
                ]);
                break;
            case 'about':
                return view('misc/about', [
                    'title' => 'About Us',
                ]);
                break;
            case 'privacy':
                return view('misc/privacy', [
                    'title' => 'Privacy Policy',
                ]);
                break;
            default:
                abort(404);
        }

    }

}
