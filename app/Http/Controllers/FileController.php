<?php

namespace App\Http\Controllers;

use App\Hands;
use App\Repository\ParseFile;
use App\Repository\Win;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FileController extends Controller
{
    /**
     * Read the file
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:txt|max:2048',
        ]);
        $hands = file($request->file('file'));
        $round = new ParseFile();
        $round->readRounds($hands);
        return view('result');
    }


}
