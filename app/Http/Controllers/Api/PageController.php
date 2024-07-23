<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getPageData($id)
    {
        $page = Page::find($id);

        if ($page) {
            return response()->json($page, 200);
        } else {
            return response()->json(['message' => 'Page not found'], 404);
        }
    }
}
